<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
 * Class CompetitionController
 * @package App\Controller
 */
class CompetitionController extends AbstractController {
	/**
	 * @Route("/competition/create", name="Competition.create")
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
