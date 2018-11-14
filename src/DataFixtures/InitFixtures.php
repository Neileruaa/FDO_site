<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class InitFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->init($manager);
    }
    public function init(ObjectManager $manager){
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
            $manager->persist($new_dance);
        }

        $manager->flush();
    }
}
