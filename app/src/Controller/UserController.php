<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @property UserService $service
 */
class UserController extends BaseController
{
    #[Route('/profile', name: 'user-profile')]
    public function index(): Response
    {
        dd($this->getUser());
        return $this->json([
            'status' => 'WIP'
        ]);
    }

    #[Route('/register', name: 'user-register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if ($this->getUser()) return $this->redirectToRoute('user-profile');

        $form = $this->createForm(RegistrationFormType::class);
        list($registered, $form) = $this->service->registrationProcess($form, $request, $userPasswordHasher);
        if ($registered) {
            $this->addFlash('success', 'Successfully registered');
            return $this->redirectToRoute('user-index');
        }

        return $this->render('register', [
            'registrationForm' => $form->createView()
        ]);
    }

    #[Route('/login', name: 'user-login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) return $this->redirectToRoute('user-profile');

        $data = $this->service->loginProcess($authenticationUtils);
        return $this->render('login', $data);
    }

    #[Route('/logout', name: 'user-logout')]
    public function logout(): Response
    {
        throw new \LogicException('Logout error');
    }

    #[Route('/panel', name: 'user-panel')]
    public function panel(): Response
    {
        return $this->json([
            'status' => 'WIP'
        ]);
    }
}
