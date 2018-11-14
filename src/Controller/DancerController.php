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
use App\Form\DancerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DancerController extends AbstractController {
	/**
	 * @Route("/dancer/remove/{id}", name="Dancer.remove", requirements={"page"="\d+"})
	 * @param Dancer $dancer
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function removeDancer(Dancer $dancer) {
		$em=$this->getDoctrine()->getManager();
		$list_teams = $dancer->getTeams();
		foreach ($list_teams as $team){
			$em->remove($team);
			$em->flush();
		}
		$em->remove($dancer);
		$em->flush();
		return $this->redirectToRoute('Dancer.create');
	}

	/**
	 * @Route("/dancer/create", name="Dancer.create")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function createDancer(Request $request) {
		$club = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$dancer = new Dancer();
		$form= $this->createForm(DancerType::class, $dancer);
		$list_dancers = $em->getRepository(Dancer::class)->findAll();
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values
			// but, the original `$task` variable has also been updated
			$dancerToSave = $form->getData();
			$dancerToSave->setClub($club);
			$club->addDancer($dancerToSave);
			$em->persist($dancerToSave);
			$em->flush();

			return $this->redirectToRoute('Dancer.create');
		}
		return $this->render(
			'dancer/createDancer.html.twig',
			array(
				'formDanseur'=>$form->createView(),
				'listDancer'=>$list_dancers,
				'club'=>$club
			)
		);
	}
}