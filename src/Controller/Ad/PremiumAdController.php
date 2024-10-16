<?php

declare(strict_types=1);

namespace App\Controller\Ad;

use App\Document\Ad;
use App\Util\PremiumDuration;
use Nebkam\SymfonyTraits\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PremiumAdController extends AbstractController
{
    use ControllerTrait;

    #[Route(path: '/api/ad/activate_premium/{id}', name: 'activate_premium', methods: Request::METHOD_GET)]
    public function activatePremium(Ad $ad, PremiumDuration $duration): Response
    {
        $ad->setPremium($duration->value);

        return $this->createOkResponse();
    }

    #[Route(path: '/api/ad/deactivate_premium/{id}', name: 'deactivate_premium', methods: Request::METHOD_GET)]
    public function deactivatePremium(Ad $ad): Response
    {
        $ad->removePremium();

        return $this->createOkResponse();
    }
}
