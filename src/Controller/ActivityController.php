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
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{

    #[Route('/create', name: 'app_activity_create')]
    public function createActivity(StatusRepository $statusRepository, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $activity = new Activity();
        $activity->setOrganizer($user);
        $activity->setCampus($user->getCampus());
        $activity->setStatus($statusRepository->findOneByWording('Créée'));
        $activityForm = $this->createForm(ActivityType::class, $activity);

        $activityForm->handleRequest($request);


        if ($activityForm->isSubmitted() && $activityForm->isValid()) {

            $imageFile = $activityForm['activityPicture']->getData();

            if ($imageFile) {
                $newFilename = $fileUploader->upload($imageFile, $this->getParameter('kernel.project_dir') . '/public/images/');
                $activity->setActivityPicture($newFilename);
            }

            $entityManager->persist($activity);
            $entityManager->flush();

            $this->addFlash('success', 'Activité créée avec succès !');

            return $this->redirectToRoute('app_activity_index');
        }

        return $this->render('activity/create.html.twig', [
            'activityForm' => $activityForm->createView(), 'user' => $user
        ]);
    }


    #[Route('/create_location', name: 'app_activity_create_location')]
    public function createLocation(Request $request, EntityManagerInterface $entityManager): Response
    {

        $location = new Location();
        $locationForm = $this->createForm(LocationType::class, $location);

        $locationForm->handleRequest($request);

        if ($locationForm->isSubmitted() && $locationForm->isValid()) {

            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu créée avec succès !');

            return $this->redirectToRoute('app_activity_create');
        }

        return $this->render('activity/create_location.html.twig', [
            'locationForm' => $locationForm->createView()
        ]);
    }

    #[Route('/activity/subscribe/{activityId}', name: 'activity_subscribe')]
    public function subscribeAction(EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry, int $activityId): Response
    {

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

        $activityService->subscribeToActivity($user, $activity);
        $this->addFlash('success', 'Vous êtes inscris à cette sortie.');

        return $this->redirectToRoute('activity_show', ['id' => $activityId]);
    }

    #[Route('/activity/unsubscribe/{activityId}', name: 'activity_unsubscribe')]
    public function unsubscribeAction(EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry, int $activityId): Response
    {
        $user = $this->getUser();

        $activityService = new ActivityService($entityManager);
        $activity = $managerRegistry->getRepository(Activity::class)->find($activityId);
        if (!$activity) {
            throw $this->createNotFoundException('Cette sortie est introuvable.');
        }

        $activityService->unsubscribeFromActivity($user, $activity);
        $this->addFlash('success', 'Vous êtes désinscris à cette sortie.');

        return $this->redirectToRoute('activity_show', ['id' => $activityId]);
    }


    #[Route('/activity/cancel/{activityId}', name: 'activity_cancel')]
    public function cancelActivity(EntityManagerInterface $entityManager, int $activityId, ActivityRepository $activityRepository, StatusRepository $statusRepository): Response
    {
        $user = $this->getUser();

        $activity = $activityRepository->find($activityId);
        if (!$activity) {
            throw $this->createNotFoundException('Cette sortie est introuvable.');
        }


        if ($this->isGranted('ROLE_ADMIN') || $activity->getOrganizer()->getId() == $user->getId()) {
            $activity->setStatus($statusRepository->findOneByWording('Annulée'));
            $entityManager->persist($activity);
            $entityManager->flush();
            $participants = $activity->getUsers();
            $actionMessage = 'Activité annulée avec succès.';
            foreach ($participants as $participant) {
                if ($participant->getId() == $user->getId()) {
                    $actionMessage = 'Vous êtes inscrit(e) à cette sortie.';
                    break;
                }
            }

            $this->addFlash('success', $actionMessage);
            return $this->redirectToRoute('app_activity_index');
        }
        return throw $this->createAccessDeniedException('Seul l\'organisateur peu suprimer ses sorties');
    }


//    #[Route('/activity/{id}', name: 'activity_show')]
//    public function show(Activity $activity): Response
//    {
//        $user = $this->getUser();
//        return $this->render('activity/show.html.twig', [
//            'activity' => $activity,
//            'user' => $user
//        ]);
//    }

    #[Route('/activity/{id}', name: 'activity_show')]
    public function showList(Activity $activity): Response
    {
        

        $participant = $activity->getUsers();

        return $this->render('activity/show.html.twig', [
            'activity' => $activity,
            'participant' => $participant,
        ]);
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
            $organizer = $searchDTO->organizer;
            $isRegistered = $searchDTO->isRegistered;
            $isNotRegistered = $searchDTO->isNotRegistered;
            $isPast = $searchDTO->isPast;
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

    #[Route('/check-mobile-device', name: 'check_mobile_device')]
    public function checkMobileDevice(Request $request): Response
    {
        // Récupérer les données JSON envoyées depuis le client
        $data = json_decode($request->getContent(), true);
        if (isset($data['isMobile']) && $data['isMobile'] === true) {
            // S'il détecte la version mobile, il envoie ce message dans une page d'erreur
            //return $this->redirectToRoute('app_activity_index');
            return new Response('La création de sortie n\'est pas autorisée sur les appareils mobiles.', 403);
        }
        // Sinon renvoyer sur la page de création de sorties
        return $this->redirectToRoute('app_activity_create');
    }

    #[Route('/activity_publish/{activityId}', name: 'activity_publish')]
    public function publish(int $activityId, EntityManagerInterface $entityManager, ActivityRepository $activityRepository): Response
    {

        $updatedActivity = $activityRepository->findOneById($activityId);
        $updatedActivity->setIsPublished(true);

        $entityManager->persist($updatedActivity);
        $entityManager->flush();


        $this->addFlash('success', 'Votre sortie a bien été publiée.');

        return $this->redirectToRoute('activity_show', ['id' => $activityId]);
    }

    #[Route('/activity/edit/{id}', name: 'activity_edit')]
    public function editActivity(Request $request, ActivityRepository $activityRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $activity = $activityRepository->find($id);
        if (!$activity) {
            throw $this->createNotFoundException('Activité non trouvée.');
        }

        // Vérifiez si l'utilisateur est l'organisateur ou a le rôle ADMIN
        if ($activity->getOrganizer()->getId() !== $this->getUser()->getId() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'éditer cette activité.');
        }

        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vous pouvez ajouter une logique supplémentaire si nécessaire
            $entityManager->flush();

            $this->addFlash('success', 'Activité mise à jour avec succès.');

            return $this->redirectToRoute('activity_show', ['id' => $id]);
        }

        return $this->render('activity/edit.html.twig', [
            'activityForm' => $form->createView(),
            'activity' => $activity,
        ]);
    }


}