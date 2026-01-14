<?php

declare(strict_types=1);

namespace App\Form\Necesse;

use App\Entity\Necesse\World;
use App\Enum\Necesse\ReplaceModeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Libellé',
                'required' => true,
            ])
            ->add('eggToChick', MinMaxType::class, [
                'label' => "Temps pour qu'un oeuf se transforme en poussin (en secondes)",
                'required' => true,
            ])
            ->add('chickToChicken', MinMaxType::class, [
                'label' => "Temps pour qu'un poussin se transforme en poulet (en secondes)",
                'required' => true,
            ])
            ->add('henToLay', MinMaxType::class, [
                'label' => "Temps pour qu'une poule ponde un oeuf (en secondes)",
                'required' => true,
            ])
            ->add('roosterToFertilize', MinMaxType::class, [
                'label' => "Temps pour qu'un coq fertilise une poule (en secondes)",
                'required' => true,
            ])
            ->add('eggToFemale', NumberType::class, [
                'label' => "Probabilité qu'un oeuf donne une femelle (entre 0 et 1)",
                'required' => true,
            ])
            ->add('replaceMode', EnumType::class, [
                'label' => 'Mode de remplacement quand un coq/une poule doit être tué(e)',
                'required' => true,
                'class' => ReplaceModeEnum::class,
                'expanded' => true,
                'multiple' => false,
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'row_attr' => [
                    'class' => 'text-end',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => World::class,
            'translation_domain' => false,
            'attr' => [
                'novalidate' => true,
            ],
        ]);
    }
}
