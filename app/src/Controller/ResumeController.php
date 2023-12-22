<?php

namespace App\Controller;

use App\Entity\Resume;
use App\Entity\User;
use App\Form\ResumeFormType;
use App\Service\PaginationService;
use App\Service\ResumeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property ResumeService $service
 */
class ResumeController extends BaseController
{
    #[Route('/profile/resume', name: 'resume-list')]
    public function list(Request $request): Response
    {
        $title = $request->query->getString('title');
        $encodedTitle = $title ? htmlspecialchars($title) : null;
        $status = $request->query->getInt('status');
        $page = $request->query->getInt('page', 1);

        $query = $this->service->resumeListQuery($this->getUser(), $encodedTitle, $status);
        $pagination = $this->paginationService->paginate($query, $page, 10);
        return $this->render('list', [
            'pagination' => $pagination,
            'title' => $encodedTitle,
            'status' => $status,
            'status_active' => Resume::STATUS_ACTIVE,
            'status_inactive' => Resume::STATUS_INACTIVE
        ]);
    }

    #[Route('/profile/resume/create', name: 'resume-create')]
    public function create(Request $request): Response
    {
        if (!$this->service->allowNew($this->getUser())) {
            $this->addFlash('danger', 'Количество активных резюме превышает допустимое');
            return $this->redirectToRoute('resume-list');
        }

        $form = $this->createForm(ResumeFormType::class);
        list($created, $form, $resume) = $this->service->creationProcess($form, $request, $this->getUser()->getFreelancer());
        if ($created) {
            $this->addFlash('success', 'Успешно сохранено');
            return $this->redirectToRoute('resume-edit', ['id' => $resume->getId()]);
        }

        return $this->render('view', [
            'resumeForm' => $form->createView(),
            'action' => 'create',
            'entity' => null
        ]);
    }

    public function show()
    {

    }

    #[Route('/profile/resume/edit', name: 'resume-edit')]
    public function edit(Request $request): Response
    {
        $resume = $this->service->findById($request->query->getInt('id'));
        if (!$resume) throw new NotFoundHttpException('Резюме не найдено');
        $form = $this->createForm(ResumeFormType::class, $resume);
        list($updated, $form, $resume) = $this->service->updateProcess($form, $request, $resume);
        if ($updated) {
            $this->addFlash('success', 'Успешно сохранено');
            return $this->redirectToRoute('resume-edit', ['id' => $resume->getId()]);
        }

        return $this->render('view', [
            'resumeForm' => $form->createView(),
            'action' => 'update',
            'entity' => $resume
        ]);
    }

    public function delete()
    {

    }
}