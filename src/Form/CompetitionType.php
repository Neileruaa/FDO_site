<?php

namespace App\Form;


use App\Entity\Club;
use App\Entity\Competition;
use App\Entity\Dance;
use App\Entity\Place;
use App\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;

class CompetitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateCompetition', DateType::class)


            ->add('place', EntityType::class, array(
                        'class' => Place::class,
                        'choice_label' => 'cityPlace',
                        'expanded'=>false,
                        'multiple'=>false)
            )

            ->add('teams', EntityType::class, array(
                    'class' => Team::class,
                    'choice_label' => 'id',
                    'expanded'=>false,
                    'multiple'=>false)
            )

            ->add('dances', EntityType::class, array(
                    'class' => Dance::class,
                    'choice_label' => 'nameDance',
                    'expanded'=>false,
                    'multiple'=>true)
            )
            ->add('clubOrganizer', EntityType::class, array(
                    'class' => Club::class,
                    'choice_label' => 'Username',
                    'expanded'=>false,
                    'multiple'=>false)
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Competition::class,
        ]);
    }
}
