<?php

namespace App\Form;

use App\Util\AdImageUpload;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @See AdImageUpload
 */
class AdImageUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('image');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdImageUpload::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}