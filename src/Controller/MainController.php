<?php

namespace App\Controller;

use App\DTO\ActivitySearchDTO;
use App\Entity\User;
use App\Form\ActivitySearchType;
use App\Repository\ActivityRepository;
use App\Repository\StatusRepository;
use App\Services\ActivityUpdateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_activity_index')]
    public function index(Request $request, ActivityRepository $activityRepository, EntityManagerInterface $entityManager, StatusRepository $statusRepository): Response
    {
        $user = $this->getUser();
        $activities = $activityRepository->findAll();

        $activityUpdate = new ActivityUpdateService();
        $activityUpdate->activityUpdate($activityRepository, $entityManager, $statusRepository);

        if ($user instanceof User) {
            $searchDTO = new ActivitySearchDTO();
            $form = $this->createForm(ActivitySearchType::class, $searchDTO);
            $form->handleRequest($request);

            $activities = $activityRepository->findLatestActivities(9); // Récupération des 9 dernières sorties

            if ($form->isSubmitted() && $form->isValid()) {
                // Recherche basée sur les critères fournis
                $activities = $activityRepository->findBySearchCriteria($searchDTO, $user);

                // Si requête AJAX
                if ($request->isXmlHttpRequest()) {
                    return $this->render('activity/_searchResults.html.twig', [
                        'activities' => $activities,
                    ]);
                }
            }
        } else {
            return $this->redirectToRoute('app_login');
        }

        // Déplacer la vérification de $form ici
        return $this->render('activity/index.html.twig', [
            'form' => $form?->createView(),
            'activities' => $activities,
        ]);
    }
}
