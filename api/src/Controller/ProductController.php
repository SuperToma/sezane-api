<?php

namespace App\Controller;

use App\Pagination\Paginator;
use App\Repository\ProductRepository;
use App\Response\APIResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'product')]
    public function get(int $id, ProductRepository $productRepository): APIResponse
    {
        $product = $productRepository->findOneBy(['id' => $id]);

        return new APIResponse($product, $product ? 200 : 404);
    }

    #[Route('/products', name: 'products')]
    public function list(Request $request, ProductRepository $productRepository): APIResponse
    {
        $paginator = new Paginator($request);

        $products = $productRepository->findProducts(
            $paginator->getLimit(),
            $paginator->getOffset(),
            $request->get('managerId')
        );

        // Remove stocks from other shops
        /*if (null !== $request->get('managerId')) {
            $products = array_map(function ($product) {
                $product->getStocks();
            }, $products);
        } */

        return new APIResponse($products, 200, [
            'X-Total-Count' => $productRepository->count([]),
            'X-Offset' => $paginator->getOffset(),
            'X-Limit' => $paginator->getLimit(),
        ]);
    }
}
