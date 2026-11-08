<?php

declare(strict_types=1);

namespace App\Form\Apad;

use App\Dto\Apad\Date;
use App\Enum\Apad\MonthEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('day', ChoiceType::class, [
                'label' => 'Jour',
                'required' => true,
                'choices' => array_combine(range(1, 31), range(1, 31)),
                'label_attr' => ['class' => 'd-none'],
            ])
            ->add('month', EnumType::class, [
                'label' => 'Mois',
                'required' => true,
                'class' => MonthEnum::class,
                'expanded' => false,
                'multiple' => false,
                'label_attr' => ['class' => 'd-none'],
            ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Date::class,
            'csrf_protection' => false,
            'method' => 'get',
            'translation_domain' => false,
            'attr' => [
                'novalidate' => true,
                'data-turbo' => 'false',
            ],
        ]);
    }
}
