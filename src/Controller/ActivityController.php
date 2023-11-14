<?php

namespace App\Controller;

use App\Entity\Activity;
use Doctrine\ORM\EntityManager;
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




    #[Route('/create', name: 'app_create')]
    public function createActivity(Request $request, EntityManager $entityManager): Response
    {

        $activity = new Activity();
        $activityForm = $this->createForm(Activity::class, $activity);

        $activityForm->handleRequest($request);


        if ($activityForm->isValid() && $activityForm->isSubmitted()){
            $entityManager->persist($activity);
            $entityManager->flush();

            $this->addFlash('success', 'Activitée créée avec succès !');
        }

        return $this->render('activity/create.html.twig', [
            'activityForm' => $activityForm
        ]);
    }
}








//public function new(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $wish = new Wish();
//        $wish->setUser($this->getUser());
//        $wishForm = $this->createForm(WishType::class, $wish);
//
//        $wishForm->handleRequest($request);
//
//        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
//            $entityManager->persist($wish);
//            $entityManager->flush();
//
//            $this->addFlash('success', 'Souhait enregistré avec succès !');
//
//            return $this->redirectToRoute('wish_show', ['id' => $wish->getId()]);
//        }
//
//        return $this->render('wish/new.html.twig', [
//            'wishForm' => $wishForm->createView()
//        ]);
//    }