<?php

namespace App\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class AdImageFileManager
{
    private string $adImagesPath;
    private LoggerInterface $logger;

    public function __construct(
        string          $adImagesPath,
        LoggerInterface $logger
    )
    {
        $this->adImagesPath = $adImagesPath;
        $this->logger = $logger;
    }

    public function moveUploadedFile(UploadedFile $file, string $adId, string $imageId): File
    {
        $dir = $this->resolveDir($adId);
        try {
            mkdir($dir, 0775, true);
        } catch (Exception $e) {
            $this->logger->info($this::class . ": This directory already exists.");
        }

        return $file->move($dir, $imageId . '.' . $file->guessExtension());
    }

    private function resolveDir(string $id): string
    {
        return __DIR__ . "/../.." . $this->adImagesPath . DIRECTORY_SEPARATOR . $id;
    }

    public function removeDir(string $id): void
    {
        rmdir($this->resolveDir($id));
    }
}