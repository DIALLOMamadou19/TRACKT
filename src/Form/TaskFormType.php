<?php

namespace App\Form;

use App\Entity\Tache;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_tache', TextType::class, [
                'label' => 'Task Name',
            ])
            ->add('description_tache', TextareaType::class, [
                'label' => 'Task Description',
                'required' => false,
            ])
            ->add('date_debut', DateType::class, [
                'label' => 'Start Date',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('date_echeance', DateType::class, [
                'label' => 'Due Date',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Assign Users',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
        ]);
    }
}