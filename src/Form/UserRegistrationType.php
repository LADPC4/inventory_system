<?php

namespace App\Form;

use App\Entity\User;
use App\Form\UserInfoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['data'];
        $roleChoices = [
            'Plantilla' => 'ROLE_PLANTILLA',
            'COS' => 'ROLE_COS',
        ];
        $statusChoices = [
            'Active' => 'Active',
            'Inactive' => 'Inactive',
        ];

        // Check if the user has ROLE_ADMIN
        $isAdmin = ($user && in_array('ROLE_ADMIN', $user->getRoles(), true));
        if ($isAdmin) {
            // If the user is Admin, retain only the Admin role and make the field read-only
            $roleChoices = [
                'Admin' => 'ROLE_ADMIN',
            ];
        }

        
        if ($options['is_default']) {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => $statusChoices,  // Use the modified choices
                'placeholder' => 'Choose Status',
                'multiple' => false, // Allow only one selection
                'expanded' => false, // Render as dropdown (not radio buttons)
                'required' => true,
                'data' => $options['data']->getStatus()[0] ?? null, // Set default value
                'mapped' => true,
                'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => $roleChoices,  // Use the modified choices
                'placeholder' => 'Choose Role',
                'multiple' => false, // Allow only one selection
                'expanded' => false, // Render as dropdown (not radio buttons)
                'required' => true,
                'data' => $options['data']->getRoles()[0] ?? null, // Set default value
                'mapped' => false, // Prevent direct mapping
                'disabled' => $isAdmin,
                'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('userInfo', UserInfoType::class, [
                'label' => false, // Keep it mapped correctly
                'mapped' => true, // This must be TRUE
                'by_reference' => true,
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success shadow-sm'],
            ]);
        }
        elseif ($options['is_edit']) {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => $statusChoices,  // Use the modified choices
                'placeholder' => 'Choose Status',
                'multiple' => false, // Allow only one selection
                'expanded' => false, // Render as dropdown (not radio buttons)
                'required' => true,
                // 'data' => $options['data']->getStatus()[0] ?? null, // Set default value
                'data' => $options['data']->getStatus() ?? null,
                'mapped' => true,
                'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => $roleChoices,  // Use the modified choices
                'placeholder' => 'Choose Role',
                'multiple' => false, // Allow only one selection
                'expanded' => false, // Render as dropdown (not radio buttons)
                'required' => true,
                'data' => $options['data']->getRoles()[0] ?? null, // Set default value
                'mapped' => false, // Prevent direct mapping
                'disabled' => $isAdmin,
                'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('userInfo', UserInfoType::class, [
                'label' => false, // Keep it mapped correctly
                'mapped' => true, // This must be TRUE
                'by_reference' => true,
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success shadow-sm'],
            ]);
        }

        elseif ($options['is_cpass']) {
            $builder
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, [
                // 'label' => 'Update User',
                'attr' => ['class' => 'btn btn-success shadow-sm'],
            ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_default' => false,
            'is_edit' => false,
            'is_cpass' => false,
        ]);
    }
}
