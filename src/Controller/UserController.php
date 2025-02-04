<?php
// src/Controller/UserController.php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserRegistrationService;
use App\Service\UserEditService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\UserRegistrationType;

class UserController extends AbstractController
{
    private UserRegistrationService $registrationService;
    private UserEditService $editService;

    public function __construct(UserRegistrationService $registrationService, UserEditService $editService)
    {
        $this->registrationService = $registrationService;
        $this->editService = $editService;
    }

    #[Route('/users', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/register', name: 'app_user_register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->registrationService->register($user, $form);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_user_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createForm(UserRegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->editService->edit($user, $form);

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/users/{id}/delete', name: 'user_delete', methods: ['POST'])]
    public function delete(User $user, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'User deleted successfully.');

        return $this->redirectToRoute('user_index');
    }

    #[Route('/users/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}

// namespace App\Controller;

// use App\Entity\User;
// use App\Repository\UserRepository;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\HttpFoundation\RedirectResponse;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use App\Form\UserRegistrationType;
// use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// class UserController extends AbstractController
// {
//     #[Route('/users', name: 'user_index', methods: ['GET'])]
//     public function index(UserRepository $userRepository): Response
//     {
//         $users = $userRepository->findAll();

//         return $this->render('user/index.html.twig', [
//             'users' => $users,
//         ]);
//     }

//     #[Route('/register', name: 'user_register')]
//     public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
//     {
//         $user = new User();
//         $form = $this->createForm(UserRegistrationType::class, $user);

//         $form->handleRequest($request);
//         if ($form->isSubmitted() && $form->isValid()) {
//             $hashedPassword = $passwordHasher->hashPassword(
//                 $user,
//                 $form->get('password')->getData()
//             );
//             $user->setPassword($hashedPassword);

//             $roles = explode(',', $form->get('roles')->getData());
//             $user->setRoles(array_map('trim', $roles));

//             // Retrieve the status value manually
//             $status = $form->get('status')->getData();
//             if ($status !== null) {
//                 $user->setStatus($status);
//             }

//             $userInfo = $user->getUserInfo();
//             $userInfo->setUser($user);
//             $userInfo->setCreatedAt(new \DateTimeImmutable());

//             $entityManager->persist($user);
//             $entityManager->flush();

//             return $this->redirectToRoute('user_index');
//         }

//         return $this->render('user/register.html.twig', [
//             'form' => $form->createView(),
//         ]);
//     }

//     #[Route('/edit/{id}', name: 'app_user_edit')]
//     public function edit(int $id, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
//     {
//         $user = $entityManager->getRepository(User::class)->find($id);

//         if (!$user) {
//             throw $this->createNotFoundException('User not found');
//         }

//         $form = $this->createForm(UserRegistrationType::class, $user);

//         $form->handleRequest($request);
//         if ($form->isSubmitted() && $form->isValid()) {
//             $password = $form->get('password')->getData();
//             if ($password) {
//                 $hashedPassword = $passwordHasher->hashPassword($user, $password);
//                 $user->setPassword($hashedPassword);
//             }

//             $roles = explode(',', $form->get('roles')->getData());
//             $user->setRoles(array_map('trim', $roles));

//             $status = $form->get('status')->getData();
//             if ($status !== null) {
//                 $user->setStatus($status);
//             }

//             $userInfo = $user->getUserInfo();
//             if ($userInfo) {
//                 $userInfo->setUser($user);
//             }

//             $userInfo->setUpdatedAt(new \DateTimeImmutable());
//             $entityManager->flush();

//             return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
//         }

//         return $this->render('user/edit.html.twig', [
//             'form' => $form->createView(),
//             'user' => $user,
//         ]);
//     }

//     #[Route('/users/{id}/delete', name: 'user_delete', methods: ['POST'])]
//     public function delete(User $user, EntityManagerInterface $em): RedirectResponse
//     {

//         $em->remove($user);
//         $em->flush();

//         $this->addFlash('success', 'User deleted successfully.');

//         return $this->redirectToRoute('user_index');
//     }

//     #[Route('/users/{id}', name: 'user_show', methods: ['GET'])]
//     public function show(User $user): Response
//     {
//         return $this->render('user/show.html.twig', [
//             'user' => $user,
//         ]);
//     }
// }