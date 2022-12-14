<?php

namespace App\Form;

use App\Entity\Classes;
use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
            [
                'label' => 'Student Name',
                'attr' => [
                    'minlength' => 3,
                    'maxlength' => 30
                ]
            ])
            ->add('dob', DateType::class,
            [
                'label' => 'Student D.O.B',
                'widget' => 'single_text'
            ])
            ->add('sex', TextType::class,
            [
                'label' => 'Sex',
                'attr' => [
                    'minlength' => 3,
                    'maxlength' => 30
                ]
            ])
            ->add('address', TextType::class,
            [
                'label' => 'Student Address',
                'attr' => [
                    'minlength' => 3,
                    'maxlength' => 30
                ]
            ])
            ->add('image', TextType::class,
            [
                'label' => 'Student Image',
                'attr' => [
                    'maxlength' => 255
                ]
            ])
            ->add('classId', EntityType::class,
            [
                'label' => 'Class',
                'required' => true,
                'class' => Classes::class,
                'choice_label' => 'name',
                'multiple' => false,    //neu chi duoc chon 1 option (relationship: 1)
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
