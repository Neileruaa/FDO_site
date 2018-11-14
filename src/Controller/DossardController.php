<?php

namespace App\Controller;

use App\Entity\Team;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DossardController extends AbstractController {
	/**
	 * @Route("/admin/validDossard", name="admin.showDossard")
	 */
	public function showDossard(ObjectManager $manager) {
		$em = $this->container->get('doctrine')->getManager();
		$repository = $em->getRepository(Mailbox::class);
		$mail = $repository->findAll();
		return $this->render('admin/validDossard.html.twig', ['mail' => $mail]);
	}

	/**
	 * @Route("/dossard/show", name="Dossard.show")
	 * @param Request $request
	 * @return Response
	 */
	public function page2(Request $request) {
		$user= $this->getUser();
		return $this->render(
			'dossard/pdfTeam.html.twig',[
				"user"=>$user
			]
		);
	}

	/**
	 * @Route("/dossard/create/{id}/{dance}", name="Dossard.create", requirements={"id" = "\d+"})
	 * @param Team $team
	 * @return Response
	 */
	public function createDossard(Team $team, $dance){
		$pdf = $this->createDossardPDF($team->getId(),
			$team->getDancers(),
			//TODO:Faire un champs nom clubs dans l'entité
			$team->getClub()->getUsername(),
			//TODO: Afficher la bonne categorie
			$dance);

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
		$namesToPrint="";
		foreach ($nom as $dancer){
			$namesToPrint.= $dancer->getNameDancer()." ";
		}
		$pdf->Text(10, 148-10 , utf8_decode($namesToPrint));
		$pdf->Text(210-10-$pdf->GetStringWidth(utf8_decode($club)),16, utf8_decode($club));
		//Partie catégorie
		$pdf->SetFont('Times', 'B', 38);
		$pdf->Text(($pdf->GetPageWidth()/2)-($pdf->GetStringWidth(utf8_decode($categorie)))/2, $height-10, utf8_decode($categorie));
		return $pdf;
	}

	/**
	 * @Route("/dossard/choose/{id}", name="Dossard.choose", requirements={"id" = "\d+"})
	 * @param Team $team
	 * @return Response
	 */
	public function chooseDossard(Team $team) {
		return $this->render('dossard/chooseDossard.html.twig',[
			'team'=>$team
		]);
	}
}
