<?php
/**
 * Created by PhpStorm.
 * User: aurelien
 * Date: 13/10/18
 * Time: 11:58
 */

namespace App\Controller;


use App\Entity\Danseur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DanseurController extends AbstractController {
	/**
	 * @Route("/removeDancer/{id}", name="dancer_removeDancer", requirements={"page"="\d+"})
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function removeDancer($id) {
		$em=$this->getDoctrine()->getManager();
		$danseurToRemove = $em->getRepository(Danseur::class)
							->findOneBy(['id'=>$id]);
		$em->remove($danseurToRemove);
		$em->flush();
		return $this->redirectToRoute('page4');
	}
}