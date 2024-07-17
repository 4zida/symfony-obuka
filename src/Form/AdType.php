<?php

namespace App\Form;

use App\Document\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('url', UrlType::class, ["default_protocol" => "http"])
            ->add('dateTime', TextType::class, ['empty_data' => date(DATE_ATOM)])
            ->add('unixTime', IntegerType::class, ['empty_data' => strtotime(date(DATE_ATOM))])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
            'csrf_protection' => false
        ]);
    }
}