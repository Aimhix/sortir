<?php

namespace App\Services;

use App\Entity\City;
use App\Entity\Location;
use App\Entity\User;

use App\Repository\CityRepository;
use App\Repository\LocationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class BackOfficeServices
{

    public function softDeleteUser(User $user, EntityManagerInterface $entityManager)
    {
        if ($user->isActiveStatus()) {
            $user->setActiveStatus(false);
        }
        else {
            $user->setActiveStatus(true);
        }

        $entityManager->persist($user);
        $entityManager->flush();
    }

    public function deleteUser(User $user, UserRepository $userRepository)
    {
        $userRepository->remove($user, true);

    }

    public function deleteCity(City $city, CityRepository $cityRepository)
    {
        $cityRepository->remove($city, true);
    }

    public function deleteLocation(Location $location, LocationRepository $locationRepository)
    {
        $locationRepository->remove($location, true);
    }
}