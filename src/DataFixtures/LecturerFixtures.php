<?php

namespace App\DataFixtures;

use App\Entity\Lecturer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LecturerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=1; $i<=10; $i++){
            $lecturer = new Lecturer;
            $lecturer->setName("Lecturer $i")
                ->setDOB(\DateTime::createFromFormat('Y/m/d', '2003/08/21'))
                ->setImage("https://www.shareicon.net/data/512x512/2016/04/10/747365_man_512x512.png");
            $manager->persist($lecturer);
        }

        $manager->flush();
    }
}
