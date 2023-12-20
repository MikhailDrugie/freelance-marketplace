<?php

namespace App\Entity;

use Doctrine\ORM\EntityManagerInterface;

class BaseEntity
{
    public function __construct(?EntityManagerInterface $entityManager = null)
    {
    }
}