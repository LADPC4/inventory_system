<?php
// src/Service/UserRegistrationService.php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\UserRegistrationType;

class UserRegistrationService
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function register(User $user, $form): User
    {
        // Handle password hashing
        $hashedPassword = $this->passwordHasher->hashPassword($user, $form->get('password')->getData());
        $user->setPassword($hashedPassword);

        // Handle roles
        $roles = explode(',', $form->get('roles')->getData());
        $user->setRoles(array_map('trim', $roles));

        // Handle status
        $status = $form->get('status')->getData();
        if ($status !== null) {
            $user->setStatus($status);
        }

        // Handle userInfo
        $userInfo = $user->getUserInfo();
        $userInfo->setUser($user);
        $userInfo->setCreatedAt(new \DateTimeImmutable());

        // Persist the user
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
