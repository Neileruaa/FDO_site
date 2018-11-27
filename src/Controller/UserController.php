<?php

namespace App\Controller;


use App\Entity\Club;
use App\Form\ClubType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController {
	/**
	 * @Route("/user/show", name="User.show")
	 */
	public function showUser() {
		$user = $this->getUser();
		return $this->render('user/showUser.html.twig', ['users' => $user,]);
	}

	/**
	 * @Route("/user/delete/{id}", name="User.delete")
	 * @param Request $request
	 * @param ObjectManager $manager
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteUser(Request $request, ObjectManager $manager, $id) {
		$currentUserId= $this->getUser()->getId();
		if ($currentUserId == $id){
			$session = new Session();
			$session->invalidate();
		}
		$user = $manager->getRepository(Club::class)->find($id);
		$manager->remove($user);
		$manager->flush();
		return $this->redirectToRoute('Security.login');
	}

	/**
	 * @Route("/user/edit", name="User.edit")
	 * @param Request $request
	 * @param ObjectManager $manager
	 * @param UserPasswordEncoderInterface $encoder
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
		$club = $this->getUser();
		$form = $this->createForm(ClubType::class, $club);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$club = $form->getData();
			$hash = $encoder->encodePassword($club, $club->getPassword());
			$club->setPassword($hash);
			$manager->persist($club);
			$manager->flush();

			return $this->redirectToRoute('User.show');
		}
		return $this->render('user/editUser.html.twig', ['form' => $form->createView()]);
	}
}