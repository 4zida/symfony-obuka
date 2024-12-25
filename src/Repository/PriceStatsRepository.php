<?php

namespace App\Repository;

use App\Document\Ad;
use App\Document\AdFor;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Exception;

class PriceStatsRepository extends DocumentRepository
{
    /**
     * @throws Exception
     * @return array{_id: array{placeId: string, type: string}, prices: int[]}
     */
    public function getAggregatedPriceStats(): array
    {
        $builder = $this->getDocumentManager()->createAggregationBuilder(Ad::class);
        $result = $builder
            ->match()
            ->field('for')->equals(AdFor::SALE)
            ->group()
            ->field('_id')->expression(
                $builder->expr()
                    ->field('placeId')->expression('$placeId')
                    ->field('type')->expression('$type')
            )
            ->field('prices')->push('$price')
            ->getAggregation();

        return $result->getIterator()->toArray();
    }
}