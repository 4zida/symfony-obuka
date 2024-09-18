<?php

namespace App\Util;

use App\Document\Ad\Ad;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class AdImageUpload
{
    public Ad $ad;

    #[Assert\Image(
        mimeTypes: ['image/png', 'image/jpeg'],
        detectCorrupted: true,
        mimeTypesMessage: 'Only png, jpg and jpeg images are allowed.',
        corruptedMessage: 'This image is corrupted.'
    )]
    public UploadedFile $image;

    public function __construct(Ad $ad)
    {
        $this->ad = $ad;
    }
}