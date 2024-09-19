<?php

namespace App\Form\Search;

use App\Document\AdFor;
use App\Search\Filter\AdSearchFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdSearchFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('floorTo', IntegerType::class)
            ->add('floorFrom', IntegerType::class)
            ->add('m2To', IntegerType::class)
            ->add('m2From', IntegerType::class)
            ->add('priceFrom', IntegerType::class)
            ->add('priceTo', IntegerType::class)
            ->add('address', TextType::class)
            ->add('for', EnumType::class, [
                'class' => AdFor::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', AdSearchFilter::class);
        $resolver->setDefault('csrf_protection', false);
    }
}