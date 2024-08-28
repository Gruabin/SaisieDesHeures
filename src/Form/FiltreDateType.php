<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'date', ChoiceType::class, [
                    'choices' => $options['dates'],
                    'label' => false,
                    'data' => $options['data'][0],
                ]
            )
            ->add(
                'button', SubmitType::class, [
                    'label' => 'Appliquer la date',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                // Configure your form options here
            ]
        );
        $resolver->setRequired('dates');
        $resolver->setRequired('data');
    }
}
