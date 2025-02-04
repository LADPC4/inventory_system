<?php
// src/Service/UserEditService.php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserEditService
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function edit(User $user, $form): User
    {
        $password = $form->get('password')->getData();
        if ($password) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
        }

        $roles = explode(',', $form->get('roles')->getData());
        $user->setRoles(array_map('trim', $roles));

        $status = $form->get('status')->getData();
        if ($status !== null) {
            $user->setStatus($status);
        }

        $userInfo = $user->getUserInfo();
        if ($userInfo) {
            $userInfo->setUser($user);
        }

        $userInfo->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->flush();

        return $user;
    }
}
