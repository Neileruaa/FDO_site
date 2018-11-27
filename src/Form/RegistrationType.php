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
                'label'=>'Nom du club',
                'placeholder'=>'entrez le nom du club'
            ))
            ->add('nameClubOwner',TextType::class, array(
                'label'=>'Nom du gérant',
                'placeholder'=>'entrez le nom du gerant du club'
            ))
            ->add('villeClub',TextType::class, array(
                'label'=>"Ville",
                'placeholder'=>'entrez votre ville'
            ))
            ->add('codePostalClub',TextType::class, array(
                'label'=>'Code postal',
                'placeholder'=>'entrez le codePostal de votre ville'
            ))
            ->add('password',PasswordType::class, array(
                'label'=>'Mot de passe',
                'placeholder'=>'mot de passe'
            ))
            ->add('confirmPassword',PasswordType::class, array(
                'label'=>'Confirmer le mot de passe',
                'placeholder'=>'confirmer votre mot de passe'
            ))
            ->add('emailClub', EmailType::class, array(
                'label'=>'Adresse e-mail',
                'placeholder'=>'entrez votre mail'
            ))
            ->add('phoneClub',TextType::class, array(
                'label'=>'Numéro de téléphone',
                'placeholder'=>'entrez votre numero de telephone'
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
