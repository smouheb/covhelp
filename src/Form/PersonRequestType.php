<?php

namespace App\Form;

use App\Entity\PersonRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => false
            ])
            ->add('lastname', TextType::class, [
                'label' => false
            ])
            ->add('email', EmailType::class, [
                'label' => false
            ])
            ->add('phone', TextType::class, [
                'label' => false
            ])
            ->add('language', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'French' => 'French',
                    'English' => 'English',
                    'Portuguese' => 'Portuguese'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => false
            ])
            ->add('send', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PersonRequest::class,
        ]);
    }
}
