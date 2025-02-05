<?php
// src/Service/UserEditService.php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserEditService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function edit(User $user, $form): User
    {
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
