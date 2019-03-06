<?php

namespace App\Controller;

use App\Entity\Judge;
use App\Form\JudgeType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class JudgeController
 * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
 * @package App\Controller
 */
class JudgeController extends AbstractController
{
    /**
     * @Route("/judge/show", name="Judge.show")
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, ObjectManager $manager)
    {
        $judges=$this->getDoctrine()->getRepository(Judge::class)->findAll();
        $judge=new Judge();
        $form = $this->createForm(JudgeType::class, $judge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($judge);
            $manager->flush();
            return $this->redirectToRoute('Judge.show');
        }
        return $this->render('judge/show.html.twig', [
            'judges' => $judges,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/judge/delete/{id}", name="Judge.delete", requirements={"page"="\d+"})
     * @param Judge $judge
     * @isGranted("ROLE_ADMIN")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Judge $judge, ObjectManager $manager){
        $manager->remove($judge);
        $manager->flush();
        return $this->redirectToRoute('Judge.show');
    }

    /**
     * @Route("/judge/edit/{id}", name="Judge.edit", requirements={"page"="\d+"})
     * @isGranted("ROLE_ADMIN")
     */
    public function edit(Judge $judge, ObjectManager $manager, Request $request){
        $form=$this->createForm(JudgeType::class, $judge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($judge);
            $manager->flush();
            return $this->redirectToRoute('Judge.show');
        }

        return $this->render('judge/edit.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
