<?php

namespace App\Controller;

use App\Entity\Club;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClubController extends AbstractController
{
    /**
     * @Route("/club", name="Club.showAll")
     * @IsGranted("ROLE_ADMIN")
     */
    public function showAll()
    {
        $clubs=$this->getDoctrine()->getRepository(Club::class)->findAll();
        return $this->render('club/showAll.html.twig', [
            'clubs'=>$clubs
        ]);
    }

    /**
     * @Route("/club/delete/{id}", name="Club.delete", requirements={"page"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public  function deleteClub(Club $club, ObjectManager $manager){
        $compets=$club->getCompetitions();
        foreach ($compets as $c){
            $manager->remove($c);
        }
        $tickets= $club->getTickets();
        foreach ($tickets as $ticket){
            $manager->remove($ticket);
        }
        $manager->remove($club);
        $manager->flush();
        return $this->redirectToRoute("Club.showAll");
    }
}
