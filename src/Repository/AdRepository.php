<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class AdRepository extends DocumentRepository
{
    /**
     * @throws MongoDBException
     */
    public function findBetween(\DateTimeImmutable $after, \DateTimeImmutable $before): array
    {
        if ($before < $after) {
            $temp = $before;
            $before = $after;
            $after = $temp;
        }

        $ads = $this->createQueryBuilder()
            ->field('createdAt')->lte($before)
            ->field('createdAt')->gte($after)
            ->getQuery()
            ->execute();
        return $this->toArray($ads);
    }

    /**
     * @throws MongoDBException
     */
    public function findByUser(User $user): array
    {
        $ads = $this->createQueryBuilder()
            ->field('userId')->equals($user->getId())
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