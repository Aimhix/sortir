<?php

namespace App\Services;

use App\Entity\User;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class BackOfficeServices
{

    public function softDeleteUser(User $user, EntityManagerInterface $entityManager){
        $user->setActiveStatus(false);

        $entityManager->persist($user);
        $entityManager->flush();
    }

    public function deleteUser(User $user, EntityManagerInterface $entityManager, UserRepository $userRepository){
        $userRepository->remove($user, true);

    }
}