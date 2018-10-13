<?php

namespace App\Controller;

use App\Entity\Danseur;
use App\Entity\Equipe;
use App\Form\DanseurType;
use App\Form\EquipeType;
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
		$list_danseurs = $em->getRepository(Danseur::class)
			->findAll();
		return $this->render(
			'home/page2.html.twig',
			array('danseurs'=>$list_danseurs)
		);
	}

	/**
	 * @Route("/page3", name="page3")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function page3(Request $request) {
		$equipe = new Equipe();
		$form = $this->createForm(EquipeType::class, $equipe);

		return $this->render(
			'home/page3.html.twig',
			array('formEquipe'=>$form->createView())
		);
	}

	/**
	 * @Route("/page4", name="page4")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function page4(Request $request) {
		 $em = $this->getDoctrine()->getManager();
		$danseur = new Danseur();
		$form= $this->createForm(DanseurType::class, $danseur);
		$list_danseur = $em->getRepository(Danseur::class)->findAll();
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values
			// but, the original `$task` variable has also been updated
			$danseurToSave = $form->getData();
			 $em->persist($danseurToSave);
			 $em->flush();

			return $this->redirectToRoute('page4');
		}
		return $this->render(
			'home/page4.html.twig',
			array(
				'formDanseur'=>$form->createView(),
				'listDanseur'=>$list_danseur
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
	public function createDossard(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$danseur=$em->getRepository(Danseur::class)
			->findOneBy(['id'=>$id]);
		$pdf = $this->createDossardPDF($danseur->getId(),
			$danseur->getName(),
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
