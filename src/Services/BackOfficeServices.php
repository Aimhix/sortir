<?php

namespace App\Services;

use App\Entity\City;
use App\Entity\Location;
use App\Entity\User;

use App\Repository\CityRepository;
use App\Repository\LocationRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class BackOfficeServices
{

    public function softDeleteUser(User $user, StatusRepository $statusRepository, EntityManagerInterface $entityManager)
    {
        if ($user->isActiveStatus()) {
            $user->setActiveStatus(false);

            $user->setActiveStatus(false);

            // Annuler toutes les activités organisées par l'utilisateur
            foreach ($user->getActivitiesOrganized() as $activity) {
                $cancelledStatus = $statusRepository->findOneBy(['wording' => 'Annulée']);
                $activity->setStatus($cancelledStatus);
            }

            // Désinscrire l'utilisateur de toutes les activités auxquelles il est inscrit
            foreach ($user->getActivities() as $activity) {
                $activity->removeUser($user);
            }



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