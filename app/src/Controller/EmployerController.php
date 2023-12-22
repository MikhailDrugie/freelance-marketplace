<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\EmployerService;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @property EmployerService $service
 */
class EmployerController extends BaseController
{
    #[Route('/profile/activate-employ', name: 'activate-employ')]
    public function activate(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getEmployer() && $user->getEmployer()->isActive())
            throw new AccessDeniedException('Вы уже активировали профиль заказчика');

        $form = $this->getDefaultContactFormForm();
        list($success, $form) = $this->service->activateProfile($form, $request, $user);
        if ($success) {
            $this->addFlash('success', 'Профиль заказчика успешно активирован');
            return $this->redirectToRoute('profile');
        }

        return $this->render('activate', [
            'contactFormForm' => $form->createView()
        ]);
    }

    public function deactivate(Request $request): Response
    {
        return $this->json(['status' => 'WIP']);
    }

    private function getDefaultContactFormForm(): FormInterface
    {
        $oldContactForm = $this->service->getContactForm($this->getUser());
        return $this->createFormBuilder($oldContactForm)
            ->add('email', EmailType::class, ['required' => false])
            ->add('phone', TelType::class, ['required' => false])
            ->add('telegram', TextType::class, ['required' => false])
            ->getForm();
    }
}