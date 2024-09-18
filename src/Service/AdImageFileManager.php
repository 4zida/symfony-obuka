<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class AdImageFileManager
{
    private string $adImagesPath;

    public function __construct(
        string $adImagesPath
    )
    {
        $this->adImagesPath = $adImagesPath;
    }

    public function moveUploadedFile(UploadedFile $file, string $adId, string $imageId): File
    {
        $dir = $this->resolveDir($adId);
        mkdir($dir, 0775, true);

        return $file->move($dir, $imageId.'.'.$file->guessExtension());
    }

    private function resolveDir(string $adId): string
    {
        return __DIR__."/../..".$this->adImagesPath.DIRECTORY_SEPARATOR.$adId;
    }
}