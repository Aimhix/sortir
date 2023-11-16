<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Repository\StatusRepository;
use App\Services\ActivityUpdateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_activity_index')]
    public function index(ActivityRepository $activityRepository, EntityManagerInterface $entityManager, StatusRepository $statusRepository): Response
    {
        $activities = $activityRepository->findAll();

        $activityUpdate = new ActivityUpdateService();
        $activityUpdate->activityUpdate($activityRepository,$entityManager,$statusRepository);

        return $this->render('activity/index.html.twig', [
            'activities' => $activities,
        ]);
    }
}
