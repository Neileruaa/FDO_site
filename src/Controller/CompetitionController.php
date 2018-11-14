<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompetitionController extends AbstractController {
	/**
	 * @Route("/admin/createCompetition", name="admin.createCompetition")
	 */
	public function createCompetition(Request $request, ObjectManager $manager) {
		$competition = new Competition();
		$form = $this->createForm(CompetitionType::class, $competition);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$manager->persist($competition);
			$manager->flush();
			return $this->redirectToRoute('security.login');
		}
		return $this->render('admin/createCompetition.html.twig', ['form' => $form->createView()]);
	}
}
