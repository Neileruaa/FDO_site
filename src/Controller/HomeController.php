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
        $teams=$this->getDoctrine()->getRepository(Team::class)->findAll();
        $compteur=1;

        foreach ($teams as $t){
            $t->setNumDossard($compteur);
            $t->setNombreDeDanceurs(sizeof($teams));
            $compteur=$compteur+1;
        }

        return $this->render(
        	'home/index.html.twig',
	        array()
        );
    }
}
