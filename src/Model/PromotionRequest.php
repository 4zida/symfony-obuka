<?php

namespace App\Model;

use App\Util\PremiumDuration;
use Symfony\Component\Validator\Constraints as Assert;

class PromotionRequest
{
    #[Assert\NotBlank]
    public ?PremiumDuration $duration = null;

    public function getDuration(): ?PremiumDuration
    {
        return $this->duration;
    }

    public function setDuration(?PremiumDuration $duration): self
    {
        $this->duration = $duration;
        return $this;
    }
}