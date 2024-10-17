<?php

namespace App\Model;

use App\Util\PremiumDuration;
use Symfony\Component\Validator\Constraints as Assert;

class PromotionRequest
{
    #[Assert\NotBlank]
    public ?int $duration;

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?PremiumDuration $duration): self
    {
        $this->duration = $duration->value;
        return $this;
    }
}