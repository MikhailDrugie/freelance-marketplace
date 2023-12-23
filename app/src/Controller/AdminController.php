<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Service\AdminService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property AdminService $service
 */
class AdminController extends BaseController
{
    #[Route('/admin', name: 'admin-panel')]
    public function index(): Response
    {
        return $this->redirectToRoute('user-list');
    }

    #[Route('/admin-logout', name: 'admin-logout')]
    public function logout(): Response
    {
        return (new Response('ok'))->setStatusCode(Response::HTTP_UNAUTHORIZED);
    }

    #[Route('/admin/users', name: 'user-list')]
    public function userList(Request $request): Response
    {
        $login = $request->query->getString('login');
        $encodedLogin = $login ? htmlspecialchars($login) : null;
        $status = $request->query->getInt('status');
        $page = $request->query->getInt('page', 1);

        $query = $this->service->userListQuery($encodedLogin, $status);
        $pagination = $this->paginationService->paginate($query, $page, 10);
        return $this->render('user_list', [
            'pagination' => $pagination,
            'login' => $encodedLogin,
            'status' => $status,
            'status_active' => User::STATUS_ACTIVE,
            'status_inactive' => User::STATUS_DELETED
        ]);
    }

    #[Route('/admin/users/edit', name: 'user-edit')]
    public function userEdit(Request $request): Response
    {
        $user = $this->service->findById($request->query->getInt('id'));
        if (!$user) throw new NotFoundHttpException('Пользователь не найден');
        $form = $this->createForm(UserFormType::class, $user);
        list($updated, $form, $user) = $this->service->userUpdateProcess($form, $request, $user);
        if ($updated) {
            $this->addFlash('success', 'Успешно сохранено');
            return $this->redirectToRoute('user-list');
        }

        return $this->render('user_view', [
            'userForm' => $form->createView(),
            'entity' => $user
        ]);
    }
}