<?php

namespace App\Repository;

use App\Document\Image;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ImageRepository extends DocumentRepository
{
    public function remove(Image $image): void
    {
        try {
            unlink($image->getLocation());
            self::getDocumentManager()->remove($image);
            self::getDocumentManager()->flush();
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}