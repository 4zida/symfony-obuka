<?php

namespace App\Util;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class SerializerHelper
{
    const COMPANY_CONFIG = [
        AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
        AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 2,
        'groups' => [
            'list_company',
            'list_user'
        ]
    ];

    const USER_CONFIG = [
        AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
        AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 2,
        'groups' => [
            'list_company_no_users',
            'list_user_all'
        ]
    ];

}