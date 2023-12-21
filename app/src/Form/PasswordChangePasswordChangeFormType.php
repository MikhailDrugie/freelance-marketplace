<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContext;

class PasswordChangePasswordChangeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('passwordOld', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'current-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите текующий пароль'
                    ])
                ]
            ])
            ->add('passwordNew', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите новый пароль',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Пароль должен быть хотя бы из {{ limit }} символов',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('passwordConfirm', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Подтвердите новый пароль'
                    ]),
                    new Callback(['callback' => function ($value, ExecutionContext $ec) {
                        if ($ec->getRoot()['passwordNew']->getViewData() !== $value) {
                            $ec->addViolation("Пароли не совпадают");
                        }
                    }])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {}
}
