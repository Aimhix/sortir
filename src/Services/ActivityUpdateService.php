<?php

namespace App\Services;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class ActivityUpdateService
{
    public function activityUpdate(ActivityRepository $activityRepository, EntityManagerInterface $entityManager, StatusRepository $statusRepository)
    {

        $activities = $activityRepository->findAll();

        foreach ($activities as $activity) {

            $currentDate = new \DateTime();
            $dateStart = $activity->getDateStart();
            $dateDifference = $dateStart->diff($currentDate)->days;


            if ($activity->isIsPublished() && $activity->getStatus()->getId() != 6) {
                $activity->setStatus($statusRepository->findOneByWording('Ouverte'));
            }

            if ($activity->getSubLimitDate() < $currentDate && $activity->getStatus()->getId() != 6) {
                $activity->setStatus($statusRepository->findOneByWording('Clôturée'));
            }

            if ($activity->getDateStart() < $currentDate && $activity->getStatus()->getId() != 6) {
                $activity->setStatus($statusRepository->findOneByWording('Activité en cours'));
            }

            if ($activity->getDateStart()->modify('+' . $activity->getDuration() . ' minutes') < $currentDate && $activity->getStatus()->getId() != 6) {
                $activity->setStatus($statusRepository->findOneByWording('Passée'));
            }

            if ($dateDifference > 30 && $dateStart < $currentDate)

                $activity->setStatus($statusRepository->findOneByWording('Archivée'));

            $entityManager->persist($activity);
            $entityManager->flush();
        }
    }
}