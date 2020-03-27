<?php

namespace App\Form;

use App\Entity\OptionForHelp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionForHelpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('choices')
            ->add('groceries')
            ->add('garbage')
            ->add('walkingDog')
            ->add('dryCleaning')
            ->add('deliverTakeAway')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OptionForHelp::class,
        ]);
    }
}
