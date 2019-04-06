<?php

namespace App\Controller;


use App\Entity\Competition;
use App\Entity\Team;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller {
    /**
     * @Route("/", name="Home.index")
     * @param ObjectManager $manager
     * @return Response
     */
    public function index(ObjectManager $manager) {
        $firstCompet=$manager->getRepository(Competition::class)->findOneBy([], ["dateCompetition"=> "ASC"]);
        return $this->render(
        	'home/index.html.twig',
	        ["firstCompet"=>$firstCompet]
        );
    }
}
