<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Competition;
use App\Entity\Team;
use App\Form\CompetitionType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
	 * @Route("/competition/viewDetails/{id}", name="Competition.viewDetails")
	 * @param Competition $competition
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function detailsCompetition(Competition $competition) {
		return $this->render('competition/viewDétailsCompetition.html.twig',[
			'compet' => $competition
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
		$listTeams = self::checkDanceOfTeam($competition->getDances()->toArray(), $this->getUser()->getTeams()->toArray(), $competition);
		return $this->render('competition/addTeam.html.twig',[
			'teams' => $listTeams,
			'compet' => $competition
		]);
	}

	private function checkDanceOfTeam($availableDances, $teams, $competition){
		$teamWhoCanRegister = array();
		$idC=$competition->getId();
        foreach ($teams as $team){
            $cs=$team->getCompetitions();
            $compets=[];
            foreach ($team->getDances()->toArray() as $team_dance){
				foreach ($availableDances as $dance){
				    foreach ($cs as $compet){
				        array_push($compets, $compet->getId());
                    }
					if ($team_dance->getNameDance() == $dance->getNameDance() and in_array($idC,$compets)==false){
						array_push($teamWhoCanRegister, $team);
					}
				}
			}
		}
		$teamWhoCanRegister = self::removeDuplicateItem($teamWhoCanRegister);
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
     * @Route("/competition/delete/team/{id}/{idC}", name="Competition.deleteTeam")
     * @param $id
     * @param $idC
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
	public function removeTeamFromCompetition($id, $idC, ObjectManager $manager){
	    $id=$manager->getRepository(Team::class)->find($id);
	    $idC=$manager->getRepository(Competition::class)->find($idC);
        $idC->removeTeam($id);
        $id->removeCompetition($idC);
        $manager->persist($idC);
        $manager->persist($id);
        $manager->flush();
        return $this->redirectToRoute("Competition.viewDetails", ["id"=>$idC->getId()]);
    }

    /**
     * @Route("/competition/delete/{id}", name="Competition.delete", requirements={"page"="\d+"})
     * @isGranted("ROLE_ADMIN")
     * @param Competition $competition
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
	public function deleteCompetition(Competition $competition, ObjectManager $manager){
        $manager->remove($competition);
        $manager->flush();
        return $this->redirectToRoute("Competition.show");
    }

    /**
     * @Route("/competition/edit/{id}", name="Competition.edit", requirements={"page"="\d+"})
     * @param Competition $compet
     * @param ObjectManager $manager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @isGranted("ROLE_ADMIN")
     */
    public function editCompetition(Competition $compet, ObjectManager $manager, Request $request){
        $form=$this->createForm(CompetitionType::class, $compet);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($compet);
            $manager->flush();
            return $this->redirectToRoute('Competition.show');
        }
        return $this->render('competition/editCompetition.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
