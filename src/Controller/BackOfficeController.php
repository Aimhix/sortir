<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use App\Repository\LocationRepository;
use App\Repository\UserRepository;
use App\Services\BackOfficeServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class BackOfficeController extends AbstractController
{

    #[Route('/back_office/', name: 'app_back_office')]
    public function mainBackOffice(): Response
    {
        return $this->render('backoffice/back_office.html.twig');
    }


    #[Route('/back_office/users_management', name: 'app_users_management')]
    public function userBackOffice( UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();

        return $this->render('backoffice/back_office_user.html.twig', [
            'users' => $users
        ]);
    }


    #[Route('/back_office/city_management', name: 'app_city_management')]
    public function cityBackOffice( CityRepository $cityRepository): Response
    {
        $cities = $cityRepository->findAll();

        return $this->render('backoffice/back_office_city.html.twig', [
            'cities' => $cities
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
    public function deleteUser(BackOfficeServices $backOfficeServices, UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->findOneById($id);
        $backOfficeServices->deleteUser($user, $userRepository);
        return $this->redirectToRoute('app_users_management');
    }


    #[Route('/back_office/cities_management_delete/{id}', name: 'app_cities_management_delete')]
    public function deleteCity(BackOfficeServices $backOfficeServices, CityRepository $cityRepository, int $id): Response
    {
        $city = $cityRepository->findOneById($id);
        $backOfficeServices->deleteCity($city, $cityRepository);
        return $this->redirectToRoute('app_city_management');
    }

    #[Route('/back_office/cities_management_new', name: 'app_cities_management_new')]
    public function newCity(Request $request, EntityManagerInterface $entityManager): Response
    {
        $city = new City();
        $cityForm = $this->createForm(CityType::class, $city);

        $cityForm->handleRequest($request);

        if ($cityForm->isSubmitted() && $cityForm->isValid()){

            $entityManager->persist($city);
            $entityManager->flush();

            $this->addFlash('success', 'Ville créée avec succès !');

            return $this->redirectToRoute('app_city_management');
        }

        return $this->render('backoffice/new_city.html.twig', [
            'cityForm' => $cityForm ->createView()
        ]);
    }



    #[Route('/back_office/location_management', name: 'app_location_management')]
    public function locationBackOffice( LocationRepository $locationRepository): Response
    {
        $locations = $locationRepository->findAll();

        return $this->render('backoffice/back_office_location.html.twig', [
            'locations' => $locations
        ]);
    }


















}