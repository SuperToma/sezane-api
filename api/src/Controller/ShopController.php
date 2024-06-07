<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Entity\Shop;
use App\Pagination\Paginator;
use App\Repository\ShopRepository;
use App\Response\APIResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/shops', name: 'shops')]
    public function list(Request $request, ShopRepository $shopRepository): APIResponse
    {
        $paginator = new Paginator($request);

        $shops = $shopRepository->findShops(
            $paginator->getLimit(),
            $paginator->getOffset(),
            $request->get('latitude'),
            $request->get('longitude'),
            $request->get('limitMeters')
        );

        return new APIResponse($shops, 200, [
            'X-Total-Count' => $shopRepository->count([]),
            'X-Offset' => $paginator->getOffset(),
            'X-Limit' => $paginator->getLimit(),
        ]);
    }

    #[Route('/shop/{id}', name: 'shop')]
    public function get(int $id, ShopRepository $shopRepository): APIResponse
    {
        $shop = $shopRepository->findOneBy(['id' => $id]);

        return new APIResponse($shop, $shop ? 200 : 404);
    }

    #[Route('/shop', name: 'shop_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): APIResponse|JsonResponse
    {
        $manager = $entityManager->getRepository(Manager::class)->find($request->get('managerId'));

        if (null === $manager) {
            return new JsonResponse(['error' => 'Invalid manager ID'], 400);
        }

        $shop = new Shop();
        $shop->setManager($manager)
            ->setName($request->get('name'))
            ->setAddress($request->get('address'))
            ->setLatitude($request->get('latitude'))
            ->setLongitude($request->get('longitude'))
        ;

        $entityManager->persist($shop);
        $entityManager->flush();

        return new APIResponse($shop);
    }
}
