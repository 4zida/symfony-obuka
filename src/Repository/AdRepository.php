<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class AdRepository extends DocumentRepository
{
    public function findLastMonthsAds(): array
    {
        $month = 2592000;
        $ads = $this->findAll();
        $array = [];
        foreach ($ads as $ad)
        {
            if (
                strtotime($ad->getDateTime()) < (time() - $month) &&
                strtotime($ad->getDateTime()) > time() - ($month * 2)
            )
            {
                $array[] = $ad;
            }
        }
        return $array;
    }
}