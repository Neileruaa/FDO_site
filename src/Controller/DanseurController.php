<?php
/**
 * Created by PhpStorm.
 * User: aurelien
 * Date: 13/10/18
 * Time: 11:58
 */

namespace App\Controller;


use App\Entity\Dancer;
use App\Entity\Danseur;
use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DanseurController extends AbstractController {
	/**
	 * @Route("/removeDancer/{id}", name="dancer_removeDancer", requirements={"page"="\d+"})
	 * @param Dancer $dancer
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function removeDancer(Dancer $dancer) {
	    //rggs
		$em=$this->getDoctrine()->getManager();
		$list_teams = $dancer->getTeams();
		foreach ($list_teams as $team){
			$em->remove($team);
			$em->flush();
		}
		$em->remove($dancer);
		$em->flush();
		return $this->redirectToRoute('page4');
	}
}