<?php

namespace App\Controller;

use App\Entity\Danseur;
use App\Entity\Equipe;
use Fpdf\Fpdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller {

	public $danseurs=array(
		array("id"=>0, "nom"=>"Aurélien", "club"=>"DanseMulhouse", "date_naissance"=>"03/01/1999", 'categorie'=>'solo valse Junior'),
		array("id"=>1, "nom"=>"Antoine", "club"=>"DanseAlsace", "date_naissance"=>"04/01/1999", 'categorie'=>'solo rock senior'),
		array("id"=>2, "nom"=>"Gauvain", "club"=>"DanseBretagne", "date_naissance"=>"05/01/1999", 'categorie'=>'solo hiphop enfant'),
		array("id"=>3, "nom"=>"Mattéo", "club"=>"DanseBelfort", "date_naissance"=>"06/01/1999", 'categorie'=>'solo salsa enfant'),
		array("id"=>4, "nom"=>"Olivier", "club"=>"DanseLuxeuil", "date_naissance"=>"07/01/1999", 'categorie'=>'solo disco Junior'),
		array("id"=>5, "nom"=>"Raphael", "club"=>"DanseVesoul", "date_naissance"=>"08/01/1999", 'categorie'=>'solo dance show enfant')
	);

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
		return $this->render(
			'home/page2.html.twig',
			array('danseurs'=>$this->danseurs)
		);
	}

	/**
	 * @Route("/page3/addFakeTeamAndDancers", name="addFakeTeamAndDancers")
	 */
	public function addFakeTeamAndDancers() {
		$equipe1 = new Equipe();
		$equipe1->setCategorie("Junior")
			->setNumeroDossard("10");
		$equipe2 = new Equipe();
		$equipe2->setCategorie("Enfant")
			->setNumeroDossard("1");

		$danseur1 = new Danseur();
		$danseur1->setName("Aurélien")
			->setAge(19);
		$danseur2 = new Danseur();
		$danseur2->setName("Antoine")
			->setAge(19);
		$danseur3 = new Danseur();
		$danseur3->setName("Raph")
			->setAge(19);
		$danseur4 = new Danseur();
		$danseur4->setName("Olivier")
			->setAge(19);
		$danseur5 = new Danseur();
		$danseur5->setName("Matteo")
			->setAge(19);
		$danseur6 = new Danseur();
		$danseur6->setName("Gauvain")
			->setAge(19);

		$equipe1->addDanseur($danseur1);

		$em = $this->getDoctrine()->getManager();
		$em->persist($equipe1);
		$em->persist($equipe2);
		$em->persist($danseur1);
		$em->persist($danseur2);
		$em->persist($danseur3);
		$em->persist($danseur4);
		$em->persist($danseur5);
		$em->persist($danseur6);
		$em->flush();

		return $this->redirectToRoute('page3');

	}

	/**
	 * @Route("/page3/removeFakeTeamAndDancers", name="removeFakeTeamAndDancers")
	 */
	public function removeFakeTeamAndDancers() {
		$em = $this->getDoctrine()->getManager();
		$teams= $em->getRepository('App:Equipe')->findAll();
		if ($teams){
			foreach ($teams as $teammate){
				$em->remove($teammate);
			}
		}

		$danceurs= $em->getRepository('App:Danseur')->findAll();
		if ($danceurs){
			foreach ($danceurs as $danceur){
				$em->remove($danceur);
			}
		}
		$em->flush();

		return $this->redirectToRoute('page3');
	}
	/**
	 * @Route("/page3", name="page3")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function page3(Request $request) {
		return $this->render(
			'home/page3.html.twig',
			array()
		);
	}

	/**
	 * @Route("/page4", name="page4")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function page4(Request $request) {
		return $this->render(
			'home/page4.html.twig',
			array()
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
		//Traitement sur la bdd pour avoir les bonnes informations
			foreach ($this->danseurs as $danseur){
				if ($danseur['id']==$id){
					$id_danseur = $id;
					$nom_danseur = $danseur['nom'];
					$club_danseur = $danseur['club'];
					$dateNaissance_danseur = $danseur['date_naissance'];
					$categorie_danseur = $danseur['categorie'];
				}
			}
		//Traitement sur la bdd pour avoir les bonnes informations

		$pdf = $this->createDossardPDF($id_danseur, $nom_danseur, $club_danseur, $categorie_danseur);

		return new Response(
			$pdf->Output(),
			200,
			array(
				'Content-Type'=>'application/pdf'
			));
	}

	public function createDossardPDF($id, $nom, $club, $categorie){
		$logo = $this->get('kernel')->getProjectDir() . '/public/Images/logo_fdo.jpg';
		$pdf = new Fpdf('L','mm', 'A4');
		$pdf->SetMargins(8,8, 8);
		$pdf->SetAutoPageBreak(false, 8);
		$pdf->SetTitle("Dossard ".$id, false);
		$pdf->AddPage();
		$pdf->SetFont('Times', 'BI', 320);
//		$pdf->Cell(80);

		$height=$pdf->GetPageHeight()-16;
		//width = 0 ; veut dire on prend toute la page
		//height = 202 ; largeur d'une page A4 = 210 mm donc -8
		$pdf->Cell(0, $height, $id, 1, 0 , 'C');
		$pdf->Image($logo,10,12,40);
		$pdf->SetFont('Arial', '', 20);
		$pdf->Text(10, 199, utf8_decode($nom));
		$pdf->Text(297-10-$pdf->GetStringWidth(utf8_decode($club)),199, utf8_decode($club));
		$pdf->SetFont('Times', 'B', 48);
		$pdf->Text(297-10-$pdf->GetStringWidth(utf8_decode($categorie)), 22, utf8_decode($categorie));
		return $pdf;
	}
}
