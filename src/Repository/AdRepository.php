<?php

namespace App\Repository;

use App\Util\UnixHelper;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class AdRepository extends DocumentRepository
{
    public function findLastMonthsAds(): array
    {
        $month = UnixHelper::MONTH;
        $ads = $this->createQueryBuilder()
            ->field('unixTime')->lte(time() - $month)
            ->field('unixTime')->gte(time() - $month*2)
            ->getQuery()
            ->execute();
        $array = [];
        foreach ($ads as $ad)
        {
            $array[] = $ad;
        }
        return $array;
    }
}