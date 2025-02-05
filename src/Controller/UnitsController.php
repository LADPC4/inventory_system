<?php

namespace App\Controller;

use App\Entity\Units;
use App\Form\UnitsType;
use App\Service\TimeFormatterService;
use App\Repository\UnitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/units')]
final class UnitsController extends AbstractController
{
    private TimeFormatterService $timeFormatterService;

    public function __construct(TimeFormatterService $timeFormatterService)
    {
        $this->timeFormatterService = $timeFormatterService;
    }

    #[Route(name: 'app_units_index', methods: ['GET'])]
    public function index(UnitsRepository $unitsRepository): Response
    {
        $units = $unitsRepository->findAll();
        $now = new \DateTimeImmutable();

        foreach ($units as $unit) {
            $unit->creationTime = $this->timeFormatterService->getRelativeTime($unit->getCreatedAt(), $now);
            $unit->relativeTime = $this->timeFormatterService->getRelativeTime($unit->getUpdatedAt(), $now);
        }

        return $this->render('units/index.html.twig', [
            'units' => $unitsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_units_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $unit = new Units();
        $form = $this->createForm(UnitsType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unit->setCreatedAt(new \DateTimeImmutable());
            $unit->setModifiedBy($this->getUser());
            $entityManager->persist($unit);
            $entityManager->flush();

            $this->addFlash('success', 'Unit created successfully.');

            return $this->redirectToRoute('app_units_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('units/new.html.twig', [
            'unit' => $unit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_units_show', methods: ['GET'])]
    public function show(Units $unit): Response
    {
        return $this->render('units/show.html.twig', [
            'unit' => $unit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_units_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Units $unit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UnitsType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unit->setUpdatedAt(new \DateTimeImmutable());
            $unit->setModifiedBy($this->getUser());
            $entityManager->flush();

            $this->addFlash('success', 'Unit updated successfully.');
            

            return $this->redirectToRoute('app_units_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('units/edit.html.twig', [
            'unit' => $unit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_units_delete', methods: ['POST'])]
    public function delete(Request $request, Units $unit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$unit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($unit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_units_index', [], Response::HTTP_SEE_OTHER);
    }
}
