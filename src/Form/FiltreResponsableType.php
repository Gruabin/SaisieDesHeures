<?php

namespace App\Form;

use App\Entity\CentreDeCharge;
use App\Entity\Employe;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FiltreResponsableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('responsable', EntityType::class, [
            'class' => Employe::class,
            'query_builder' => function (EntityRepository $er) use ($options): QueryBuilder {
                return $er->createQueryBuilder('r')
                    ->distinct('r.nom_employe')
                    ->innerJoin('App\Entity\CentreDeCharge', 'cc', 'WITH', 'cc.responsable = r.id')
                    ->andWhere('r.id LIKE :codeSite')
                    ->setParameter('codeSite', '%' . substr($options['user']->getId(), 0, 2) .'%')
                    ->orderBy('r.nom_employe')
                    ; 
            },
            'choice_label' => 'nom_employe',
            'choice_value' => 'id',
            'label' => false,
            'multiple' => true,
            'mapped' => false,
        ])
        ->add('button', SubmitType::class, [
            'label' => "Appliquer le filtre"
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
        $resolver->setRequired('user');
    }
}
