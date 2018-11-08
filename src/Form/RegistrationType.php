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
            ->add('username',TextType::class)
            ->add('nameClubOwner',TextType::class)
            ->add('villeClub',TextType::class)
            ->add('codePostalClub',TextType::class)
            ->add('password',PasswordType::class)
            ->add('confirmPassword',PasswordType::class)
            ->add('emailClub', EmailType::class)
            ->add('phoneClub',TextType::class)
            //->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
