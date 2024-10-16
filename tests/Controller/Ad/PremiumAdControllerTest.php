<?php

namespace App\Tests\Controller\Ad;

use App\Controller\Ad\PremiumAdController;
use App\Tests\BaseTestController;
use App\Tests\DocumentManagerAwareTrait;
use PHPUnit\Framework\TestCase;

class PremiumAdControllerTest extends BaseTestController
{
    use DocumentManagerAwareTrait;

    public function testActivatePremium(): void
    {
        // $ad->setPremium($duration->value);
    }

    public function testDeactivatePremium(): void
    {
        // $ad->removePremium();
    }
}
