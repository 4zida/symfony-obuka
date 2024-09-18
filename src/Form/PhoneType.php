<?php

namespace App\Form;

use App\Entity\Phone;
use Misd\PhoneNumberBundle\Doctrine\DBAL\Types\PhoneNumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('full', PhoneNumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Phone::class,
            'csrf_protection' => false,
        ]);
    }
}