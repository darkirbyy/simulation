<?php

declare(strict_types=1);

namespace App\Form\Necesse;

use App\Entity\Necesse\Run;
use App\Entity\Necesse\World;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RunType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('world', EntityType::class, [
                'label' => 'Monde',
                'required' => true,
                'class' => World::class,
                'choice_label' => 'label',
                'row_attr' => [
                    'class' => 'app-necesse-w-33 ',
                ],
            ])
            ->add('time', IntegerType::class, [
                'label' => 'Durée (en secondes)',
                'required' => true,
                'row_attr' => [
                    'class' => 'app-necesse-w-33 ',
                ],
            ])
            ->add('seed', IntegerType::class, [
                'label' => 'Graine (entre 0 et 2<sup>32</sup>)',
                'label_html' => true,
                'required' => true,
                'row_attr' => [
                    'class' => 'app-necesse-w-33 ',
                ],
            ])
            ->add('initialHen', IntegerType::class, [
                'label' => 'Poules',
                'required' => true,
                'row_attr' => [
                    'class' => 'app-necesse-w-33 ',
                ],
            ])
            ->add('initialRooster', IntegerType::class, [
                'label' => 'Coqs',
                'required' => true,
                'row_attr' => [
                    'class' => 'app-necesse-w-33 ',
                ],
            ])
            ->add('limitHen', IntegerType::class, [
                'label' => 'Poules',
                'required' => true,
                'row_attr' => [
                    'class' => 'app-necesse-w-33 ',
                ],
            ])
            ->add('limitRooster', IntegerType::class, [
                'label' => 'Coqs',
                'required' => true,
                'row_attr' => [
                    'class' => 'app-necesse-w-33 ',
                ],
            ])
            ->add('limitNest', IntegerType::class, [
                'label' => 'Nids',
                'required' => true,
                'row_attr' => [
                    'class' => 'app-necesse-w-33 ',
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Lancer la simulation',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Run::class,
            'translation_domain' => false,
            'attr' => [
                'novalidate' => true,
            ],
        ]);
    }
}
