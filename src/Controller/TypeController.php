<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use App\Service\TimeFormatterService;
use App\Service\TypeSortingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/type')]
final class TypeController extends AbstractController
{
    private TimeFormatterService $timeFormatterService;
    // private TypeSortingService $typeSortingService;

    public function __construct(TimeFormatterService $timeFormatterService
    // , TypeSortingService $typeSortingService
    )
    {
        $this->timeFormatterService = $timeFormatterService;
        // $this->typeSortingService = $typeSortingService;
    }

    #[Route(name: 'app_type_index', methods: ['GET'])]
    public function index(TypeRepository $typeRepository): Response
    {
        
        $types = $typeRepository->findAll();
        $now = new \DateTimeImmutable();

        foreach ($types as $type) {
            $type->creationTime = $this->timeFormatterService->getRelativeTime($type->getCreatedAt(), $now);
            $type->relativeTime = $this->timeFormatterService->getRelativeTime($type->getUpdatedAt(), $now);
        }
        return $this->render('type/index.html.twig', [
            'types' => $types,
        ]);
    }

    #[Route('/new', name: 'app_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $type = new Type();
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type->setCreatedAt(new \DateTimeImmutable());
            $type->setModifiedBy($this->getUser());
            $entityManager->persist($type);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type/new.html.twig', [
            'type' => $type,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_show', methods: ['GET'])]
    public function show(Type $type): Response
    {
        return $this->render('type/show.html.twig', [
            'type' => $type,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Type $type, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type->setUpdatedAt(new \DateTimeImmutable());
            $type->setModifiedBy($this->getUser());
            $entityManager->flush();

            return $this->redirectToRoute('app_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type/edit.html.twig', [
            'type' => $type,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_delete', methods: ['POST'])]
    public function delete(Request $request, Type $type, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$type->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($type);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
