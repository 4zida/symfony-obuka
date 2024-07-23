<?php

namespace App\Service;

use App\Document\Ad;
use App\Search\Filter\AdFilter;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use ReflectionException;

readonly class AdSearchService
{
    public function __construct(
        private DocumentManager $dm,
    )
    {
    }

    /**
     * @throws ReflectionException
     * @throws MongoDBException
     */
    public function search(AdFilter $filter): array
    {
        return $this->dm->getRepository(Ad::class)->search($filter);
    }
}