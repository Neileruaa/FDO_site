<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 16/10/18
 * Time: 11:23
 */

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Dance;
use App\Entity\Dancer;
use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

//TODO: faire un fichier de fixture pour remplacer ce controller
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
        $team->addCategory($category);
        $em->persist($team);
        $em->persist($category);
        $em->persist($category2);
        $em->persist($category3);

        $dances=[
            ["nameDance"=>"disco"],
            ["nameDance"=>"hip hop"],
            ["nameDance"=>"popping"],
            ["nameDance"=>"break dance"],
            ["nameDance"=>"dance show"],
            ["nameDance"=>"salsa"],
            ["nameDance"=>"show caraibe"],
            ["nameDance"=>"swing"],
            ["nameDance"=>"tango argentino"],
            ["nameDance"=>"claquettes"]
        ];
        foreach ($dances as $dance){
            $new_dance=new Dance();
            $new_dance->setNameDance($dance["nameDance"]);
            $em->persist($new_dance);
        }

        $em->flush();
        return $this->redirectToRoute("home");
    }
}