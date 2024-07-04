<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;

class BaseRestController extends AbstractFOSRestController
{

    protected function serializeJSON($object, $serializer, $format = 'json', $context = []): string
    {
        return $serializer->serialize($object, $format, $context);
    }

    protected function generateOkResponse(string $message = ''): Response
    {
        return new Response($message, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    protected function generateNotFoundResponse(string $message): Response
    {
        return new Response($message, Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
    }
}