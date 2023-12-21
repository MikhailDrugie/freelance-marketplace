<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property null $service - no service needed for this controller
 */
class SiteController extends BaseController
{
    #[Route('/', name: "index")]
    public function index(): Response
    {
        return $this->render("index");
    }
}
