<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractService
{
    protected $repository;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected $entity
    )
    {
        $this->repository = $this->entityManager->getRepository($this->entity::class);
    }
}