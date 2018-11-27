<?php

namespace App\Form;

use App\Entity\Club;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class, array(
                'label'=>'Nom du club'
            ))
            ->add('nameClubOwner',TextType::class, array(
                'label'=>'Nom du propriétaire du club'
            ))
            ->add('villeClub',TextType::class, array(
                'label'=>"Ville"
            ))
            ->add('codePostalClub',TextType::class, array(
                'label'=>'Code postal'
            ))
            ->add('password',PasswordType::class, array(
                'label'=>'Mot de passe'
            ))
            ->add('confirmPassword',PasswordType::class, array(
                'label'=>'Confirmer le mot de passe'
            ))
            ->add('emailClub', EmailType::class, array(
                'label'=>'Adresse e-mail'
            ))
            ->add('phoneClub',TextType::class, array(
                'label'=>'Numéro de téléphone'
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
