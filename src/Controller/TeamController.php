<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Competition;
use App\Entity\Dance;
use App\Entity\Team;
use App\Entity\Dancer;
use App\Form\TeamType;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
 * Class TeamController
 * @package App\Controller
 */
class TeamController extends AbstractController
{
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
            $team->setIsPresent(true);
			$list_dancers = $form->get("dancers")->getData();
			$list_dances=$form->get("dances")->getData();

			if (!$this->verifTeam($list_dancers, $list_dances)){
                $this->addFlash('danger', 'Impossible, un danseur ne peut pas danser la même danse dans 2 équipes différentes de mêmes tailles');
                return $this->render(
                    'team/createTeam.html.twig',
                    array(
                        'formEquipe' => $form->createView(),
                        'listEquipe' => $list_teams,
                        'club' => $club
                    )
                );
            }

            $sizeTeam=count($list_dancers);
			$team = $form->getData();
			if ($sizeTeam==1) $team->setSize("solo");
			elseif ($sizeTeam==2) $team->setSize("duo");
			elseif ($sizeTeam>=4 && $sizeTeam<=8) $team->setSize("smallGroup");
			elseif ($sizeTeam>=9 && $sizeTeam<=24) $team->setSize("formation");
			else {
                $this->addFlash('danger', 'Une équipe ne peut être constituée que de 1 ou 2 danseurs, ou de 4 à 24 danseurs.');
                return $this->render(
                    'team/createTeam.html.twig',
                    array(
                        'formEquipe' => $form->createView(),
                        'listEquipe' => $list_teams,
                        'club' => $club
                    )
                );
            }
            $currentDate=intval(date("Y"));

