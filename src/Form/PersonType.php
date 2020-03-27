<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => "First Name / Prénom"
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last name / Nom de famille'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('phonenumber', TextType::class, [
                'label' => 'Phone number / téléphone'
            ])
            ->add('address', CollectionType::class, [
                'entry_type' => AddressType::class,
                'by_reference' => false,
                'label'=>false,
                'entry_options' => [
                                     'label' => false
                                   ],
                'allow_add' => true,
            ])
            ->add('Save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
