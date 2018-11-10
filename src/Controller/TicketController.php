<?php

namespace App\Controller;

use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    /**
     * @Route("/ticket", name="ticket")
     */
    public function index()
    {
        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
        ]);
    }
    /**
     * @Route("/add/ticket",name="Ticket.addTicket")
     */
    public function addTicket(){
        return $this->render('ticket/addTicket.html.twig');
    }
    /**
     * @Route("/show/ticket" , name="Ticket.showTicket")
     */
    public function showTicket(){
        $em=$this->getDoctrine()->getManager();
        $tickets=$em->getRepository(Ticket::class)->findAll();
        return $this->render('ticket/showTicket.html.twig',['tickets'=>$tickets]);
    }

}
