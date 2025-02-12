<?php

namespace App\Controller;

use App\Entity\Specification;
use App\Form\SpecificationType;
use App\Service\TimeFormatterService;
use App\Service\SpecificationSortingService;
use App\Repository\SpecificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/specification')]
final class SpecificationController extends AbstractController
{
    private TimeFormatterService $timeFormatterService;
    private SpecificationSortingService $specificationSortingService;

    public function __construct(TimeFormatterService $timeFormatterService, SpecificationSortingService $specificationSortingService)
    {
        $this->timeFormatterService = $timeFormatterService;
        $this->specificationSortingService = $specificationSortingService;
    }

    #[Route(name: 'app_specification_index', methods: ['GET'])]
    public function index(SpecificationRepository $specificationRepository): Response
    {

        $specifications = $this->specificationSortingService->sortSpecification('name', 'ASC');
        $now = new \DateTimeImmutable();

        foreach ($specifications as $specification) {
            $specification->creationTime = $this->timeFormatterService->getRelativeTime($specification->getCreatedAt(), $now);
            $specification->relativeTime = $this->timeFormatterService->getRelativeTime($specification->getUpdatedAt(), $now);
        }

        return $this->render('specification/index.html.twig', [
            'specifications' => $specifications,
        ]);
    }

    #[Route('/new', name: 'app_specification_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $specification = new Specification();
        $form = $this->createForm(SpecificationType::class, $specification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $specification->setCreatedAt(new \DateTimeImmutable());
            $specification->setModifiedBy($this->getUser());
            $entityManager->persist($specification);
            $entityManager->flush();

            return $this->redirectToRoute('app_specification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('specification/new.html.twig', [
            'specification' => $specification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_specification_show', methods: ['GET'])]
    public function show(Specification $specification): Response
    {
        return $this->render('specification/show.html.twig', [
            'specification' => $specification,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_specification_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Specification $specification, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SpecificationType::class, $specification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $specification->setUpdatedAt(new \DateTimeImmutable());
            $specification->setModifiedBy($this->getUser());
            $entityManager->flush();

            return $this->redirectToRoute('app_specification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('specification/edit.html.twig', [
            'specification' => $specification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_specification_delete', methods: ['POST'])]
    public function delete(Request $request, Specification $specification, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$specification->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($specification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_specification_index', [], Response::HTTP_SEE_OTHER);
    }
}
