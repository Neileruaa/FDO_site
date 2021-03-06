<?php

namespace App\Controller;

use App\Entity\Mailbox;
use App\Entity\Team;
use Doctrine\Common\Persistence\ObjectManager;
use Fpdf\Fpdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
 * Class DossardController
 * @package App\Controller
 */
class DossardController extends AbstractController {
	/**
	 * @Route("/admin/validDossard", name="Admin.showDossard")
	 */
	public function showDossard(ObjectManager $manager) {
		$mail = $manager->getRepository(Mailbox::class)->findAll();
		return $this->render('admin/validDossard.html.twig', [
			'mail' => $mail
		]);
	}

	/**
	 * @Route("/dossard/show", name="Dossard.show")
	 * @param Request $request
	 * @return Response
	 */
	public function showDossardUser(Request $request) {
		$user= $this->getUser();
		return $this->render(
			'dossard/pdfTeam.html.twig',[
				"user"=>$user
			]
		);
	}

	/**
	 * @Route("/dossard/create/{id}", name="Dossard.create", requirements={"id" = "\d+"})
	 * @param Team $team
	 * @return Response
	 */
	public function createDossard(Team $team){
		$pdf = $this->createDossardPDF($team->getNumDossard(),
			$team->getDancers(),
			$team->getClub()->getUsername(),
			$team->getCategory());

		return new Response(
			$pdf->Output(),
			200,
			array(
				'Content-Type'=>'application/pdf'
			));
	}

	public function createDossardPDF($id, $nom, $club, $categorie){
//		$logo = $this->get('kernel')->getProjectDir() . '/public/Images/logo_fdo.jpg';
		$logo = $this->getParameter('images_directory')."logo_fdo.jpg";
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
		$pdf->SetFont('Arial','', 14);
		$namesToPrint="";
		foreach ($nom as $dancer){
			$namesToPrint.= $dancer->getNameDancer()." ".$dancer->getFirstNameDancer()[0].".  ";
		}
		$pdf->Text(10, 148-10 , utf8_decode($namesToPrint));
		$pdf->SetFont('Arial','', 20);
		$pdf->Text(210-10-$pdf->GetStringWidth(utf8_decode($club)),16, utf8_decode($club));
		//Partie catégorie
		$pdf->SetFont('Times', 'B', 38);
		$pdf->Text(($pdf->GetPageWidth()/2)-($pdf->GetStringWidth(utf8_decode($categorie)))/2, $height-10, utf8_decode($categorie));
		return $pdf;
	}
}
