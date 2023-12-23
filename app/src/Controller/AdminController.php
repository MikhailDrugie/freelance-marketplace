<?php

namespace App\Controller;

use App\Service\AdminService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property AdminService $service
 */
class AdminController extends BaseController
{
    #[Route('/admin', name: 'admin-panel')]
    public function index(): Response
    {
        return $this->json([
            'status' => 'WIP'
        ]);
    }

    #[Route('/admin-logout', name: 'admin-logout')]
    public function logout(): Response
    {
        return (new Response('ok'))->setStatusCode(Response::HTTP_UNAUTHORIZED);
    }
}