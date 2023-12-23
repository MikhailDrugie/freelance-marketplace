<?php

namespace App\Form;

use App\Entity\Employer;
use App\Entity\Freelancer;
use App\Entity\User;
use App\Entity\UserGroup;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class, [
                'constraints' => [
                    new NotBlank( message: 'Логин обязателен для заполнения')
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank( message: 'Email обязателен для заполнения')
                ]
            ])
            ->add('full_name', TextType::class, [
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Активен' => User::STATUS_ACTIVE,
                    'Удален' => User::STATUS_DELETED
                ]
            ])
            ->add('userGroup', EntityType::class, [
                'class' => UserGroup::class,
                'choice_label' => 'label',
            ])
            ->add('freelancer', EntityType::class, [
                'class' => Freelancer::class,
                'choice_label' => 'id',
                'disabled' => true
            ])
//            ->add('freelancerStatus', ChoiceType::class, [
//                'mapped' => false,
//                'choices' => [
//                    'Активен' => Freelancer::STATUS_ACTIVE,
//                    'Неактивен' => Freelancer::STATUS_INACTIVE
//                ]
//            ])
            ->add('employer', EntityType::class, [
                'class' => Employer::class,
                'choice_label' => 'id',
                'disabled' => true
            ])
//            ->add('employerStatus', ChoiceType::class, [
//                'mapped' => false,
//                'choices' => [
//                    'Активен' => Employer::STATUS_ACTIVE,
//                    'Неактивен' => Employer::STATUS_INACTIVE
//                ]
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
