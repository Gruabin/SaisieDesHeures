<?php

namespace App\Form;

use App\Entity\DetailHeures;
use App\Entity\Tache;
use App\Entity\CentreDeCharge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class AjoutGeneraleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tache', EntityType::class, [
                'class' => Tache::class,
                'choice_label' => 'name',
                'placeholder' => '-- Sélectionner une tâche --',
                'required' => false,
                'attr' => ['required' => false],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une tâche.']),
                ]
            ])
            ->add('centre_de_charge', EntityType::class, ['class' => CentreDeCharge::class,
                'choice_label' => 'name',
                'placeholder' => '-- Sélectionner une charge --',
                'required' => false,
                'attr' => ['required' => false],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une charge.']),
                ]
            ])
            ->add('temps_main_oeuvre', NumberType::class, [
                'scale' => 2,
                'required' => false,
                'attr' => ['required' => false],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner un temps.']),
                    new Range([
                        'min' => 0.1,
                        'max' => 12,
                        'notInRangeMessage' => 'Le temps doit être compris entre 0.1 et 12 heures.',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetailHeures::class,
        ]);
    }
}
