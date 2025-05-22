<?php

namespace App\Form;

use App\Entity\DetailHeures;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Range;

class AjoutServiceType extends AbstractType
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
            ->add('operation', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir une opération.']),
                    new Range([
                        'min' => 0,
                        'max' => 999,
                        'notInRangeMessage' => 'L\'opération doit être comprise entre {{ min }} et {{ max }}.',
                    ]),
                ],
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
