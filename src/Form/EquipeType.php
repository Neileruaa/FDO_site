<?php

namespace App\Form;

use App\Entity\Equipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            	'class'=> 'App\Entity\Danseur',
            	'choice_label'=> 'name',
            	'label'=>'Qui dans cette équipe ?',
	            'expanded'=>true,
	            'multiple'=>true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
