<?php

namespace App\Controller;

use App\Pagination\Paginator;
use App\Repository\ManagerRepository;
use App\Response\APIResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ManagerController extends AbstractController
{
    #[Route('/managers', name: 'managers')]
    public function list(Request $request, ManagerRepository $managerRepository): APIResponse
    {
        $paginator = new Paginator($request);

        $managers = $managerRepository->findBy([], null, $paginator->getLimit(), $paginator->getOffset());

        return new APIResponse($managers, 200, [
            'X-Total-Count' => $managerRepository->count([]),
            'X-Offset' => $paginator->getOffset(),
            'X-Limit' => $paginator->getLimit(),
        ]);
    }
}
