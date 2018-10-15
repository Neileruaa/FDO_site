<?php

namespace App\Form;

use App\Entity\Danseur;
use App\Entity\Equipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Categorie')
            ->add('numero_dossard')
            ->add('danseurs', EntityType::class, [
            	'class'=> Danseur::class,
            	'choice_label'=> 'name',
            	'placeholder'=>'Qui dans cette équipe ?',
	            'expanded'=>true,
	            'multiple'=>true,
	            'by_reference'=>false
            ])
	        ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
