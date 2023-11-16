<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CityRepository;
use App\Repository\UserRepository;
use App\Services\BackOfficeServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class BackOfficeController extends AbstractController
{

    #[Route('/back_office/', name: 'app_back_office')]
    public function mainBackOffice(): Response
    {
        return $this->render('activity/back_office.html.twig');
    }


    #[Route('/back_office/users_management', name: 'app_users_management')]
    public function userBackOffice( UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();

        return $this->render('activity/back_office_user.html.twig', [
            'users' => $users
        ]);
    }


    #[Route('/back_office/users_management_soft_delete/{id}', name: 'app_users_management_soft_delete')]
    public function softDeleteUser(BackOfficeServices $backOfficeServices, UserRepository $userRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneById($id);
        $backOfficeServices->softDeleteUser($user, $entityManager);
        return $this->redirectToRoute('app_users_management');
    }

    #[Route('/back_office/users_management_delete/{id}', name: 'app_users_management_delete')]
    public function deleteUser(BackOfficeServices $backOfficeServices, UserRepository $userRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneById($id);
        $backOfficeServices->deleteUser($user, $entityManager, $userRepository);
        return $this->redirectToRoute('app_users_management');
    }





}