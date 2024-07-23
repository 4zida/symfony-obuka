<?php

namespace App\Form;

use App\Search\Filter\AdFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdSearchFilterFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', AdFilter::class);
        $resolver->setDefault('csrf_protection', false);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('floorTo', IntegerType::class)
            ->add('floorFrom', IntegerType::class)
            ->add('m2To', IntegerType::class)
            ->add('m2From', IntegerType::class)
        ;
    }
}