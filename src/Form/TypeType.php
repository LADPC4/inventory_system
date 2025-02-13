<?php

namespace App\Form;

use App\Entity\specification;
use App\Entity\Type;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            // ->add('specification', EntityType::class, [
            //     'class' => specification::class,
            //     'choice_label' => 'name',
            //     'attr' => ['class' => 'form-control'],
            // ])
            ->add('specification', EntityType::class, [
                'class' => Specification::class,  // Correct class name
                'choice_label' => 'name', // Correctly displays Specification names
                'placeholder' => 'Choose Specification',
                'multiple' => false, // Allow only one selection
                'expanded' => false, // Render as dropdown
                'required' => true,
                'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
            // ->add('modifiedBy', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success shadow-sm'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Type::class,
        ]);
    }
}
