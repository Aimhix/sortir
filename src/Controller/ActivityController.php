<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Location;
use App\Entity\User;
use App\DTO\ActivitySearchDTO;
use App\Form\ActivitySearchType;
use App\Form\ActivityType;
use App\Form\LocationType;
use App\Repository\ActivityRepository;
use App\Services\ActivityService;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ActivityController extends AbstractController
{
    #[Route('/create', name: 'app_activity_create')]
    public function createActivity(StatusRepository $statusRepository ,Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $activity = new Activity();
        $activity->setOrganizer($user);
        $activity->setCampus($user->getCampus());
        $activity->setStatus($statusRepository->findOneByWording('Créée'));
        $activityForm = $this->createForm(ActivityType::class, $activity);

        $activityForm->handleRequest($request);


        if ($activityForm->isSubmitted() && $activityForm->isValid()){

            $entityManager->persist($activity);
            $entityManager->flush();

            $this->addFlash('success', 'Activitée créée avec succès !');

            return $this->redirectToRoute('app_activity_index');
        }

        return $this->render('activity/create.html.twig', [
            'activityForm' => $activityForm ->createView(), 'user' => $user
        ]);
    }


    #[Route('/create_location', name: 'app_activity_create_location')]
    public function createLocation(Request $request, EntityManagerInterface $entityManager): Response
    {

        $location = new Location();
        $locationForm = $this->createForm(LocationType::class, $location);

        $locationForm->handleRequest($request);

        if ($locationForm->isSubmitted() && $locationForm->isValid()){
            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu créée avec succès !');

            return $this->redirectToRoute('app_activity_index');
        }

        return $this->render('activity/create_location.html.twig', [
            'locationForm' => $locationForm ->createView()
        ]);
    }

    #[Route('/activity/subscribe/{activityId}', name: 'activity_subscribe')]
    public function subscribeAction(EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry, int $activityId, UserInterface $user, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('L\'utilisateur actuel n\'est pas une instance de \App\Entity\User');
    }

        $activityService = new ActivityService($entityManager);
        $activity = $managerRegistry->getRepository(Activity::class)->find($activityId);
        if (!$activity) {
            throw $this->createNotFoundException('Cette sortie est introuvable.');
        }
//tenter de regarder si l'utilisateur peut s'inscrire ?

        try {
            if ($request->query->get('action') == 'subscribe') {
                $activityService->subscribeToActivity($user, $activity);
                $this->addFlash('success', 'Vous êtes inscris à cette sortie.');
            } elseif ($request->query->get('action') == 'unsubscribe') {
                $activityService->unsubscribeFromActivity($user, $activity);
                $this->addFlash('success', 'Vous avez quitté cette sortie.');
            } else {
                throw new \InvalidArgumentException('Action impossible.');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('activity_show');
    }


    #[Route('/activity/{id}', name: 'activity_show')]
    public function show(Activity $activity): Response
    {
        return $this->render('activity/show.html.twig', ['activity' => $activity]);
    }

    #[Route('/search', name: 'activity_search')]
    public function search(Request $request, ActivityRepository $activityRepository): Response
    {
        $searchDTO = new ActivitySearchDTO();
        $form = $this->createForm(ActivitySearchType::class, $searchDTO);
        $form->handleRequest($request);

        $activities = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $activities = $activityRepository->findBySearchCriteria($searchDTO, $user);
        }

        // Gestion du piti AJAX
        if ($request->isXmlHttpRequest()) {
            return $this->render('fragments/_searchResults.html.twig', [
                'activities' => $activities,
            ]);
        }

        // Other requêtes
        // remplacé search par main pour test
        return $this->render('main/index.html.twig', [
            'form' => $form->createView(),
            'activities' => $activities,
        ]);
    }

}