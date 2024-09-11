<?php

namespace App\Form;

use App\Entity\Phone;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('full', TextType::class)
            ->add('national', TextType::class)
            ->add('international', TextType::class)
            ->add('isViber')
            ->add('countryCode', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Phone::class,
            'csrf_protection' => false,
        ]);
    }
}