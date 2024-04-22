<?php

namespace App\Form;

use App\Entity\Employe;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreResponsableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('responsable', EntityType::class, [
                'class' => Employe::class,
                'query_builder' => fn (EntityRepository $er): QueryBuilder => $er->createQueryBuilder('r')
                ->distinct('r.nom_employe')
                ->innerJoin(\App\Entity\CentreDeCharge::class, 'cc', 'WITH', 'cc.responsable = r.id')
                ->andWhere('r.id LIKE :codeSite')
                ->setParameter('codeSite', '%'.substr($options['user']->getId(), 0, 2).'%')
                ->orderBy('r.nom_employe'),

                'choice_label' => 'nom_employe',
                'choice_value' => 'id',
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'data' => $options['data'],
            ])

            ->add('button', SubmitType::class, [
                'label' => 'Appliquer le filtre',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // $resolver->setDefaults([
        //     'data_class' => Employe::class,
        // ]);
        $resolver->setRequired('user');
        $resolver->setRequired('data');
    }
}
