<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Shop;
use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return array<int, Product>
     */
    public function findProducts(int $limit = 20, int $offset = 0, ?int $managerId = null): array
    {
        $qb = $this->createQueryBuilder('p')->setMaxResults($limit)->setFirstResult($offset);

        if (null !== $managerId) {
            $qb->innerJoin(Shop::class, 'shop')
                ->andWhere('shop.manager = :managerId')
                ->setParameter('managerId', $managerId)
            ;

            $products = $qb->getQuery()->getResult();

            /** @var ShopRepository $shopRepository */
            $shopRepository = $this->getEntityManager()->getRepository(Shop::class);
            $managerShopIds = $shopRepository->findShopIdsByManagerId($managerId);

            // Not the best way to filter only manager's products, can be refactored using MySQL
            foreach ($products as $product) {
                /* @var Product $product */
                $product->setStocks(
                    $product->getStocks()->filter(function ($stock) use ($managerShopIds) {
                        /* @var Stock $stock */
                        return in_array($stock->getShop()->getId(), $managerShopIds);
                    })
                );
            }

            return $products;
        }

        return $qb->getQuery()->getResult();
    }
}
