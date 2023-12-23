<?php

namespace App\Form;

use App\Entity\Freelancer;
use App\Entity\Resume;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class ResumeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('price', NumberType::class, [
                'scale' => 2,
                'attr' => [
                    'min' => 0,
                    'step' => 0.5
                ],
                'constraints' => [
                    new Positive(message: 'Цена должна быть больше 0')
                ]
            ])
            ->add('experience', ChoiceType::class, [
                'choices' => Resume::EXP_TEXT_MAP
            ])
            ->add('tagsText', TextType::class, [
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank(message: 'Поле обязательно для заполнения')
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'mapped' => false
            ])
            ->add('phone', TelType::class, [
                'required' => false,
                'mapped' => false
            ])
            ->add('telegram', TextType::class, [
                'required' => false,
                'mapped' => false
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Показывать' => Resume::STATUS_ACTIVE,
                    'Не показывать' => Resume::STATUS_INACTIVE
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resume::class,
        ]);
    }
}
