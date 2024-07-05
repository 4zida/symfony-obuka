<?php

namespace App\Repository;

use App\Util\UnixHelper;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class AdRepository extends DocumentRepository
{
    /**
     * @throws MongoDBException
     */
    public function findLastMonthsAds(): array
    {
        $month = UnixHelper::MONTH;
        $ads = $this->createQueryBuilder()
            ->field('unixTime')->lte(time() - $month)
            ->field('unixTime')->gte(time() - $month*2)
            ->getQuery()
            ->execute();
        return $this->toArray($ads);
    }

    /**
     * @throws MongoDBException
     */
    public function findByUser(string $userId): array
    {
        $ads = $this->createQueryBuilder()
            ->field('userId')->equals($userId)
            ->getQuery()
            ->execute();
        return $this->toArray($ads);
    }

    public function findByCompany(\App\Entity\Company $company)
    {
        $ads = $this->createQueryBuilder()
            ->field('companyId')->equals($company->getId())
            ->getQuery()
            ->execute();
        return $this->toArray($ads);
    }

    protected function toArray($data): array
    {
        $array = [];
        foreach ($data as $datum){
            $array[] = $datum;
        }
        return $array;
    }
}