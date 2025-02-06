<?php

namespace App\Controller;

use App\Entity\ActivityCode;
use App\Form\ActivityCodeType;
use App\Repository\ActivityCodeRepository;
use App\Service\TimeFormatterService;
use App\Service\ActivityCodeSortingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/activity_code')]
final class ActivityCodeController extends AbstractController
{
    private TimeFormatterService $timeFormatterService;
    private ActivityCodeSortingService $activityCodeSortingService;

    public function __construct(TimeFormatterService $timeFormatterService, ActivityCodeSortingService $activityCodeSortingService)
    {
        $this->timeFormatterService = $timeFormatterService;
        $this->activityCodeSortingService = $activityCodeSortingService;
    }

    #[Route(name: 'app_activity_code_index', methods: ['GET'])]
    public function index(ActivityCodeRepository $activityCodeRepository): Response
    {

        // $activity_codes = $activityCodeRepository->findAll();
        $activity_codes = $this->activityCodeSortingService->sortActivityCodes('activityCode', order: 'ASC');
        $now = new \DateTimeImmutable();

        foreach ($activity_codes as $activity_code) {
            $activity_code->creationTime = $this->timeFormatterService->getRelativeTime($activity_code->getCreatedAt(), $now);
            $activity_code->relativeTime = $this->timeFormatterService->getRelativeTime($activity_code->getUpdatedAt(), $now);
        }

        return $this->render('activity_code/index.html.twig', [
            'activity_codes' => $activity_codes,
        ]);
    }

    #[Route('/new', name: 'app_activity_code_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activityCode = new ActivityCode();
        $form = $this->createForm(ActivityCodeType::class, $activityCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activityCode->setCreatedAt(new \DateTimeImmutable());
            $activityCode->setModifiedBy($this->getUser());
            $entityManager->persist($activityCode);
            $entityManager->flush();

            // $this->addFlash('success', 'Activity Code created successfully.');

            return $this->redirectToRoute('app_activity_code_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activity_code/new.html.twig', [
            'activity_code' => $activityCode,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activity_code_show', methods: ['GET'])]
    public function show(ActivityCode $activityCode): Response
    {
        return $this->render('activity_code/show.html.twig', [
            'activity_code' => $activityCode,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activity_code_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ActivityCode $activityCode, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActivityCodeType::class, $activityCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activityCode->setUpdatedAt(new \DateTimeImmutable());
            $activityCode->setModifiedBy($this->getUser());
            $entityManager->flush();

            // $this->addFlash('success', 'Activity Code updated successfully.');

            return $this->redirectToRoute('app_activity_code_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activity_code/edit.html.twig', [
            'activity_code' => $activityCode,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activity_code_delete', methods: ['POST'])]
    public function delete(Request $request, ActivityCode $activityCode, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activityCode->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($activityCode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_activity_code_index', [], Response::HTTP_SEE_OTHER);
    }
}
