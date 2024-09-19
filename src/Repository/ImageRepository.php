<?php

namespace App\Repository;

use App\Document\Ad\Image;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Exception;

class ImageRepository extends DocumentRepository
{
    public function remove(Image $image): bool
    {
        try {
            unlink($image->getLocation());
            // rmdir($image->getLocation() . "/..");
            self::getDocumentManager()->remove($image);
            self::getDocumentManager()->flush();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}