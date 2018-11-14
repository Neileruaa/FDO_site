<?php

namespace App\Controller;

use App\Entity\Club;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\RegistrationType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/register", name="Security.registration")
     */
    public function registration(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder )
    {
    	//TODO: Refaire la registration
        $club=new Club();
        $form=$this->createForm(RegistrationType::class,$club);

        $mail= $request->request->get('emailClub');

        $em= $this->container->get('doctrine')->getManager();

        $msgErreurEmail = "";

        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $hash=$encoder->encodePassword($club,$club->getPassword());
            $club->setPassword($hash);
            $manager->persist($club);
            $manager->flush();
            return $this->redirectToRoute('Security.login');
        }
        return $this->render('security/registration.html.twig', ['form'=>$form->createView() , 'msg'=> $msgErreurEmail, 'mail'=>$mail]);
    }

    /**
     * @Route("/login", name="Security.login")
     */
    public function login() {
        return $this->render('security/login.html.twig');
    }
}
