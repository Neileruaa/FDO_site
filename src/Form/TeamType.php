<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Dance;
use App\Entity\Dancer;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dances', EntityType::class, array(
                'constraints'=>new IsTrue(array('message'=>'Une équipe doit au moins être reliée à une danse')),
                'label'=>'Liste des danses',
                'attr' => ['class' => 'form-check'],
                // query choices from this entity
                'class' => Dance::class,

                // use the User.username property as the visible option string
                'choice_label' => 'nameDance',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
                'required' => true,
                'expanded'=>true,
                'multiple'=>true,
                'by_reference'=>false
            ))
//            ->add('dances')
            ->add('dancers', EntityType::class, [
                'constraints'=>new IsTrue(array('message'=>'Une équipe doit au moins être composé d\'un danseur')),
                'label'=>'Liste des danseurs',
            	'class'=> Dancer::class,
	            'choice_label'=>function(Dancer $dancer){
            	    return strval($dancer->getNameDancer()." ". $dancer->getFirstNameDancer());
	            },
                'required'=>true,
	            'expanded'=>true,
	            'multiple'=>true,
	            'by_reference'=>false
            ])
//            ->add('club')
//            ->add('competitions')
	        ->add('save', SubmitType::class, [
	            'label'=>'Ajouter l\'équipe',
                'attr' => ['class' => 'btn btn-outline-success']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}