<?php

namespace App\Service;

use App\Entity\Employer;
use App\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class EmployerService extends AbstractService
{
    public function activateProfile(FormInterface $form, Request $request, User $user): array
    {
        $form->handleRequest($request);
        $success = false;

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$employer = $user->getEmployer()) {
                $employer = new Employer();
                $employer->setUser($user);
            }
            $employer->setStatus(Employer::STATUS_ACTIVE);
            $employer->setDefaultContactForm($form->getData() ?? []);

            $this->entityManager->persist($employer);
            $this->entityManager->flush();
            $success = true;
        }

        return [$success, $form];
    }

    public function getContactForm(User|UserInterface $user): array
    {
        $employer = $user->getEmployer();
        return $employer ? $employer->getDefaultContactForm() : [];
    }
}