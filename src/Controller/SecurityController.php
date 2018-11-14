<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\RegistrationType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller {
	/**
	 * @Route("/register", name="Security.registration")
	 * @param Request $request
	 * @param ObjectManager $manager
	 * @param UserPasswordEncoderInterface $encoder
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
		$club = new Club();
		$form = $this->createForm(ClubType::class, $club);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$hash = $encoder->encodePassword($club, $club->getPassword());
			$club->setPassword($hash);
			$club->setRoles('ROLE_USER');
			$manager->persist($club);
			$manager->flush();
			return $this->redirectToRoute('Security.login');
		}
		return $this->render('security/registration.html.twig', ['form' => $form->createView()]);
	}

	/**
	 * @Route("/login", name="Security.login")
	 * @param AuthenticationUtils $authenticationUtils
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function login(AuthenticationUtils $authenticationUtils) {
		$lastUsername = $authenticationUtils->getLastUsername();
		$errors = $authenticationUtils->getLastAuthenticationError();
		return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'errors' => $errors]);
	}

	/**
	 * @Route("/logout", name="Security.logout")
	 */
	public function logout() {}
}
