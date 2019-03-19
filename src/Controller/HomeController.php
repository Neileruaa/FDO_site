<?php

namespace App\Controller;


use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller {
	/**
	 * @Route("/", name="Home.index")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function index(Request $request) {
        $manager=$this->getDoctrine()->getManager();
        $teams=$this->getDoctrine()->getRepository(Team::class)->findAll();
        $cmpt=1;
        foreach ($teams as $team){
            $team->setNumDossard($cmpt);
            $manager->persist($team);
        }
        $manager->flush();
        return $this->render(
        	'home/index.html.twig',
	        array()
        );
    }
}
