<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_activity_index')]
    public function index(ActivityRepository $activityRepository): Response
    {
        $activities = $activityRepository->findAll();

        return $this->render('activity/index.html.twig', [
            'activities' => $activities,
        ]);
    }
}
