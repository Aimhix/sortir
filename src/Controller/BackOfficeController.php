<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Location;
use App\Form\CityType;
use App\Form\CityUpdateType;
use App\Form\LocationType;
use App\Repository\CityRepository;
use App\Repository\LocationRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use App\Services\BackOfficeServices;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BackOfficeController extends AbstractController
{

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/back_office/users_management', name: 'app_users_management')]
    public function userBackOffice(UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();

        return $this->render('backoffice/back_office_user.html.twig', [
            'users' => $users
        ]);
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/back_office/city_management', name: 'app_city_management')]
    public function cityBackOffice(CityRepository $cityRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $cities = $cityRepository->findAll();

        $newCity = new City();
        $newCityForm = $this->createForm(CityType::class, $newCity);

        $newCityForm->handleRequest($request);

        if ($newCityForm->isSubmitted() && $newCityForm->isValid()) {
            $entityManager->persist($newCity);
            $entityManager->flush();

            $this->addFlash('success', 'Ville créée avec succès !');

            return $this->redirectToRoute('app_city_management');
        }

        return $this->render('backoffice/back_office_city.html.twig', [
            'cityForm' => $newCityForm->createView(),
            'cities' => $cities,
        ]);
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/back_office/users_management_soft_delete/{id}', name: 'app_users_management_soft_delete')]
    public function softDeleteUser(BackOfficeServices $backOfficeServices, UserRepository $userRepository, int $id, EntityManagerInterface $entityManager, StatusRepository $statusRepository): Response
    {
        $user = $userRepository->findOneById($id);
        $backOfficeServices->softDeleteUser($user, $statusRepository, $entityManager);
        return $this->redirectToRoute('app_users_management');
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/back_office/users_management_delete/{id}', name: 'app_users_management_delete')]
    public function deleteUser(BackOfficeServices $backOfficeServices, UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->findOneById($id);
        $backOfficeServices->deleteUser($user, $userRepository);
        return $this->redirectToRoute('app_users_management');
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/back_office/cities_management_delete/{id}', name: 'app_cities_management_delete')]
    public function deleteCity(BackOfficeServices $backOfficeServices, CityRepository $cityRepository, int $id): Response
    {
        $city = $cityRepository->findOneById($id);
        $backOfficeServices->deleteCity($city, $cityRepository);
        return $this->redirectToRoute('app_city_management');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/back_office/location_management', name: 'app_location_management')]
    public function locationBackOffice(LocationRepository $locationRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $locations = $locationRepository->findAll();


        $location = new Location();
        $locationForm = $this->createForm(LocationType::class, $location);

        $locationForm->handleRequest($request);

        if ($locationForm->isSubmitted() && $locationForm->isValid()) {

            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', 'Ville créée avec succès !');

            return $this->redirectToRoute('app_location_management');
        }

        return $this->render('backoffice/back_office_location.html.twig', [
            'locationForm' => $locationForm->createView(),
            'locations' => $locations
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/back_office/location_management_delete/{id}', name: 'app_location_management_delete')]
    public function deleteLocation(BackOfficeServices $backOfficeServices, LocationRepository $locationRepository, int $id): Response
    {
        $location = $locationRepository->findOneById($id);
        $backOfficeServices->deleteLocation($location, $locationRepository);
        return $this->redirectToRoute('app_location_management');
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/back_office/cities_update/{id}', name: 'app_city_management_update')]
    public function updateCity(EntityManagerInterface $entityManager,CityRepository $cityRepository, int $id, Request $request): Response
    {

        $city = $cityRepository->findOneById($id);
        $cityForm = $this->createForm(CityType::class, $city);

        $cityForm->handleRequest($request);

        if ($cityForm->isSubmitted() && $cityForm->isValid()) {

            $entityManager->persist($city);
            $entityManager->flush();

            $this->addFlash('success', 'Ville modifié avec succes !');

            return $this->redirectToRoute('app_city_management');
        }

        return $this->render('backoffice/back_office_city_update.html.twig', [
            'cityForm' => $cityForm->createView()
        ]);
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/back_office/locations_update/{id}', name: 'app_location_management_update')]
    public function updateLocation(EntityManagerInterface $entityManager,LocationRepository $locationRepository, int $id, Request $request): Response
    {

        $location = $locationRepository->findOneById($id);
        $locationForm = $this->createForm(LocationType::class, $location);

        $locationForm->handleRequest($request);

        if ($locationForm->isSubmitted() && $locationForm->isValid()) {

            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu modifié avec succes !');

            return $this->redirectToRoute('app_location_management');
        }

        return $this->render('backoffice/back_office_location_update.html.twig', [
            'locationForm' => $locationForm->createView()
        ]);
    }

}