<?php

namespace App\DataFixtures;

use App\Entity\Dance;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DancesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->addDances($manager);
    }
    public function addDances(ObjectManager $manager){
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
            $manager->flush();
        }
    }
}
