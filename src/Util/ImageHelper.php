<?php

namespace App\Util;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ImageHelper
{
    /**
     * @param File $file File to get the dimensions from
     * @return int[] Returns an array of integers as [width, height]
     * @throws BadRequestHttpException If the file doesn't have a size
     */
    public static function getDimensions(File $file): array
    {
        $imageSize = getimagesize($file->getRealPath());
        if ($imageSize === false) {
            throw new BadRequestHttpException('Size not detected!');
        }
        $width = (int)$imageSize[0];
        $height = (int)$imageSize[1];

        return [$width, $height];
    }
}