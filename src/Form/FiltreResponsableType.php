<?php

namespace App\Form;

use App\Entity\CentreDeCharge;
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
                'class' => CentreDeCharge::class,
                'query_builder' => function (EntityRepository $er) use ($options): QueryBuilder {
                    return $er->createQueryBuilder('cc')
                        ->innerJoin('cc.responsable', 'r', 'WITH', 'cc.id = r.centre_de_charge')
                        ->andWhere('r.id LIKE :codeSite')
                        ->setParameter('codeSite', '%' . substr($options['user']->getId(), 0, 2) .'%')
                        ->orderBy('r.nom_employe')
                        ;
                },
                'choice_label' => 'responsable.nom_employe',
                'choice_value' => 'responsable.id',
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
            'data_class' => CentreDeCharge::class,
        ]);
        $resolver->setRequired('user');
    }
}