<?php

namespace App\Form;

use App\Entity\User;
use App\Form\UserInfoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['data'];
        $roleChoices = [
            // 'Admin' => 'ROLE_ADMIN',
            'Plantilla' => 'ROLE_PLANTILLA',
            'COS' => 'ROLE_COS',
        ];

        // Check if the user has ROLE_ADMIN
        $isAdmin = ($user && in_array('ROLE_ADMIN', $user->getRoles(), true));
        if ($isAdmin) {
            // If the user is Admin, retain only the Admin role and make the field read-only
            $roleChoices = [
                'Admin' => 'ROLE_ADMIN',
            ];
        }
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password'
            ])
            // ->add('roles', TextType::class, [
            //     'label' => 'Role',
            //     'mapped' => false, // Will be processed manually in controller
            // ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                ],
                'multiple' => false, // Allow only one selection
                'expanded' => false, // Render as dropdown (not radio buttons)
                'required' => true,
                'data' => $options['data']->getStatus()[0] ?? null, // Set default value
                'mapped' => false, // Prevent direct mapping
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
