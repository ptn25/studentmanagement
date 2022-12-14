<?php

namespace App\Form;

use App\Entity\Classes;
use App\Entity\Lecturer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LecturerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
            [
                'label' => 'Lecturer name',
                'attr' => [
                    'minlength' => 3,
                    'maxlength' => 30
                ]
            ])
            ->add('dob', DateType::class,
            [
                'label' => 'D.O.B',
                'widget' => 'single_text'
            ])
            ->add('image', TextType::class,
            [
                'label' => 'Lecturer Image',
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
                'multiple' => true,     //neu co the chon nhieu options (relationship: many)
                'expanded' => true

            ])
            // ->add('subjId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lecturer::class,
        ]);
    }
}
