<?php

namespace App\Service;

use App\Document\Ad;
use App\Document\Image;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class AdImageManager
{
    public function __construct(
        private AdImageFileManager $fileManager,
        private DocumentManager    $documentManager
    )
    {
    }

    /**
     * @throws MongoDBException
     */
    public function remove(Image $image): void
    {
        $this->fileManager->removeDir($image->getId());
        $this->documentManager->remove($image);
        $this->documentManager->flush();
    }

    /**
     * @throws MongoDBException
     */
    public function upload(Ad $ad, UploadedFile $file): Ad
    {
        $image = new Image();
        $this->documentManager->persist($image);
        $file = $this->fileManager->moveUploadedFile($file, $ad->getId(), $image->getId());
        $image->populateFromFile($file);
        $ad->addImage($image);
        $this->documentManager->persist($image);
        $this->documentManager->flush();

        return $ad;
    }
}