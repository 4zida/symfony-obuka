<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdImageFileManager
{
    private FileSystem $fs;
    private readonly string $adImagesPath;

    public function __construct(
        string $adImagesPath
    )
    {
        $this->adImagesPath = $adImagesPath;
        $this->fs = new Filesystem();
    }

    public function moveUploadedFile(UploadedFile $file, string $adId, string $imageId): File
    {
        $dir = $this->resolveDir($adId);
        $this->fs->mkdir($dir, 0775);

        return $file->move($dir, $imageId);
    }

    private function resolveDir(string $adId): string
    {
        return $this->adImagesPath.DIRECTORY_SEPARATOR.$adId;
    }
}