<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
		return $this->render(
			'home/page2.html.twig',
			array()
		);
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
	public function test(){

	}
}
