<?php

namespace App\Form;

use App\Entity\Dancer;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('dances')
            ->add('dancers', EntityType::class, [
            	'class'=> Dancer::class,
	            'choice_label'=>function(Dancer $dancer){
            	    $fullname = strval($dancer->getNameDancer()." ". $dancer->getFirstNameDancer());
            	    return $fullname;
	            },
	            'expanded'=>true,
	            'multiple'=>true,
	            'by_reference'=>false
            ])
//            ->add('club')
//            ->add('competitions')
	        ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
