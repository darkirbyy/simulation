<?php

declare(strict_types=1);

namespace App\Form\Necesse;

use App\Entity\Necesse\MinMax;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MinMaxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('min', IntegerType::class, [
                'label' => 'Minimum',
                'required' => true,
                'row_attr' => [
                    'class' => 'flex-fill',
                ],
            ])
            ->add('max', IntegerType::class, [
                'label' => 'Maximum',
                'required' => true,
                'row_attr' => [
                    'class' => 'flex-fill',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MinMax::class,
            'attr' => [
                'class' => 'd-flex gap-2',
            ],
        ]);
    }
}
