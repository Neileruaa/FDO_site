<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Competition;
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
		$confirm=0;
		$club = $this->getUser();
		$team = new Team();
		$form = $this->createForm(TeamType::class, $team, array('club' => $this->getUser()));
		$em = $this->getDoctrine()->getManager();
		//$team->addCategory($em->getRepository(Category::class)->find(1));
		$list_teams=$em->getRepository(Team::class)->findAll();
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values
			// but, the original `$task` variable has also been updated32
			$list_dancers = $form->get("dancers")->getData();
            $sizeTeam=count($list_dancers);
			$team = $form->getData();

			foreach ($list_dancers  as $dancer){
                $birthDateDancer=intval($dancer->getDateBirthDancer()->format("Y"));
                if ($sizeTeam==2){
                    $ecart = abs(abs(intval($list_dancers{0}->getDateBirthDancer()->format("Y")))-abs(intval($list_dancers{1}->getDateBirthDancer()->format("Y"))));

                    if ($ecart>4) {
                        $this->addFlash('danger', 'L\'écart entre les deux danseurs est supérieur à 4 ans ! ');
                        return $this->render(
                            'team/createTeam.html.twig',
                            array(
                                'formEquipe' => $form->createView(),
                                'listEquipe' => $list_teams,
                                'club' => $club
                            )
                        );
                    }
                        if (intval($list_dancers{0}->getDateBirthDancer()->format("Y"))>intval($list_dancers{1}->getDateBirthDancer()->format("Y"))) $birthDateDancer=intval($list_dancers{0}->getDateBirthDancer()->format("Y"));
                        elseif (intval($list_dancers{0}->getDateBirthDancer()->format("Y"))<intval($list_dancers{1}->getDateBirthDancer()->format("Y"))) $birthDateDancer=intval($list_dancers{1}->getDateBirthDancer()->format("Y"));
                }
			    $currentDate=intval(date("Y"));
			    $ageDancer=$currentDate-$birthDateDancer;

			    $enfant=$em->getRepository(Category::class)->find(1);
			    $junior=$em->getRepository(Category::class)->find(2);
			    $adulte=$em->getRepository(Category::class)->find(3);

                switch ($sizeTeam){
                    case 1:
                        if($ageDancer>=5 && $ageDancer<=11) $team->setCategory($enfant);
                        elseif ($ageDancer>=10 && $ageDancer<=15) $team->setCategory($junior);
                        elseif ($ageDancer>=14 && $ageDancer<100) $team->setCategory($adulte);
                        else {
                            $this->addFlash('danger', 'Le danseur n\'a pas l\'âge requis! ');
                            return $this->render(
                            'team/createTeam.html.twig',
                            array(
                                'formEquipe'=>$form->createView(),
                                'listEquipe'=>$list_teams,
                                'club'=>$club
                            )
                        );}
                        break;
                    case 2:
                        if($ageDancer>=5 && $ageDancer<=11) $team->setCategory($enfant);
                        elseif ($ageDancer>=10 && $ageDancer<=15) $team->setCategory($junior);
                        elseif ($ageDancer>=14 && $ageDancer<100) $team->setCategory($adulte);
                        else {
                            $this->addFlash('danger', 'Un des danseurs n\'a pas l\'âge requis ! ');
                            return $this->render(
                            'team/createTeam.html.twig',
                            array(
                                'formEquipe'=>$form->createView(),
                                'listEquipe'=>$list_teams,
                                'club'=>$club
                            )
                        );}
                        break;
                    default:
                        $somme=0;
                        foreach ($list_dancers as $d){
                            if($currentDate-($d->getDateBirthDancer()->format("Y"))<5){
                                    $this->addFlash('danger', 'Un des danseurs n\'a pas l\'âge requis ! ');
                                    return $this->render(
                                        'team/createTeam.html.twig',
                                        array(
                                            'formEquipe'=>$form->createView(),
                                            'listEquipe'=>$list_teams,
                                            'club'=>$club
                                        )
                                    );
                            }
                            $somme=$somme+intval($currentDate-($d->getDateBirthDancer()->format("Y")));
                        }
                        $moyenneAge=$somme/$sizeTeam;
                        if($moyenneAge>=5 && $moyenneAge<=11) $team->setCategory($enfant);
                        elseif ($moyenneAge>=10 && $moyenneAge<=15) $team->setCategory($junior);
                        elseif ($moyenneAge>=14 && $moyenneAge<100) $team->setCategory($adulte);
                        else {
                            $this->addFlash('danger', 'Un des danseurs n\'a pas l\'âge requis ! ');
                            return $this->render(
                                'team/createTeam.html.twig',
                                array(
                                    'formEquipe'=>$form->createView(),
                                    'listEquipe'=>$list_teams,
                                    'club'=>$club
                                )
                            );}
                        break;

                }
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
	 * @Route("/team/delete/{id}", name="Team.delete", requirements={"id" = "\d+"})
	 * @param Team $team
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
    public function deleteTeam(Team $team, ObjectManager $manager){
        $manager->remove($team);
        $manager->flush();
        return $this->redirectToRoute("Team.create");
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

	/**
	 * @Route("/registerTeam/{idTeam}/{idCompet}", name="Team.registerToCompetition")
	 * @param $idTeam
	 * @param $idCompet
	 * @param ObjectManager $manager
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function registerTeamCompetition($idTeam, $idCompet, ObjectManager $manager) {
		$team = $this->getDoctrine()->getRepository(Team::class)->find($idTeam);
		$competition = $this->getDoctrine()->getRepository(Competition::class)->find($idCompet);

		$competition->addTeam($team);
		$team->addCompetition($competition);

		$manager->persist($team);
		$manager->persist($competition);
		$manager->flush();

		return $this->redirectToRoute('Competition.show');
	}
}
