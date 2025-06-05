<?php

namespace App\Form;

use App\Entity\DetailHeures;
use App\Entity\Tache;
use App\Entity\CentreDeCharge;
use App\Repository\TacheRepository;
use App\Repository\CentreDeChargeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class AjoutGeneraleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tache', EntityType::class, ['class' => Tache::class,
                'choice_label' => 'name',
                'placeholder' => '-- Sélectionner une tâche --',
                'required' => true,
                'attr' => ['required' => true],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une tâche.']),
                ],
                'query_builder' => fn(TacheRepository $repo) => $repo->createQueryBuilder('t')
                            ->where('t.id >= 100'),
            ])
            ->add('centre_de_charge', EntityType::class, [
                'class' => CentreDeCharge::class,
                'choice_label' => 'name',
                'placeholder' => '-- Sélectionner une charge --',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une charge.']),
                ],
                'query_builder' => function (CentreDeChargeRepository $repo) use ($options) {
                    $prefix = $options['site'] ?? '';
                    return $repo->createQueryBuilder('c')
                                ->where('c.id LIKE :prefix')
                                ->setParameter('prefix', $prefix . '%')
                                ->orderBy('c.id', 'ASC');
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
