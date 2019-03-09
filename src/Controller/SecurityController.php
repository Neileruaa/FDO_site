<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use ReCaptcha\ReCaptcha;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\RegistrationType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller {
    /**
     * @Route("/register", name="Security.registration")
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
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

    /**
     * @Route("/forgottenPassword", name="Security.forgottenPassword")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param \Swift_Mailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @param ClubRepository $clubRepository
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forgottenPassword(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator,
        ClubRepository $clubRepository,
        ObjectManager $manager
    ) {
        if($request->isMethod('POST')){
            $email = $request->request->get('email');

            $club = $clubRepository->findOneBy(['emailClub' => $email]);
            /* @var $club Club */

            if ($club === null){
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('Home.index');
            }
            $token = $tokenGenerator->generateToken();
            try{
                $club->setResetPasswordToken($token);
                $manager->flush();
            } catch (\Exception $e){
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('Home.index');
            }

            $url = $this->generateUrl('Security.ResetPassword', ['token'=>$token], UrlGeneratorInterface::ABSOLUTE_URL);

            			$message = (new \Swift_Message('Mot de passe oublié'))
				->setFrom('contact@fdo-online.net')
				//TODO: mettre le mail de l'admin
				->setTo($club->getEmailClub())
				->setBody(
					$this->renderView(
						'emails/forgotten_password.html.twig',[
						'url'=>$url
						]
					),
					'text/html'
				);
			$mailer->send($message);
                        
            $this->addFlash('success', 'Mail envoyé');

            return $this->redirectToRoute('Home.index');
        }
        return $this->render('security/forgottenPassword.html.twig');
	}

    /**
     * @Route("/resetPassword/{token}", name="Security.ResetPassword")
     * @param Request $request
     * @param string $token
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ObjectManager $manager
     * @param ClubRepository $clubRepository
     */
    public function resetPassword(
        Request $request,
        string $token,
        UserPasswordEncoderInterface $passwordEncoder,
        ObjectManager $manager,
        ClubRepository $clubRepository
    ) {
        if ($request->isMethod('POST')){
            $club = $clubRepository->findOneBy(['resetPasswordToken' => $token]);
            /* @var $club Club */

            if ($club === null){
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('Home.index');
            }

            $club->setResetPasswordToken(null);
            $club->setPassword($passwordEncoder->encodePassword($club, $request->request->get('password')));
            $manager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');
            return $this->redirectToRoute('Home.index');
        }else{
            return $this->render('security/resetPassword.html.twig', ['token'=>$token]);
        }
	}
}
