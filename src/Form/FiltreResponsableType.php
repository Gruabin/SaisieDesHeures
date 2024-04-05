<?php

namespace App\Form;

use App\Entity\CentreDeCharge;
use App\Entity\Employe;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreResponsableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('responsables', EntityType::class, [
                'class' => Employe::class,
                'query_builder' => function (EntityRepository $er) use ($options): QueryBuilder {
                    return $er->createQueryBuilder('r')
                        ->innerJoin('r.centre_de_charge', 'cc', 'WITH', 'cc.id = r.centre_de_charge')
                        ->andWhere('cc.responsable = r.id')
                        ->andWhere('r.id LIKE :codeSite')
                        ->andWhere('r.nom_employe != :utilisateur')
                        ->setParameter('codeSite', '%' . substr($options['user']->getId(), 0, 2) .'%')
                        ->setParameter('utilisateur', $options['user']->getNomEmploye())
                        ->orderBy('r.nom_employe')
                        ;
                },
                'choice_label' => 'nom_employe',
                'choice_value' => 'id',
                'label' => false,
                'mapped' => false,
            ])
            ->add('button', SubmitType::class, [
                'label' => "Changer d'utilisateur"
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