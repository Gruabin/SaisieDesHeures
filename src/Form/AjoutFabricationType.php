<?php

namespace App\Form;

use App\Entity\DetailHeures;
use App\Entity\TacheSpecifique;
use App\Repository\TacheSpecifiqueRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Range;

class AjoutFabricationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ordre', TextType::class, [
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'maxlength' => 5,
                    'pattern' => '\d{5}',
                    'placeholder' => '12345',
                    'class' => 'w-20',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir la partie numérique.']),
                    new Regex([
                        'pattern' => '/^\d{5}$/',
                        'message' => 'La partie numérique doit contenir exactement 5 chiffres.',
                    ]),
                ],
            ])
            ->add('operation', IntegerType::class, [
                'required' => true,
                'attr' => ['required' => true],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir une opération.']),
                    new Range([
                        'min' => 0,
                        'max' => 999,
                        'notInRangeMessage' => 'L\'opération doit être comprise entre {{ min }} et {{ max }}.',
                    ]),
                ],
            ])
            ->add('tacheSpecifique', EntityType::class, [
                'class' => TacheSpecifique::class,
                'choice_label' => 'name',
                'required' => false,
                'attr' => ['required' => false],
                'query_builder' => function (TacheSpecifiqueRepository $repo) use ($options) {
                    $prefix = substr($options['site'] ?? '', 0, 2);
                    return $repo->createQueryBuilder('t')
                                ->where('t.id LIKE :prefix')
                                ->setParameter('prefix', $prefix . '%')
                                ->orderBy('t.id', 'ASC');
                },
            ])
            ->add('temps_main_oeuvre', NumberType::class, [
                'required' => true,
                'html5' => true,
                'attr' => [
                    'required' => true,
                    'max' => 12,
                    'step' => 0.1,
                    'type' => 'number',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner un temps.']),
                    new Range([
                        'min' => 0.1,
                        'max' => 12,
                        'notInRangeMessage' => 'Le temps doit être compris entre {{ min }} et {{ max }} heures.',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetailHeures::class,
            'site' => null,
        ]);
    }
}
