<?php

namespace App\Form;

use App\Entity\DetailHeures;
use App\Entity\TypeHeures;
use App\Entity\Tache;
use App\Entity\Activite;
use App\Entity\CentreDeCharge;
use App\Entity\Employe;
use App\Entity\TacheSpecifique;
use App\Entity\Statut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AjoutHeuresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('temps_main_oeuvre', NumberType::class, [
                'scale' => 2,
            ])
            ->add('type_heures', EntityType::class, [
                'class' => TypeHeures::class,
                'choice_label' => 'nomType',
            ])
            ->add('operation', IntegerType::class, [
                'required' => false,
            ])
            ->add('ordre', TextType::class, [
                'required' => false,
            ])
            ->add('tache', EntityType::class, [
                'class' => Tache::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('activite', EntityType::class, [
                'class' => Activite::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('centre_de_charge', EntityType::class, [
                'class' => CentreDeCharge::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'nom_employe',
            ])
            ->add('tacheSpecifique', EntityType::class, [
                'class' => TacheSpecifique::class,
                'choice_label' => 'name',
                'required' => false,
                'disabled' => false,
            ])
            ->add('statut', EntityType::class, [
                'class' => Statut::class,
                'choice_label' => 'libelle',
                'required' => false,
            ])
            ->add('motif_erreur', TextareaType::class, [
                'required' => false,
            ]);

        // Nettoyage des champs vides
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            foreach ($data as $key => $value) {
                if ($value === '') {
                    $data[$key] = null;
                }
            }
            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetailHeures::class,
        ]);
    }
}
