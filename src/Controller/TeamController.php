<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
 * Class TeamController
 * @package App\Controller
 */
class TeamController extends AbstractController
{
	/**
	 * @Route("/team/surclassement", name="Team.surclassement")
	 * @IsGranted("ROLE_ADMIN")
	 */
	public function surclasserTeam(ObjectManager $manager) {
		$allTeams = $manager->getRepository(Team::class)->findAll();
		return $this->render('admin/surclassement.html.twig', ['teams' => $allTeams]);
	}

	/**
	 * @Route("/team/create", name="Team.create")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function createTeam(Request $request) {
		//TODO: Correct this error
		//$formDel=$this->createDeleteForm();
		$confirm=0;
		$club = $this->getUser();
		$team = new Team();
		$form = $this->createForm(TeamType::class, $team);
		$em = $this->getDoctrine()->getManager();
		//$team->addCategory($em->getRepository(Category::class)->find(1));
		$list_teams=$em->getRepository(Team::class)->findAll();
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values
			// but, the original `$task` variable has also been updated32
			$list_dancers = $form->get("dancers")->getData();
			$team = $form->getData();
			foreach ($list_dancers  as $dancer){
				$team->addDancer($dancer);
			}
			$team->setClub($club);
			$club->addTeam($team);
			$em->persist($team);
			$em->flush();

			return $this->redirectToRoute('Team.create',["confirm"=>$confirm]);
		}
		return $this->render(
			'team/createTeam.html.twig',
			array(
				'formEquipe'=>$form->createView(),
				'listEquipe'=>$list_teams,
				'club'=>$club
				//"form"=>$formDel->createView()
			)
		);
	}

	/**
	 * @Route("/team/{id}", name="Team.show", requirements={"id" = "\d+"})
	 * @param Team $team
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showTeam(Team $team) {
		return $this->render(
			'team/showTeam.html.twig',
			['list_dancer'=>$team->getDancers()]
		);
	}

	/**
	 * @Route("/deleteAll/team" , name="Team.deleteAll")
	 */
	public function deleteAllTeams(Request $request, ObjectManager $manager){
		if ($request->get("submit")!=null){
			$confirm=1;
			return $this->redirectToRoute("Team.create", ["confirm"=>$confirm]);
		}
		$teams=$this->getDoctrine()->getRepository(Team::class)->findAll();

		foreach ($teams as $team){
			$manager->remove($team);
			$manager->flush();
		}
		return $this->redirectToRoute("Team.create");
	}
}
