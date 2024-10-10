<?php

namespace App\Repository;

use App\Document\Ad;
use App\Entity\Company;
use App\Entity\User;
use App\Search\Filter\AdSearchFilter;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use JetBrains\PhpStorm\Deprecated;
use Nebkam\OdmSearchParam\SearchParamParser;
use ReflectionException;

class AdRepository extends DocumentRepository
{

    /**
     * @throws MongoDBException
     */
    public function findBetween(DateTimeImmutable $after, DateTimeImmutable $before): array
    {
        return $this->createQueryBuilder()
            ->field('createdAt')->lte($before)
            ->field('createdAt')->gte($after)
            ->getQuery()
            ->execute()
            ->toArray();
    }


    /**
     * @throws MongoDBException
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder()
            ->field('userId')->equals($user->getId())
            ->getQuery()
            ->execute()
            ->toArray();
    }

    /**
     * @throws MongoDBException
     */
    public function findByCompany(Company $company): array
    {
        return $this->createQueryBuilder()
            ->field('companyId')->equals($company->getId())
            ->getQuery()
            ->execute()
            ->toArray();
    }


    /**
     * @throws MongoDBException
     */
    #[Deprecated]
    public function findByAddress(string $address): array
    {
        return $this->createQueryBuilder()
            ->field('address')->equals($address)
            ->getQuery()
            ->execute()
            ->toArray();
    }


    /**
     * @throws MongoDBException
     */
    #[Deprecated]
    public function findByFloor(int $floor): array
    {
        return $this->createQueryBuilder()
            ->field('floor')->equals($floor)
            ->getQuery()
            ->execute()
            ->toArray();
    }

    #[Deprecated]
    protected function toArray(Iterator|array $data): array
    {
        $array = [];
        foreach ($data as $datum) {
            $array[] = $datum;
        }
        return $array;
    }


    /**
     * @throws MongoDBException
     * @throws ReflectionException
     */
    public function search(AdSearchFilter $filter): array
    {
        $builder = $this->createQueryBuilder();
        SearchParamParser::parse($filter, $builder);

        return $builder->getQuery()->execute()->toArray();
    }

    public function remove(Ad $ad): void
    {
        $this->getDocumentManager()->remove($ad);
    }
}