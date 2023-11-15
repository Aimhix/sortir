<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Services\ActivityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


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




    private $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * @Route("/activity/subscribe/{activityId}", name="activity_subscribe")
     */
    public function subscribeAction(int $activityId, UserInterface $user, Request $request): Response
    {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($activityId);

        if (!$activity) {
            throw $this->createNotFoundException('Cette sortie est introuvable.');
        }

        //tenter de regarder si l'utilisateur peut s'inscrire ?
        
        try {
            if ($request->query->get('action') == 'subscribe') {
                $this->activityService->subscribeToActivity($user, $activity);
                $this->addFlash('success', 'Vous êtes inscris à cette sortie.');
            } elseif ($request->query->get('action') == 'unsubscribe') {
                $this->activityService->unsubscribeFromActivity($user, $activity);
                $this->addFlash('success', 'Vous avez quitté cette sortie.');
            } else {
                throw new \InvalidArgumentException('Action impossible.');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('activity_list');
    }

}
