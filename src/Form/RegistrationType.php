<?php

namespace App\Form;

use App\Entity\Club;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('nameClubOwner')
            ->add('villeClub')
            ->add('codePostalClub')
            ->add('password')
            ->add('confirmPassword')
            ->add('emailClub')
            ->add('phoneClub')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
