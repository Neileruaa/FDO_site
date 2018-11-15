<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ticket")
 * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
 */
class TicketController extends AbstractController {
	/**
	 * @Route("/show", name="Ticket.show", methods="GET")
	 */
	public function show(TicketRepository $ticketRepository): Response {
		if ($this->getUser()->getRoles() == 'ROLE_ADMIN')
			$tickets = $ticketRepository->findAll();
		else
			$tickets = $this->getUser()->getTickets();
		return $this->render('ticket/show.twig', ['tickets' => $tickets]);
	}


	//TODO: Methodes pour voir uniquement les tickets d'un club

	/**
	 * @Route("/new", name="Ticket.new", methods="GET|POST")
	 * @IsGranted("ROLE_USER")
	 */
	public function new(Request $request): Response {
		$ticket = new Ticket();
		$form = $this->createForm(TicketType::class, $ticket);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$ticket = $form->getData();
			$ticket->setCreatedAt(new \DateTime());
			$ticket->setAuthor($this->getUser());

			$em->persist($ticket);
			$em->flush();

			//TODO:Faire un message = "Ticket bien envoyé"
			return $this->redirectToRoute('Ticket.show');
		}

		return $this->render('ticket/createTicket.twig', ['ticket' => $ticket, 'form' => $form->createView(),]);
	}

	/**
	 * @Route("/{id}", name="Ticket.viewDetails", methods="GET")
	 */
	public function showDetails(Ticket $ticket): Response {
		return $this->render('ticket/viewDetails.twig', ['ticket' => $ticket]);
	}

	/**
	 * @Route("/edit/{id}", name="Ticket.edit", methods="GET|POST")
	 * @IsGranted("ROLE_USER")
	 */
	public function edit(Request $request, Ticket $ticket): Response {
		$form = $this->createForm(TicketType::class, $ticket);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute('Ticket.show', ['id' => $ticket->getId()]);
		}

		return $this->render('ticket/edit.html.twig', ['ticket' => $ticket, 'form' => $form->createView(),]);
	}

	/**
	 * @Route("/delete/{id}", name="Ticket.delete", methods="DELETE")
	 */
	public function delete(Request $request, Ticket $ticket): Response {
		if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($ticket);
			$em->flush();
		}
		return $this->redirectToRoute('Ticket.show');
	}
}
