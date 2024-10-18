<?php

declare(strict_types=1);

namespace App\Form;

use App\Model\PromotionRequest;
use App\Util\PremiumDuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('duration', EnumType::class, ['class' => PremiumDuration::class]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PromotionRequest::class,
            'csrf_protection' => false
        ]);
    }
}
