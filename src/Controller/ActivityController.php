<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ActivityController extends AbstractController
{


    #[Route('/', name: 'app_activity_index')]
    public function index(): Response
    {
        return $this->render('activity/index.html.twig');
    }




    #[Route('/create', name: 'app_activity_create')]
    public function createActivity(Request $request, EntityManagerInterface $entityManager): Response
    {

        $activity = new Activity();
        $activityForm = $this->createForm(ActivityType::class, $activity);

        $activityForm->handleRequest($request);


        if ($activityForm->isSubmitted() && $activityForm->isValid()){
            $entityManager->persist($activity);
            $entityManager->flush();

            $this->addFlash('success', 'Activitée créée avec succès !');
        }

        return $this->renderForm('activity/create.html.twig', [
            'activityForm' => $activityForm
        ]);
    }
}
