<?php

namespace App\Service;

use App\Entity\User;

class UserSortingService
{
    public function sortUsers(array $users): array
    {
        // Define allowed roles
        $allowedRoles = ['ROLE_PLANTILLA', 'ROLE_COS'];

        // Filter users to keep only those with at least one of the allowed roles
        $filteredUsers = array_filter($users, function (User $user) use ($allowedRoles) {
            return !empty(array_intersect($user->getRoles(), $allowedRoles));
        });

        // Sort the filtered users
        usort($filteredUsers, function (User $a, User $b) {
            // Sort by status: Active first, then Inactive
            $statusOrder = ['Active' => 1, 'Inactive' => 2];
            $statusA = $statusOrder[$a->getStatus()] ?? 3;
            $statusB = $statusOrder[$b->getStatus()] ?? 3;

            if ($statusA !== $statusB) {
                return $statusA <=> $statusB;
            }

            // Sort by roles: ROLE_PLANTILLA first, then ROLE_COS
            $rolePriority = [
                'ROLE_PLANTILLA' => 1,
                'ROLE_COS' => 2,
            ];

            $roleA = min(array_map(fn($role) => $rolePriority[$role] ?? 99, $a->getRoles()));
            $roleB = min(array_map(fn($role) => $rolePriority[$role] ?? 99, $b->getRoles()));

            if ($roleA !== $roleB) {
                return $roleA <=> $roleB;
            }

            // Sort alphabetically by name
            return strcmp($a->getUserInfo()->getName(), $b->getUserInfo()->getName());
        });

        return $filteredUsers;
    }
}

// namespace App\Service;

// use App\Entity\User;

// class UserSortingService
// {
//     public function sortUsers(array $users): array
//     {
//         // Define allowed roles
//         $allowedRoles = ['ROLE_PLANTILLA', 'ROLE_COS'];

//         // Filter users to keep only those with at least one of the allowed roles
//         $filteredUsers = array_filter($users, function (User $user) use ($allowedRoles) {
//             return !empty(array_intersect($user->getRoles(), $allowedRoles));
//         });

//         // Sort the filtered users
//         usort($filteredUsers, function (User $a, User $b) {
//             // Sort by status: Active first, then Inactive
//             $statusOrder = ['Active' => 1, 'Inactive' => 2];
//             $statusA = $statusOrder[$a->getStatus()] ?? 3;
//             $statusB = $statusOrder[$b->getStatus()] ?? 3;

//             if ($statusA !== $statusB) {
//                 return $statusA <=> $statusB;
//             }

//             // Sort by roles: ROLE_PLANTILLA first, then ROLE_COS
//             $rolePriority = [
//                 'ROLE_PLANTILLA' => 1,
//                 'ROLE_COS' => 2,
//             ];

//             $roleA = min(array_map(fn($role) => $rolePriority[$role] ?? 99, $a->getRoles()));
//             $roleB = min(array_map(fn($role) => $rolePriority[$role] ?? 99, $b->getRoles()));

//             return $roleA <=> $roleB;
//         });

//         return $filteredUsers;
//     }
// }

