<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 16/10/18
 * Time: 11:23
 */

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Dancer;
use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class InitController extends Controller
{
    /**
     * @Route("/init", name="bdd.init")
     */
    public function index(){
        $em = $this->getDoctrine()->getManager();
        $category=new Category();
        $category2=new Category();
        $category3=new Category();
        $category->setNameCategory("junior");
        $category2->setNameCategory("enfant");
        $category3->setNameCategory("senior");

        $team=new Team();
        $team->setCategory($category);
        $em->persist($team);
        $em->persist($category);
        $em->persist($category2);
        $em->persist($category3);

        $em->flush();
        return $this->redirectToRoute("home");
    }
}