			foreach ($list_dancers  as $dancer){
                $birthDateDancer=intval($dancer->getDateBirthDancer()->format("Y"));
                if ($sizeTeam==2){
                    $ecart = abs(abs(intval($list_dancers{0}->getDateBirthDancer()->format("Y")))-abs(intval($list_dancers{1}->getDateBirthDancer()->format("Y"))));

                    $age1=$currentDate-intval($list_dancers{0}->getDateBirthDancer()->format("Y"));
                    $age2=$currentDate-intval($list_dancers{1}->getDateBirthDancer()->format("Y"));
                    if ($age1>=16 && $age2>=16);
                    else if ($ecart>4) {
                            $this->addFlash('danger', 'L\'écart entre les deux danseurs est supérieur à 4 ans');
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
                        if (!$this->verifRock($list_dances)){
                            if($ageDancer>=5 && $ageDancer<=11) $team->setCategory($enfant);
                            elseif ($ageDancer>=10 && $ageDancer<=15) $team->setCategory($junior);
                            elseif ($ageDancer>=14 && $ageDancer<100) $team->setCategory($adulte);
                            else {
                                $this->addFlash('danger', 'Le danseur n\'a pas l\'âge requis');
                                return $this->render(
                                    'team/createTeam.html.twig',
                                    array(
                                        'formEquipe'=>$form->createView(),
                                        'listEquipe'=>$list_teams,
                                        'club'=>$club
                                    )
                                );}
                        }
                        else{
                            if ($ageDancer>=6 && $ageDancer<=12) $team->setCategory($enfant);
                            elseif ($ageDancer>=13 && $ageDancer<=100) $team->setCategory($adulte);
                            else {
                                $this->addFlash('danger', 'Le danseur n\'a pas l\'âge requis');
                                return $this->render(
                                    'team/createTeam.html.twig',
                                    array(
                                        'formEquipe'=>$form->createView(),
                                        'listEquipe'=>$list_teams,
                                        'club'=>$club
                                    )
                                );}
                        }
                        break;
                    case 2:
                        if (!$this->verifRockPietine($list_dances)){
                            if($ageDancer>=5 && $ageDancer<=11) $team->setCategory($enfant);
                            elseif ($ageDancer>=10 && $ageDancer<=15) $team->setCategory($junior);
                            elseif ($ageDancer>=14 && $ageDancer<100) $team->setCategory($adulte);
                            else {
                                $this->addFlash('danger', 'Un des danseurs n\'a pas l\'âge requis');
                                return $this->render(
                                    'team/createTeam.html.twig',
                                    array(
                                        'formEquipe'=>$form->createView(),
                                        'listEquipe'=>$list_teams,
                                        'club'=>$club
                                    )
                                );}
                        }
                        else{
                            if ($ageDancer>=6 && $ageDancer<=12) $team->setCategory($enfant);
                            elseif ($ageDancer>=13 && $ageDancer<=100) $team->setCategory($adulte);
                            else {
                                $this->addFlash('danger', 'Le danseur n\'a pas l\'âge requis');
                                return $this->render(
                                    'team/createTeam.html.twig',
                                    array(
                                        'formEquipe'=>$form->createView(),
                                        'listEquipe'=>$list_teams,
                                        'club'=>$club
                                    )
                                );}
                        }
                        break;
                    default:
                        $somme=0;
                        foreach ($list_dancers as $d){
                            if($currentDate-($d->getDateBirthDancer()->format("Y"))<5){
                                    $this->addFlash('danger', 'Un des danseurs n\'a pas l\'âge requis');
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
                        if (!$this->verifRockPietine($list_dances)) {
                            if ($moyenneAge >= 5 && $moyenneAge <= 11) $team->setCategory($enfant);
                            elseif ($moyenneAge >= 10 && $moyenneAge <= 15) $team->setCategory($junior);
                            elseif ($moyenneAge >= 14 && $moyenneAge < 100) $team->setCategory($adulte);
                            else {
                                $this->addFlash('danger', 'Un des danseurs n\'a pas l\'âge requis');
                                return $this->render(
                                    'team/createTeam.html.twig',
                                    array(
                                        'formEquipe' => $form->createView(),
                                        'listEquipe' => $list_teams,
                                        'club' => $club
                                    )
                                );
                            }
                        }
                        else{
                            if ($moyenneAge >= 6 && $moyenneAge <= 12) $team->setCategory($enfant);
                            elseif ($moyenneAge >= 13 && $moyenneAge < 100) $team->setCategory($adulte);
                            else {
                                $this->addFlash('danger', 'Un des danseurs n\'a pas l\'âge requis');
                                return $this->render(
                                    'team/createTeam.html.twig',
                                    array(
                                        'formEquipe' => $form->createView(),
                                        'listEquipe' => $list_teams,
                                        'club' => $club
                                    )
                                );
                            }
                        }
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
     * @param Dancer[] $dancers
     * @param Dance[] $dances
     * @return bool
     */
	public function verifTeam($dancers, $dances){
        $sizeTeam=count($dancers);
        foreach ($dancers as $dancer){
            foreach ($dancer->getTeams() as $team){
                if (count($team->getDancers())==$sizeTeam){
                    foreach ($team->getDances() as $danceTeam){
                        if ($team->getId()!=null){
                            foreach ($dances as $dance){
                                if($dance->getNameDance()==$danceTeam->getNameDance()){
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * @param $dances
     * @return bool
     */
    public function verifRock($dances){
	    foreach ($dances as $dance){
	        if (($dance->getNameDance()=="rock pietine") || ($dance->getNameDance()=="rock saute")){
                return true;
            }
        }
	    return false;
    }

	/**
	 * @Route("/team/delete/{id}", name="Team.delete", requirements={"id" = "\d+"})
	 * @param Team $team
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
    public function deleteTeam(Team $team, ObjectManager $manager, Request $request){
        $manager->remove($team);
        $manager->flush();
        if ($request->headers->all()['referer'][0] == $this->generateUrl("Team.create", array(),UrlGeneratorInterface::ABSOLUTE_URL)){
            return $this->redirectToRoute('Team.create');
        }else{
            return $this->redirectToRoute('Team.showAll');
        }
    }

	/**
	 * @Route("/deleteAll/team" , name="Team.deleteAll")
	 */
	public function deleteAllTeams(Request $request, ObjectManager $manager){
		if ($request->get("submit")!=null){
			$confirm=1;
			return $this->redirectToRoute("Team.create", ["confirm"=>$confirm]);
		}
		$user=$this->getUser();
		$teams=$user->getTeams();

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

    /**
     * @Route("team/showAll", name="Team.showAll")
     * @isGranted("ROLE_ADMIN")
     */
	public function showAllTeams(PaginatorInterface $paginator, Request $request){
        $list_team=$paginator->paginate($this->getDoctrine()->getRepository(Team::class)->findAll(),
            $request->query->getInt('page', 1),10
        );
        return $this->render('team/showAll.html.twig', ["teams"=>$list_team]);
    }

    /**
     * @Route("/team/edit/{id}", name="Team.edit", requirements={"page"="\d+"})
     * @param Team $team
     * @isGranted("ROLE_ADMIN")
     */
    public function editTeam(Team $team, Request $request){
        $em=$this->getDoctrine()->getManager();
        $form=$this->createForm(TeamType::class, $team, array('club' => $this->getUser()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $team=$form->getData();
            //$category=$this->getDoctrine()->getRepository(Category::class)->findAll();
            switch ($request->get('category')){
                case 1:
                    $enfant=$em->getRepository(Category::class)->find(1);
                    $team->setCategory($enfant);
                    break;
                case 2:
                    $junior=$em->getRepository(Category::class)->find(2);
                    $team->setCategory($junior);
                    break;
                case 3:
                    $adulte=$em->getRepository(Category::class)->find(3);
                    $team->setCategory($adulte);
                    break;
            }
            $em->persist($team);
            $em->flush();
            return $this->redirectToRoute('Team.showAll');
        }elseif ($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('danger', 'Il y a eut une erreur lors de la modification de cette équipe');
        }

        return $this->render('team/editTeam.html.twig', ["form"=>$form->createView(), 'team'=>$team]);
    }
}
