<?php

namespace App\Form;

use App\Entity\Dancer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DancerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameDancer')
            ->add('firstNameDancer')
            ->add('dateBirthDancer', DateType::class,[
            	'widget'=>'single_text',
            ])
            ->add('emailDancer')
	        ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dancer::class,
        ]);
    }
}
