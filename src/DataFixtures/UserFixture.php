<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserInfo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create a user
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                'admin123'
            )
        );
        $user->setStatus('Active');
        $user->setRoles(['ROLE_ADMIN']);

        // Create user info
        $userInfo = new UserInfo();
        $userInfo->setUser($user);
        $userInfo->setFn('Admin');
        $userInfo->setMn('');
        $userInfo->setLn('User');
        $userInfo->setOffice('Information and Communications Technology Service');
        $userInfo->setDivision('Solutions Development Division');
        $userInfo->setPosition('System Administrator');
        $userInfo->setPhone('123-456-7890');
        $userInfo->setAddress('123 Admin Street, City, Country');
        $userInfo->setCreatedAt(new \DateTimeImmutable());

        $manager->persist($user);
        $manager->persist($userInfo);
        $manager->flush();
    }
}

// namespace App\DataFixtures;

// use Doctrine\Bundle\FixturesBundle\Fixture;
// use Doctrine\Persistence\ObjectManager;

// class UserFixture extends Fixture
// {
//     public function load(ObjectManager $manager): void
//     {
//         // $product = new Product();
//         // $manager->persist($product);

//         $manager->flush();
//     }
// }
