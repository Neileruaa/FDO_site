<?php

namespace App\Controller;

use App\Entity\Mailbox;
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
       $this->redirectToRoute('/');
    }






}
