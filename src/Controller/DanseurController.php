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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DanseurController extends AbstractController {
	/**
	 * @Route("/removeDancer/{id}", name="dancer_removeDancer", requirements={"page"="\d+"})
	 * @param Dancer $dancer
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function removeDancer(Dancer $dancer) {
		$em=$this->getDoctrine()->getManager();
		$em->remove($dancer);
		$em->flush();
		return $this->redirectToRoute('page4');
	}
}