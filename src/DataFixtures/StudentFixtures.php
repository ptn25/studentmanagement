<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class StudentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sex = ["Male", "Female", "Others"];
        for($i=1; $i<=10; $i++){
            $key = array_rand($sex, 1);
            $student = new Student;
            $student->setName("Student $i")
                ->setDOB(\DateTime::createFromFormat('Y/m/d', '2003/08/21'))
                ->setSex($sex[$key])
                ->setAddress("Viet Nam")
                ->setImage("https://img.freepik.com/premium-vector/graduate-student-avatar-student-student-icon-flat-design-style-education-graduation-isolated-student-icon-white-background-vector-illustration-web-application-printing_153097-1566.jpg?w=2000");
            $manager->persist($student);
        }

        $manager->flush();
    }
}
