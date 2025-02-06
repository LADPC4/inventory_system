<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\UserStatusService;

final class HomeController extends AbstractController
{
    protected UserStatusService $userStatusService;

    public function __construct(
        UserStatusService $userStatusService
    ){
        $this->userStatusService = $userStatusService;
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $status = $this->userStatusService->checkUserStatus();

        if ($status === 'app_inactive') {
            return $this->redirectToRoute('app_inactive');
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/unauthorized', name: 'app_unauthorized')]
    public function unauthorized(): Response
    {
        return $this->render('home/unauthorized.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/notfound', name: 'app_notfound')]
    public function notfound(): Response
    {
        return $this->render('home/notfound.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/inactive', name: 'app_inactive')]
    public function inactive(): Response
    {
        return $this->render('home/inactive.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
