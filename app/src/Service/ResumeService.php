<?php

namespace App\Service;

use App\Entity\Config;
use App\Entity\Freelancer;
use App\Entity\Resume;
use App\Entity\User;
use App\Repository\ConfigRepository;
use App\Repository\ResumeRepository;
use Doctrine\ORM\Query;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @property ResumeRepository $repository
 */
class ResumeService extends AbstractService
{
    public function resumeListQuery(User|UserInterface $user, ?string $title = null, ?int $status = null): Query
    {
        $qb = $this->repository->createQueryBuilder('r')->orderBy('r.created_at', 'DESC');
        $conditions = $qb->expr()->andX($qb->expr()->eq('r.freelancer_id',$user->getFreelancer()->getId()));
        if ($title) $conditions->add($qb->expr()->like('r.title', "'%$title%'"));
        if ($status) $conditions->add($qb->expr()->eq('r.status', $status));
        $qb->where($conditions);
        return $qb->getQuery();
    }

    public function allowNew(User|UserInterface $user): bool
    {
        /** @var ConfigRepository $configRepo */
        $configRepo = $this->entityManager->getRepository(Config::class);
        return $user->getFreelancer()->getResumes()->count() < $configRepo->getMaxResumeAmount();
    }

    public function creationProcess(FormInterface $form, Request $request, Freelancer $author): array
    {
        $form->handleRequest($request);
        $created = false;

        if ($form->isSubmitted() && $form->isValid()) {
            if (count(explode(',', $form->get('tagsText')->getData())) < 3) {
                $form->addError(new FormError('Укажите хотя бы 3 тэга'));
            } else {
                $resume = $this->loadValues($form, $author);
                $this->entityManager->persist($resume);
                $this->entityManager->flush();
                $created = true;
            }
        }
        return [$created, $form, $resume ?? null];
    }

    public function updateProcess(FormInterface $form, Request $request, Resume $resume): array
    {
        $form->handleRequest($request);
        $updated = false;

        if ($form->isSubmitted() && $form->isValid()) {
            if (count(explode(',', $form->get('tagsText')->getData())) < 3) {
                $form->addError(new FormError('Укажите хотя бы 3 тэга'));
            } else {
                $resume = $this->loadValues($form, $resume->getAuthor(), $resume);
                $this->entityManager->persist($resume);
                $this->entityManager->flush();
                $updated = true;
            }
        }
        return [$updated, $form, $resume];
    }

    public function findById(int $id): ?Resume
    {
        return $this->repository->find($id);
    }

    private function loadValues(FormInterface $form, Freelancer $author, Resume $resume = new Resume()): Resume
    {
        $resume->setAuthor($author);

        $resume->setTitle($form->get('title')->getData());

        $resume->setPrice($form->get('price')->getData());

        $resume->setExperience($form->get('experience')->getData());

        $tagsArr = explode(',', $form->get('tagsText')->getData());
        foreach ($tagsArr as $i => $value) $tagsArr[$i] = trim($value);
        $resume->setTags($tagsArr);

        $resume->setDescription($form->get('description')->getData());

        $resume->setStatus($form->get('status')->getData());

        $contactForm = [];
        if ($form->get('email')->getData()) $contactForm['email'] = $form->get('email')->getData();
        if ($form->get('phone')->getData()) $contactForm['phone'] = $form->get('phone')->getData();
        if ($form->get('telegram')->getData()) $contactForm['telegram'] = $form->get('telegram')->getData();

        $resume->setContactForm(!empty($contactForm) ? $contactForm : $author->getDefaultContactForm());
        return $resume;
    }
}