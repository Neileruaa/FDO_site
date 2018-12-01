<?php
/**
 * Created by PhpStorm.
 * User: aurelien
 * Date: 13/10/18
 * Time: 11:58
 */

namespace App\Controller;


use App\Entity\Dance;
use App\Entity\Dancer;
use App\Entity\Team;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\DancerType;
use phpDocumentor\Reflection\Types\This;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
 * Class DancerController
 * @package App\Controller
 */
class DancerController extends AbstractController {
	/**
	 * @Route("/dancer/remove/{id}", name="Dancer.remove", requirements={"page"="\d+"})
	 * @param Dancer $dancer
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function removeDancer(Dancer $dancer, Request $request) {
		$em=$this->getDoctrine()->getManager();
		$list_teams = $dancer->getTeams();
		foreach ($list_teams as $team){
			$em->remove($team);
			$em->flush();
		}
		$em->remove($dancer);
		$em->flush();
		if ($request->headers->all()['referer'][0] == $this->generateUrl("Dancer.create", array(),UrlGeneratorInterface::ABSOLUTE_URL)){
			return $this->redirectToRoute('Dancer.create');
		}else{
			return $this->redirectToRoute('Dancer.showAllDancers');
		}
	}

    /**
     * @Route("/dancer/create", name="Dancer.create")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function createDancer(Request $request) {
		$club = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$dancer = new Dancer();
		$form= $this->createForm(DancerType::class, $dancer);
        $list_dancers=$em->getRepository(Dancer::class)->findAll();
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values
			// but, the original `$task` variable has also been updated
			$dancerToSave = $form->getData();

			$dancerToSave->setClub($club);
			$club->addDancer($dancerToSave);
			$dancerToSave->setIsAuthorized(false);
			$em->persist($dancerToSave);
			$em->flush();
			return $this->redirectToRoute('Dancer.create');
		}elseif ($form->isSubmitted() && !$form->isValid()){
			$this->addFlash('danger', 'Il y a eut une erreur lors de l\'inscription de ce danseur ! ');
		}

		return $this->render(
			'dancer/createDancer.html.twig',
			array(
                'listDancer'=>$list_dancers,
                'club'=>$club,
                'formDanseur'=>$form->createView()
			)
		);
	}

    /**
     * @Route("/dancer/edit/{id}", name="Dancer.edit", requirements={"page"="\d+"})
     * @param Dancer $dancer
     * @isGranted("ROLE_ADMIN")
     */
	public function editDancer(Dancer $dancer, Request $request){
        $dancers = $this->getDoctrine()->getRepository(Dancer::class)->findBy([],['isAuthorized'=>'ASC']);
	    $em=$this->getDoctrine()->getManager();
        $form=$this->createForm(DancerType::class, $dancer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dancer=$form->getData();
            $em->persist($dancer);
            $em->flush();
            return $this->redirectToRoute('Dancer.showAllDancers');
        }elseif ($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('danger', 'Il y a eut une erreur lors de la modification de ce danseur ! ');
        }

        return $this->render('dancer/editDancer.html.twig', ["form"=>$form->createView()]);
    }

	/**
	 * @Route("/dancer/show/all", name="Dancer.showAllDancers")
	 * @isGranted("ROLE_ADMIN")
	 */
	public function showAllDancers(PaginatorInterface $paginator, Request $request) {
        $dancers=$paginator->paginate($this->getDoctrine()->getRepository(Dancer::class)->findBy([],['isAuthorized'=>'ASC']),
            $request->query->getInt('page', 1),10
        );

        $formulaire = $this->createFormBuilder()
            ->add('search', SearchType::class, array('constraints' => new Length(array('min' => 3)), 'attr' => array('placeholder' => 'Rechercher un danseur par nom de famille'), 'required'=>false, 'label'=>"Rechercher" ))
            ->add('send', SubmitType::class, array('label' => 'Rechercher'))
            ->getForm();

        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid())
        {
            $search = $formulaire['search']->getData();
            if ($search!=null){
                $dancers=$paginator->paginate($this->getDoctrine()->getRepository(Dancer::class)->searchDancer($search),
                    $request->query->getInt('page', 1),10
                );
            }
        }
		return $this->render('dancer/showAll.html.twig',[
			'dancers' => $dancers,
            'form'=>$formulaire->createView()
		]);
	}

	/**
	 * @Route("/dancer/authorize/{id}", name="Dancer.authorize")
	 * @isGranted("ROLE_ADMIN")
	 */
	public function authorizeDancer(Dancer $dancer, ObjectManager $manager) {
		$dancer->setIsAuthorized(true);
		$manager->persist($dancer);
		$manager->flush();
		return $this->redirectToRoute('Dancer.showAllDancers');
	}
}