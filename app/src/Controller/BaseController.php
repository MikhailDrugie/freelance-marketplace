<?php

namespace App\Controller;

use App\Service\AbstractService;
use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    public function __construct(
        protected ?AbstractService $service = null,
        protected ?string $viewDir = null,
        protected ?PaginationService $paginationService = null
    ) {}
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $viewPath = $this->viewDir ? "{$this->viewDir}/{$view}.html.twig" : "{$view}.html.twig";
        return parent::render($viewPath, $parameters, $response);
    }
}