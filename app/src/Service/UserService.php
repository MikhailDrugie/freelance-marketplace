<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserGroup;
use App\Repository\UserGroupRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @property UserRepository $repository
 */
class UserService extends AbstractService
{
    public function registrationProcess(FormInterface $form, Request $request, UserPasswordHasherInterface $userPasswordHasher): array
    {
        $user = new User($this->entityManager);
        $form->setData($user);
        $form->handleRequest($request);

        $registered = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setEmail($form->get('email')->getData());
            $user->setFullName($form->get('fullName')->getData());
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $registered = true;
        }

        return [$registered, $form];
    }

    public function loginProcess(AuthenticationUtils $authenticationUtils): array
    {
        return [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername()
        ];
    }

    public function passwordChangeProcess(FormInterface $form, Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher): array
    {
        $form->handleRequest($request);

        $changed = false;

        if ($form->isSubmitted() && $form->isValid()) {
            if ($userPasswordHasher->isPasswordValid($user, $form->get('passwordOld')->getData())) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('passwordNew')->getData()
                    )
                );
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $changed = true;
            } else {
                $form->addError(new FormError('Неверный пароль'));
            }
        }
        return [$changed, $form];
    }

    public function getUser(int $id)
    {
        return $this->repository->find($id);
    }
}