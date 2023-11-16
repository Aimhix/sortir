<?php

namespace App\Services;

use App\Entity\City;
use App\Entity\User;

use App\Repository\CityRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class BackOfficeServices
{

    public function softDeleteUser(User $user, EntityManagerInterface $entityManager){
        $user->setActiveStatus(false);

        $entityManager->persist($user);
        $entityManager->flush();
    }

    public function deleteUser(User $user, UserRepository $userRepository){
        $userRepository->remove($user, true);

    }

    public function deleteCity(City $city,CityRepository $cityRepository)
    {
        $cityRepository->remove($city, true);
    }
}