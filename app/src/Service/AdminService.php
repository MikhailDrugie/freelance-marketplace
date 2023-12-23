<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserGroup;
use App\Repository\UserRepository;
use Doctrine\ORM\Query;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @property UserRepository $repository
 */
class AdminService extends AbstractService
{
    public function userListQuery(?string $login = null, ?int $status = null): Query
    {
        $qb = $this->repository->createQueryBuilder('u')->orderBy('u.created_at', 'DESC');
        $conditions = $qb->expr()->andX();
        if ($login) $conditions->add($qb->expr()->like('u.login', "'%$login%'"));
        if ($status) $conditions->add($qb->expr()->eq('u.status', $status));
        if ($conditions->count() > 0) $qb->where($conditions);
        return $qb->getQuery();
    }

    public function userUpdateProcess(FormInterface $form, Request $request, User $user): array
    {
        $form->handleRequest($request);
        $updated = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->loadValues($form, $user);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $updated = true;
        }
        return [$updated, $form, $user];
    }

    public function findById(int $id): ?User
    {
        return $this->repository->find($id);
    }

    private function loadValues(FormInterface $form, ?User $user = null): User
    {
        $user = $user ?? new User($this->entityManager);
        $user->setLogin($form->get('login')->getData());
        $user->setEmail($form->get('email')->getData());
        $user->setFullName($form->get('full_name')->getData());
        $user->setStatus($form->get('status')->getData());
        $userGroup = $this->entityManager->getRepository(UserGroup::class)->find($form->get('userGroup')->getData());
        $user->setUserGroup($userGroup);
        return $user;
    }
}