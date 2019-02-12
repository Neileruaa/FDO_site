<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use ReCaptcha\ReCaptcha;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_ADMIN")
     */
	public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer) {
		$recaptcha = new ReCaptcha('6LfoFXwUAAAAAHwbk-Eq0LHYCtmxY2OlFdnVtpYL');
		$resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
		$errors = array();
		$club = new Club();
		$form = $this->createForm(ClubType::class, $club);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			if ($resp->isSuccess()) {
				$hash = $encoder->encodePassword($club, $club->getPassword());
				$club->setPassword($hash);
				$club->setRoles('ROLE_USER');
				$manager->persist($club);
				$manager->flush();

                $message = (new \Swift_Message('New Mail'))
                    ->setFrom('fdotestemail@gmail.com')
                    ->setTo($club->getEmailClub())
                    ->setBody(
                        $this->renderView(
                            'emails/successful_registration.html.twig',[
                                'name' => $club->getNameClubOwner()
                            ]
                        ),
                        'text/html'
                    );
                $mailer->send($message);

				return $this->redirectToRoute('Club.showAll');
			} else {
				$errors = $resp->getErrorCodes();
			}
		}
		return $this->render('security/registration.html.twig', [
			'form' => $form->createView(),
			'errors' => $errors
			]);
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
