<?php

namespace App\Form;

use App\Entity\UserInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fn', TextType::class, [
                'label' => 'First Name',
                'required' => true,
            ])
            ->add('mn', TextType::class, [
                'label' => 'Middle Name',
                'required' => false,
            ])
            ->add('ln', TextType::class, [
                'label' => 'Last Name',
                'required' => true,
            ])
            ->add('office', TextType::class, [
                'label' => 'Office',
                'required' => true,
            ])
            ->add('division', TextType::class, [
                'label' => 'Division',
                'required' => true,
            ])
            ->add('position', TextType::class, [
                'label' => 'Position',
                'required' => true,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone Number',
                'required' => true,
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserInfo::class, // Ensure correct mapping
        ]);
    }
}


// namespace App\Form;

// use App\Entity\UserInfo;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;

// class UserInfoType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $builder
//             ->add('fn', TextType::class, [
//                 'label' => 'First Name',
//             ])
//             ->add('mn', TextType::class, [
//                 'label' => 'Middle Name',
//                 'required' => false,
//             ])
//             ->add('ln', TextType::class, [
//                 'label' => 'Last Name',
//             ])
//             ->add('office', TextType::class, [
//                 'label' => 'Office',
//             ])
//             ->add('division', TextType::class, [
//                 'label' => 'Division',
//             ])
//             ->add('position', TextType::class, [
//                 'label' => 'Position',
//             ])
//             ->add('status', TextType::class, [
//                 'label' => 'Status',
//             ])
//             ->add('phone', TextType::class, [
//                 'label' => 'Phone Number',
//             ])
//             ->add('address', TextType::class, [
//                 'label' => 'Address',
//             ]);
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'data_class' => UserInfo::class, // Now correctly mapped
//         ]);
//     }
// }

// src/Form/UserInfoType.php

// namespace App\Form;

// use App\Entity\UserInfo;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
// use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

// class UserInfoType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options)
//     {
//         $builder->add('fn', TextType::class, ['label' => 'First Name']);
//         $builder->add('mn', TextType::class, ['label' => 'Middle Name']);
//         $builder->add('ln', TextType::class, ['label' => 'Last Name']);
//         $builder->add('office', TextType::class);
//         $builder->add('division', TextType::class);
//         $builder->add('position', TextType::class);
//         $builder->add('status', ChoiceType::class, [
//             'choices' => [
//                 'Active' => 'Active',
//                 'Inactive' => 'Inactive',
//             ]
//         ]);
//         $builder->add('phone', TextType::class);
//         $builder->add('address', TextType::class);
//         $builder->add('createdAt', DateTimeType::class);
//     }

//     public function configureOptions(OptionsResolver $resolver)
//     {
//         $resolver->setDefaults([
//             'data_class' => UserInfo::class,
//         ]);
//     }
// }

// namespace App\Form;

// use App\Entity\User;
// use App\Entity\UserInfo;
// use Symfony\Bridge\Doctrine\Form\Type\EntityType;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;

// class UserInfoType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $builder
//             ->add('fn')
//             ->add('mn')
//             ->add('ln')
//             ->add('office')
//             ->add('division')
//             ->add('position')
//             ->add('status')
//             ->add('phone')
//             ->add('address')
//             ->add('created_at', null, [
//                 'widget' => 'single_text',
//             ])
//             ->add('updated_at', null, [
//                 'widget' => 'single_text',
//             ])
//             ->add('user', EntityType::class, [
//                 'class' => User::class,
//                 'choice_label' => 'id',
//             ])
//         ;
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'data_class' => UserInfo::class,
//         ]);
//     }
// }
