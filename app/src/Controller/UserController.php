<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordChangePasswordChangeFormType;
use App\Form\RegistrationFormType;
use App\Service\UserService;
use Doctrine\DBAL\Exception\ServerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @property UserService $service
 */
class UserController extends BaseController
{
    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return Response
     * Форма регистрации и ее обработка
     */
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if ($this->getUser()) return $this->redirectToRoute('profile');

        $form = $this->createForm(RegistrationFormType::class);
        list($registered, $form) = $this->service->registrationProcess($form, $request, $userPasswordHasher);
        if ($registered) {
            $this->addFlash('success', 'Успешная регистрация');
            return $this->redirectToRoute('index');
        }

        return $this->render('register', [
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * Форма логина и ее обработка
     */
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) return $this->redirectToRoute('profile');

        $data = $this->service->loginProcess($authenticationUtils);
        return $this->render('login', $data);
    }

    /**
     * @return Response
     * Выход из аккаунта (route прописан в политику безопасности, logout происходит внутри framework'а)
     */
    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        throw new \LogicException('Logout error');
    }

    /**
     * @return Response
     * Основная странциа профиля
     */
    #[Route('/profile', name: 'profile')]
    public function index(): Response
    {
        return $this->render('profile', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @return Response
     * Страница с данными аккаунта и настройками
     */
    #[Route('/profile/settings', name: 'settings')]
    public function settings(): Response
    {
        return $this->render('settings', [
            'user' => $this->getUser()
        ]);
    }

    #[Route('/profile/password-change', name: 'password-change')]
    public function passwordChange(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(PasswordChangePasswordChangeFormType::class);
        list($changed, $form) = $this->service->passwordChangeProcess($form, $request, $user, $userPasswordHasher);
        if ($changed) {
            $this->addFlash('success', 'Пароль успешно изменен');
            return $this->redirectToRoute('settings');
        }

        return $this->render('password_change', [
            'passwordChangeForm' => $form->createView()
        ]);
    }

    #[Route('/ajax/delete-user', name: 'delete-user')]
    public function deleteUser(Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) throw new AccessDeniedException();

        $userId = $request->get('userId');
        if (!$this->getUser() || $this->getUser()->getId() != $userId) throw new AccessDeniedException();

        if (!$this->service->deleteUser($userId)) throw new \HttpException(message: "Ошибка удаления пользователя", code: 500);

        return $this->json([
            'status' => 'success'
        ]);
    }
}
