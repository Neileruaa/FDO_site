<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Doctrine\Common\Persistence\ObjectManager;
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
	 * @Route("/show", name="Ticket.show")
	 * @Route("/show/{id}", name="Ticket.show")
	 * @param TicketRepository $ticketRepository
	 * @param ObjectManager $manager
	 * @param int $id
	 * @return Response
	 */
	public function show(TicketRepository $ticketRepository, ObjectManager $manager, $id=0){
		if ($this->getUser()->getRoles() == ["ROLE_ADMIN"])
			$tickets = $ticketRepository->findAll();
		else
			$tickets = $this->getUser()->getTickets();
		if ($id!=0) {
			$ticket = $manager->getRepository(Ticket::class)->find($id);
			if (isset($_POST['submit'])) {
				if (isset($_POST['etat']) && $_POST['etat'] != 'default') {
					dump($_POST['etat']);
					$ticket->setEtat($_POST['etat']);
					$manager->persist($ticket);
					$manager->flush();
				}
			}
		}

		return $this->render('ticket/show.twig', ['tickets' => $tickets]);
	}

	/**
	 * @Route("/new", name="Ticket.new", methods="GET|POST")
	 * @IsGranted("ROLE_USER")
	 */
	public function new(Request $request, \Swift_Mailer $mailer): Response {
		$ticket = new Ticket();
		$form = $this->createForm(TicketType::class, $ticket);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$ticket = $form->getData();
			$ticket->setCreatedAt(new \DateTime());
			$ticket->setAuthor($this->getUser());
			$ticket->setEtat("attente");

			$em->persist($ticket);
			$em->flush();

			$message = (new \Swift_Message('Nouveau ticket'))
				->setFrom($this->getUser()->getEmailClub())
				//TODO: mettre le mail de l'admin
				->setTo('aureliendrey@gmail.com')
				->setBody(
					$this->renderView(
						'emails/ticketCreated.html.twig',[
							'name' => $this->getUser()->getNameClubOwner(),
							'club' => $this->getUser()->getUsername(),
							'ticket' => $ticket
						]
					),
					'text/html'
				);
			$mailer->send($message);

			$this->addFlash('success', 'Votre ticket a bien été envoyé');

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

			$this->addFlash('success', 'Votre ticket a bien été envoyé !');

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
