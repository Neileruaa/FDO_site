<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Competition;
use App\Entity\Dance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateCompetition', DateType::class, array(
                'label'=>'Date de la compétition'
            ))
            ->add('city', TextType::class, array(
                'label'=>"Ville"
            ))
            ->add('address', TextType::class, array(
                'label'=>'Adresse'
            ))
            ->add('postalCode', NumberType::class, array(
                'label'=>'Code postal'
            ))
            ->add('dances', EntityType::class, [
                'label'=>'Liste des danses',
                'class'=> Dance::class,
                'choice_label'=>'nameDance',
                'required'=>true,
                'expanded'=>false,
                'multiple'=>true,
                'by_reference'=>false
            ])
            ->add('clubOrganizer', EntityType::class, [
                'label'=>'Club organisateur',
                'class'=> Club::class,
                'choice_label'=>'username',
                'required'=>true,
                'expanded'=>false,
                'multiple'=>false,
                'by_reference'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Competition::class,
        ]);
    }
}
