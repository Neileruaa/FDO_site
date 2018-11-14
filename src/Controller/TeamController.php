<?php

namespace App\Controller;

use App\Entity\Team;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
	/**
	 * @Route("/admin/surclassement", name="admin.surclassement")
	 */
	public function surclasserTeam(ObjectManager $manager) {
		$em = $this->container->get('doctrine')->getManager();
		$repository = $em->getRepository(Team::class);

		$allTeams = $repository->findAll();

		return $this->render('admin/surclassement.html.twig', ['teams' => $allTeams]);
	}

	/**
	 * @Route("/page3", name="page3")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function page3(Request $request) {
		$formDel=$this->createDeleteForm();
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

			return $this->redirectToRoute('page3',["confirm"=>$confirm]);
		}
		return $this->render(
			'home/page3.html.twig',
			array(
				'formEquipe'=>$form->createView(),
				'listEquipe'=>$list_teams,
				'club'=>$club,
				"form"=>$formDel->createView()
			)
		);
	}

	/**
	 * @Route("/equipe/{id}", name="Equipe_description", requirements={"id" = "\d+"})
	 * @param Team $team
	 * @return Response
	 */
	public function voirEquipe(Team $team) {
		return $this->render(
			'home/showEquipe.html.twig',
			['list_dancer'=>$team->getDancers()]
		);
	}

	/**
	 * @Route("/deleteAll/team" , name="team.deleteAll")
	 */
	public function deleteAllTeams(Request $request){
		if ($request->get("submit")!=null){
			$confirm=1;
			return $this->redirectToRoute("page3", ["confirm"=>$confirm]);
		}
		$em=$this->getDoctrine()->getManager();
		$teams=$this->getDoctrine()->getRepository(Team::class)->findAll();

		foreach ($teams as $team){
			$em->remove($team);
			$em->flush();
		}
		return $this->redirectToRoute("page3");
	}
}
