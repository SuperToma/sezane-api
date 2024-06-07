<?php

namespace App\Repository;

use App\Entity\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Shop>
 */
class ShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shop::class);
    }

    /**
     * @return array<int, Shop>
     */
    public function findShops(
        int $limit = 20,
        int $offset = 0,
        ?float $latitude = null,
        ?float $longitude = null,
        ?float $limitMeters = null
    ): array {
        $computeDistance = !(null === $latitude || null == $longitude);

        $qb = $this->createQueryBuilder('s')->setMaxResults($limit)->setFirstResult($offset);

        if ($computeDistance) {
            $qb
                ->addSelect('DEGREES(
                ACOS(
                    (
                        SIN(RADIANS(:latitude)) * SIN(RADIANS(s.latitude))
                    ) + (
                        COS(RADIANS(:latitude)) * COS(RADIANS(s.latitude)) * COS(RADIANS(:longitude - s.longitude))
                    )
                )
            ) * :radius AS distanceMeters')
                ->setParameter('latitude', $latitude)
                ->setParameter('longitude', $longitude)
                ->setParameter('radius', 60 * 1.1515 * 1.609344 * 1000) // ( * 1.609344 = miles > kilometers)
                ->addOrderBy('distanceMeters', 'ASC')
            ;
        }

        if ($computeDistance && null !== $limitMeters) {
            $qb
                ->andHaving('distanceMeters <= :distanceMeters')
                ->setParameter('distanceMeters', $limitMeters)
            ;
        }

        $results = $qb->getQuery()->getResult();

        if ($computeDistance) {
            return array_map(
                function ($result) {
                    // 1. Round and set the distance in the object
                    $result[0]->setDistanceMeters(round($result['distanceMeters']));

                    // 2. Keep only the object (remove 1 level with the distance field)
                    return $result[0];
                },
                $results
            );
        }

        return $results;
    }

    /**
     * @return array<int>
     */
    public function findShopIdsByManagerId(int $managerId): array
    {
        $managerShopsIds = $this->createQueryBuilder('s')
            ->select('s.id')
            ->where('s.manager = :managerId')
            ->setParameter('managerId', $managerId)
            ->getQuery()->getResult()
        ;

        return array_map(fn ($shop) => $shop['id'], $managerShopsIds);
    }
}
