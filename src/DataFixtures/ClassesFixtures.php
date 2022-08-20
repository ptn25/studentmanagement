<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

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
