<?php

// src/Service/UserStatusService.php
namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface; // Use TokenStorageInterface instead of Security

class UserStatusService
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function checkUserStatus(): string
    {
        $user = $this->tokenStorage->getToken()?->getUser(); // Get the currently authenticated user

        if ($user instanceof User && $user->getStatus() === 'Inactive') {
            return 'app_inactive'; // Return the string if user status is "Inactive"
        }

        return 'active'; // Default return value if the user is not inactive
    }
}

