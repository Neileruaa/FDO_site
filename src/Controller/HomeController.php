<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Dancer;
use App\Entity\Danseur;
use App\Entity\Team;
use App\Form\DancerType;
use App\Form\DanseurType;
use App\Form\TeamType;
use Fpdf\Fpdf;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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

}
