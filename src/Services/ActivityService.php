<?php

namespace App\Services;

use App\Entity\Activity;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ActivityService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function subscribeToActivity(\App\Entity\User $user, Activity $activity)
    {
        $currentDate = new \DateTime();
        if ($currentDate > $activity->getSubLimitDate()) {
            throw new \Exception("La date d'inscription est dépassée.");
        }

        if (count($activity->getUsers()) >= $activity->getSubMax()) {
            throw new \Exception("Plus de places disponibles.");
        }

        $activity->addUser($user);
        $this->entityManager->persist($activity);
        $this->entityManager->flush();
    }

    public function unsubscribeFromActivity(\App\Entity\User $user, Activity $activity)
    {
        if (!$activity->getUsers()->contains($user)) {
            throw new \Exception("Vous n'êtes plus inscrit à cette sortie.");
        }

        $activity->removeUser($user);
        $this->entityManager->persist($activity);
        $this->entityManager->flush();
    }

}
