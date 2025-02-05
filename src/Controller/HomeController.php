<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
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
}
