<?php

namespace App\Form;

use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class, array(
                'label'=>'Nom du club','attr' => array(
                    'placeholder' =>'entrez le nom du club'
                )
            ))
            ->add('nameClubOwner',TextType::class, array(
                'label'=>'Nom du gérant','attr' => array(
                    'placeholder' =>'entrez le nom du gérant du club'
                )
            ))
	        ->add('villeClub',TextType::class, array(
                'label'=>'Ville','attr' => array(
                    'placeholder' =>'entrez la ville du club'
                )
            ))
            ->add('codePostalClub',NumberType::class, array(
                'label'=>'Code postal','attr' => array(
                    'placeholder' =>'entrez le code postal du club'
                )
            ))
            ->add('phoneClub',TextType::class, array(
                'label'=>'Numéro de téléphone','attr' => array(
                    'placeholder' =>'entrez le numéro de téléphone du club'
                )
            ))
            ->add('emailClub',TextType::class, array(
                'label'=>'E-mail','attr' => array(
                    'placeholder' =>'entrez votre e-mail'
                )
            ))
	        ->add('password', PasswordType::class, array(
                'label'=>'Mot de passe','attr' => array(
                    'placeholder' =>'entrez votre mot de passe'
                )
            ))
	        ->add('confirmPassword', PasswordType::class, array(
                'label'=>'Confirmation','attr' => array(
                    'placeholder' =>'confirmer le mot de passe'
                )
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
