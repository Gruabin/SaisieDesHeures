<?php

namespace App\Form;

use App\Entity\DetailHeures;
use App\Entity\Tache;
use App\Entity\Activite;
use App\Repository\TacheRepository;
use App\Form\Activite\ActiviteExist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class AjoutProjetType extends AbstractType
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
                    'class' => 'w-20',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir la partie numérique.']),
                    new Length([
                        'min' => 5,
                        'max' => 5,
                        'exactMessage' => 'La partie numérique doit contenir exactement 5 chiffres.',
                    ]),
                ],
            ])
            ->add('tache', EntityType::class, [
                'class' => Tache::class,
                'choice_label' => 'name',
                'placeholder' => '-- Sélectionner une tâche --',
                'required' => true,
                'attr' => ['required' => true],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une tâche.']),
                ],
                'query_builder' => fn(TacheRepository $repo) => $repo->createQueryBuilder('t')
                    ->where('t.id < 100'),
            ])
            ->add('activite', TextType::class, [
                'mapped' => false,
                'required' => true,
                'attr' => ['required' => true, 'id' => 'activiteInput'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir une activité.']),
                    new ActiviteExist(),
                ],
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
