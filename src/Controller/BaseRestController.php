<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

class BaseRestController extends AbstractFOSRestController
{

    protected function serializeJSON($object, $format = 'json', $context = []): string
    {
        return Serializer::class->serialize($object, $format, $context);
    }

    protected function generateOkResponse(string $message = ''): Response
    {
        return new Response($message, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

}