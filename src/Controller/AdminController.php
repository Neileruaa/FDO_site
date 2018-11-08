<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\Mailbox;
use App\Form\CompetitionType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }



    /**
     * @Route("/admin/validDossard", name="admin.showDossard")
     */
    public function showDossard(ObjectManager $manager)
    {
        $em= $this->container->get('doctrine')->getManager();
        $repository = $em->getRepository(Mailbox::class);

        $mail = $repository->findAll();


        return $this->render('admin/validDossard.html.twig', ['mail'=> $mail]);
    }

    /**
     * @Route("/admin/valid", name="admin.validDossard")
     */
    public function validDossard(ObjectManager $manager)
    {
      return  $this->redirectToRoute('/');
    }

    /**
     * @Route("/admin/createCompetition", name="admin.createCompetition")
     */
    public function createCompetition(ObjectManager $manager)
    {

    $competition =new Competition();
    $form=$this->createForm(CompetitionType::class,$competition);


        return $this->render('admin/createCompetition.html.twig', ['form'=>$form->createView()]);
    }





}
