<?php
// src/Controller/UserController.php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserEditService;
use App\Service\UserPasswordService;
use App\Service\UserStatusService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\UserRegistrationType;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    private UserEditService $editService;
    protected UserStatusService $userStatusService;

    public function __construct(
        UserEditService $editService,
        UserStatusService $userStatusService
    ){
        $this->editService = $editService;
        $this->userStatusService = $userStatusService;
    }

    #[Route('/', name: 'app_profile_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $status = $this->userStatusService->checkUserStatus();

        if ($status === 'app_inactive') {
            return $this->redirectToRoute('app_inactive');
        }
        
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profile_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the currently logged-in user
        $loggeduser = $this->getUser();

        // Fetch the user from the database
        $user = $entityManager->getRepository(User::class)->find($id);

        // If user does not exist, redirect to /notfound
        if (!$user) {
            return $this->redirectToRoute('app_notfound');
        }

        // Check if the logged-in user has the same ID as the one being edited
        if (!$loggeduser instanceof User || $loggeduser->getId() !== $user->getId()) {
            return $this->redirectToRoute('app_unauthorized');
        }

        $status = $this->userStatusService->checkUserStatus();

        if ($status === 'app_inactive') {
            return $this->redirectToRoute('app_inactive');
        }

        // Create and process the form
        $form = $this->createForm(UserRegistrationType::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->editService->edit($user, $form);

            return $this->redirectToRoute('app_profile_index', ['id' => $user->getId()]);
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/{id}/cpass', name: 'app_profile_cpass', methods: ['GET', 'POST'])]
    public function cpass(int $id, Request $request, UserPasswordService $userPasswordService, EntityManagerInterface $entityManager): Response
    {
        // Get the currently logged-in user
        $loggeduser = $this->getUser();

        // Fetch the user from the database
        $user = $entityManager->getRepository(User::class)->find($id);

        // If user does not exist, redirect to /notfound
        if (!$user) {
            return $this->redirectToRoute('app_notfound');
        }

        // Check if the logged-in user has the same ID as the one being edited
        if (!$loggeduser instanceof User || $loggeduser->getId() !== $user->getId()) {
            return $this->redirectToRoute('app_unauthorized');
        }
        
        $status = $this->userStatusService->checkUserStatus();

        if ($status === 'app_inactive') {
            return $this->redirectToRoute('app_inactive');
        }

        $form = $this->createForm(UserRegistrationType::class, $user, ['is_cpass' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userPasswordService->changePassword($user, $user->getPassword());

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/changepass.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}