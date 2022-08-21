<?php

namespace App\DataFixtures;

use App\Entity\Classes;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ClassesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i<=5; $i++) {
            $classes = new Classes;
            $classes->setName("Class $i");
            $manager->persist($classes);
        }

        $manager->flush();
    }
}
