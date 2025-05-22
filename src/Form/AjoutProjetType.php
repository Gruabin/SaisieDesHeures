<?php

namespace App\Form;

use App\Entity\DetailHeures;
use App\Entity\Tache;
use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class AjoutProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ordre', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un numéro d\'ordre.']),
                    new Regex([
                        'pattern' => '/^[0-9A-Za-z]{2}[0-9]{5}$/',
                        'message' => 'L\'ordre doit contenir exactement 5 chiffres.',
                    ]),
                ],
            ])
            ->add('tache', EntityType::class, [
                'class' => Tache::class,
                'choice_label' => 'name',
                'placeholder' => '-- Sélectionner une tâche --',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une tâche.']),
                ]
            ])
            ->add('activite', EntityType::class, ['class' => Activite::class,
                'choice_label' => 'name',
                'placeholder' => '-- Sélectionner une activité --',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une activité.']),
                ]
            ])
            ->add('temps_main_oeuvre', NumberType::class, [
                'required' => true,
                'scale' => 2,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un temps de main d\'œuvre.']),
                    new Range([
                        'min' => 0,
                        'max' => 100,
                        'notInRangeMessage' => 'Le temps doit être compris entre {{ min }} et {{ max }} heures.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetailHeures::class,
        ]);
    }
}
