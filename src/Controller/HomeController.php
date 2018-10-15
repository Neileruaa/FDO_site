<?php

namespace App\Controller;

use App\Entity\Dancer;
use App\Entity\Danseur;
use App\Entity\Team;
use App\Form\DancerType;
use App\Form\DanseurType;
use App\Form\TeamType;
use Fpdf\Fpdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller {
	/**
	 * @Route("/", name="home")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function index(Request $request) {
        return $this->render(
        	'home/index.html.twig',
	        array()
        );
    }

	/**
	 * @Route("/page2", name="page2")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function page2(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$list_dancers = $em->getRepository(Dancer::class)
			->findAll();
		return $this->render(
			'home/page2.html.twig',
			array('dancers'=>$list_dancers)
		);
	}

	/**
	 * @Route("/page3", name="page3")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function page3(Request $request) {
		$team = new Team();
		$form = $this->createForm(TeamType::class, $team);
		$em = $this->getDoctrine()->getManager();
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
			$em->persist($team);
			$em->flush();

			return $this->redirectToRoute('page3');
		}
		return $this->render(
			'home/page3.html.twig',
			array(
				'formEquipe'=>$form->createView(),
				'listEquipe'=>$list_teams
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
	 * @Route("/page4", name="page4")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function page4(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$dancer = new Dancer();
		$form= $this->createForm(DancerType::class, $dancer);
		$list_dancers = $em->getRepository(Dancer::class)->findAll();
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values
			// but, the original `$task` variable has also been updated
			$dancerToSave = $form->getData();
			 $em->persist($dancerToSave);
			 $em->flush();

			return $this->redirectToRoute('page4');
		}
		return $this->render(
			'home/page4.html.twig',
			array(
				'formDanseur'=>$form->createView(),
				'listDancer'=>$list_dancers
			)
		);
	}

	/**
	 * @Route("/createSamplePDF", name="createSamplePDF")
	 */
	public function createSamplePDF(Request $request){
		$pdf = new Fpdf();
		$pdf->AddPage();
		$pdf->SetFont('Arial', 'B', 16);
		$pdf->Cell(40,10, "Hello World");

		return new Response($pdf->Output(), 200,
			array(
				'Content-Type'=>'application/pdf'
			));
	}

	/**
	 * @Route("/createDossard/{id}", name="createDossard", requirements={"id" = "\d+"})
	 */
	public function createDossard(Dancer $dancer){
		$pdf = $this->createDossardPDF($dancer->getId(),
			$dancer->getNameDancer(),
			'ClubDanseMulhouse',
			'Solo HipHop Junior');

		return new Response(
			$pdf->Output(),
			200,
			array(
				'Content-Type'=>'application/pdf'
			));
	}

	public function createDossardPDF($id, $nom, $club, $categorie){
		$logo = $this->get('kernel')->getProjectDir() . '/public/Images/logo_fdo.jpg';
		$pdf = new Fpdf('P','mm', 'A4');
		$pdf->SetMargins(8,8, 8);
		$pdf->SetAutoPageBreak(false, 8);
		$pdf->SetTitle("Dossard ".$id, false);
		$pdf->AddPage();
		$pdf->SetFont('Times', 'B', 240);

		$height=$pdf->GetPageHeight()/2-16;
		//width = 0 ; veut dire on prend toute la page
		//height = 202 ; largeur d'une page A4 = 210 mm donc -8
		$pdf->Cell(0, $height, $id, 1, 0 , 'C');
		$pdf->Image($logo,10,12,40);
		$pdf->SetFont('Arial','', 20);
		$pdf->Text(10, 148-10 , utf8_decode($nom));
		$pdf->Text(210-10-$pdf->GetStringWidth(utf8_decode($club)),16, utf8_decode($club));
		//Partie catégorie
		$pdf->SetFont('Times', 'B', 48);
		$pdf->Text(($pdf->GetPageWidth()/2)-($pdf->GetStringWidth(utf8_decode($categorie)))/2, $height-10, utf8_decode($categorie));
		return $pdf;
	}
}
