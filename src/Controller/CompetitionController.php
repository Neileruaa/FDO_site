<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Competition;
use App\Form\CompetitionType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
		return $this->render('competition/showCompetition.html.twig', [
			'form' => $form->createView(),
			'competitions'=>$compet
		]);
	}

	/**
	 * l'id est celui de la competition
	 * @Route("/competition/addTeam/{id}", name="Competition.addTeam")
	 * @param ObjectManager $manager
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function addTeamToCompetition(ObjectManager $manager, Request $request, Competition $competition) {
		$listTeams = self::checkDanceOfTeam($competition->getDances()->toArray(), $this->getUser()->getTeams()->toArray());
		return $this->render('competition/addTeam.html.twig',[
			'teams' => $listTeams,
			'compet' => $competition
		]);
	}

	private function checkDanceOfTeam($availableDances, $teams){
		dump($availableDances);
		$teamWhoCanRegister = array();
		foreach ($teams as $team){
			foreach ($team->getDances()->toArray() as $team_dance){
				foreach ($availableDances as $dance){
					if ($team_dance->getNameDance() == $dance->getNameDance()){
						array_push($teamWhoCanRegister, $team);
					}
				}
			}
		}
		$teamWhoCanRegister = self::removeDuplicateItem($teamWhoCanRegister);
		dump($teamWhoCanRegister);
		return $teamWhoCanRegister;
	}

	private function removeDuplicateItem($array){
		$new = array();
		foreach ($array as $value){
			$new[serialize($value)] = $value;
		}
		return array_values($new);
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
