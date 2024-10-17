<?php

declare(strict_types=1);

namespace App\Controller\Ad;

use App\Document\Ad;
use App\Document\Image;
use App\Form\AdImageUploadType;
use App\Model\AdImageUpload;
use App\Service\AdImageManager;
use App\Util\ContextGroup;
use App\Util\CustomRequirement;
use App\Util\ResponseMessage;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Nebkam\SymfonyTraits\ControllerTrait;
use Nebkam\SymfonyTraits\FormTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImageController extends AbstractController
{
    use ControllerTrait;
    use FormTrait;

    public function __construct(
        private readonly DocumentManager $documentManager,
        private readonly AdImageManager  $adImageManager,
    )
    {
    }

    /**
     * @throws MongoDBException
     */
    #[Route('/api/ad/{id}/images', requirements: ['id' => CustomRequirement::OBJECT_ID], methods: Request::METHOD_POST)]
    public function upload(Ad $ad, Request $request, AdImageManager $adImageManager): JsonResponse
    {
        $adImageUpload = new AdImageUpload($ad);
        $this->handleForm($request, $adImageUpload, AdImageUploadType::class);
        $adImageManager->upload($ad, $adImageUpload->image);

        return $this->jsonWithGroup($ad, ContextGroup::AD_COMPLETE_INFO, Response::HTTP_CREATED);
    }

    #[Route('/api/ad/images', methods: Request::METHOD_GET)]
    public function showAll(): JsonResponse
    {
        $result = $this->documentManager->getRepository(Image::class)->findAll();

        return $this->jsonWithGroup($result, ContextGroup::IMAGE_DETAILS);
    }

    #[Route('/api/image/{id}', requirements: ['id' => CustomRequirement::OBJECT_ID], methods: Request::METHOD_DELETE)]
    public function remove(Image $image): Response
    {
        $this->documentManager->getRepository(Image::class)->remove($image);

        return self::createOkResponse(ResponseMessage::IMAGE_REMOVED);
    }
}
