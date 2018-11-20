<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Competition;
use App\Form\CompetitionType;
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
	 * @Route("/competition/show", name="Competition.show")
	 */
	public function createCompetition(Request $request, ObjectManager $manager) {
        $compet=$this->getDoctrine()->getRepository(Competition::class)->findAll();
		$competition = new Competition();
		$form = $this->createForm(CompetitionType::class, $competition);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$manager->persist($competition);
			$manager->flush();
			return $this->redirectToRoute('Competition.show');
		}
		return $this->render('competition/showCompetition.html.twig', ['form' => $form->createView(),'competitions'=>$compet]);
	}

    /**
     * @Route("/competition/delete/{id}", name="Competition.delete", requirements={"page"="\d+"})
     */
	public function deleteCompetition(Competition $competition, ObjectManager $manager){

        $manager->remove($competition);
        $manager->flush();
        return $this->redirectToRoute("Competition.show");
    }
}
