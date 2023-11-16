<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 *
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function add(Activity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Activity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // à placer dans ActivityRepository
    // searchCriteria = tableau associatif de données

    public function findBySearchCriteria($searchCriteria, User $user)

    {
        $query = $this->createQueryBuilder('a')
            ->leftJoin('a.campus', 'c')
            ->leftJoin('a.users', 'u')
            ->leftJoin('a.organizer', 'o');

        // par campus
        if (!empty($searchCriteria->campus)) {
            $query->andWhere('c.name = :campus')
                ->setParameter('campus', $searchCriteria->campus);
        }

        // par termes (nature de la sortie)
        if (!empty($searchCriteria->search)) {
            $query->andWhere('a.name LIKE :search')
                ->setParameter('search', '%'.$searchCriteria->search.'%');
        }
        // par date de début
        if (!empty($searchCriteria->dateStart)) {
            $query->andWhere('a.dateStart >= :dateStart')
                ->setParameter('dateStart', $searchCriteria->dateStart);
        }

        // par date de fin
        if (!empty($searchCriteria->subLimitDate)) {
            $query->andWhere('a.dateStart <= :subLimitDate')
                ->setParameter('subLimitDate', $searchCriteria->subLimitDate);
        }
        // les sorties que l'utilisateur a organisé
        if (!empty($searchCriteria->organizer)) {
            $query->andWhere('o.id = :userId')
                ->setParameter('userId', $user->getId());
        }

        // les sorties où l'utilisateur est inscrit
        if (!empty($searchCriteria->isRegistered)) {
            $query->andWhere(':user MEMBER OF a.users')
                ->setParameter('user', $user);
        }

        // les sorties où l'utilisateur n'est pas inscrit
        if (!empty($searchCriteria->isNotRegistered)) {
            $query->andWhere(':user NOT MEMBER OF a.users')
                ->setParameter('user', $user);
        }

        // les sorties passées
        if (!empty($searchCriteria->isPast)) {
            $query->andWhere('a.dateStart < CURRENT_DATE()');
        }

        return $query->getQuery()->getResult();
    }

// méthode pour afficher des sorties sans faire recherche
    public function findLatestActivities(int $limit)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.subLimitDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Activity[] Returns an array of Activity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Activity
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
