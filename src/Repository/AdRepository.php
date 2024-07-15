<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class AdRepository extends DocumentRepository
{
    /**
     * @throws MongoDBException
     */
    public function findBetween(string|int $before, string|int $after): array
    {
        if(!is_int($before)) {
            $before = strtotime($before);
        }
        if(!is_int($after)) {
            $after = strtotime($after);
        }

        if ($before > $after) {
            $temp = $before;
            $before = $after;
            $after = $temp;
        }

        $ads = $this->createQueryBuilder()
            ->field('unixTime')->gte($before)
            ->field('unixTime')->lte($after)
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

    /**
     * @throws MongoDBException
     */
    public function findByCompany(Company $company): array
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