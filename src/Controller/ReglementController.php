<?php

namespace App\Controller;

use App\Entity\Reglement;
use App\Form\ReglementType;
use App\Repository\ReglementRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/reglement")
 */
class ReglementController extends AbstractController
{
    /**
     * @Route("/", name="Reglements.index", methods="GET")
     * @param ReglementRepository $reglementRepository
     * @return Response
     */
    public function index(ReglementRepository $reglementRepository): Response{
        return $this->render('reglement/index.html.twig', [
            'reglements' => $reglementRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="Reglement.new", methods="GET|POST")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request, FileUploader $fileUploader){
        $reglement = new Reglement();
        $form = $this->createForm(ReglementType::class, $reglement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var UploadedFile $file */
            $file = $form->get('pdfFile')->getData();
            $fileName = $fileUploader->upload($file);
            $reglement->setPdfFile($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($reglement);
            $em->flush();

            return $this->redirectToRoute('Reglements.index');
        }

        return $this->render('reglement/new.html.twig', [
            'reglement' => $reglement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * @Route("/{id}", name="Reglement.delete", methods="DELETE")
     */
    public function delete(Request $request, Reglement $reglement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reglement->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reglement);
            $em->flush();
        }

        return $this->redirectToRoute('Reglements.index');
    }
}
