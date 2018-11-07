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
     * @Route("/register", name="security.registration")
     */
    public function registration(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder )
    {
        $club=new Club();
        $form=$this->createForm(RegistrationType::class,$club);


        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $hash=$encoder->encodePassword($club,$club->getPassword());
            $club->setPassword($hash);

            $manager->persist($club);
            $manager->flush();

            return $this->redirectToRoute('security.login');
        }

        return $this->render('security/registration.html.twig', ['form'=>$form->createView() ]);
    }


    /**
     * @Route("/login", name="security.login")
     */
    public function login()
    {


        return $this->render('security/login.html.twig');
    }



}
