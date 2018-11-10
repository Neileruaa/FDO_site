<?php

namespace App\Controller;


use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ProfileController extends AbstractController
{


    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }


    /**
     * @Route("/profile/show", name="profile.show")
     */
    public function showProfile()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('profile/show.html.twig', ['users' => $user,]);
    }

    /**
     * @Route("/profile/delete", name="profile.delete")
     */
    public function deleteProfile(Request $request,EntityManagerInterface $em)
    {
        $a= $request->request->get('delete');
if ( $a !=null) {

    $profilSupp= $this->get('security.token_storage')->getToken()->getUser();
    $em->remove($profilSupp);
    $em->flush();

    return $this->redirectToRoute('home');


} elseif ( $request->request->get('back')){
    return $this->redirectToRoute('profile.show');

}

    return $this->render('profile/delete.html.twig');



}
    /**
     * @Route("/profile/edit", name="profile.edit")
     */

    public function editProfile(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder )
    {

        $club= $this->getUser();

        $form=$this->createForm(RegistrationType::class,$club);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $club = $form->getData();


            $hash=$encoder->encodePassword($club,$club->getPassword());
            $club->setPassword($hash);


            $manager->persist($club);
            $manager->flush();

            return $this->redirectToRoute('profile.show');
        }



        return  $this->render('profile/edit.html.twig', ['form'=> $form->createView()]);
    }


}
