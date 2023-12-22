<?php

namespace App\Service;

use App\Entity\Freelancer;
use App\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class FreelancerService extends AbstractService
{
    public function activateProfile(FormInterface $form, Request $request, User $user): array
    {
        $form->handleRequest($request);
        $success = false;

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$freelancer = $user->getFreelancer()) {
                $freelancer = new Freelancer();
                $freelancer->setUser($user);
            }
            $freelancer->setStatus(Freelancer::STATUS_ACTIVE);
            $freelancer->setDefaultContactForm($form->getData() ?? []);

            $this->entityManager->persist($freelancer);
            $this->entityManager->flush();
            $success = true;
        }

        return [$success, $form];
    }

    public function getContactForm(User|UserInterface $user): array
    {
        $freelancer = $user->getFreelancer();
        return $freelancer ? $freelancer->getDefaultContactForm() : [];
    }
}