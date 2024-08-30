<?php

namespace App\Repository;

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
     * @param DateTimeImmutable $after
     * @param DateTimeImmutable $before
     * @return array
     * @throws MongoDBException
     */
    public function findBetween(DateTimeImmutable $after, DateTimeImmutable $before): array
    {
        $ads = $this->createQueryBuilder()
            ->field('createdAt')->lte($before)
            ->field('createdAt')->gte($after)
            ->getQuery()
            ->execute();
        return $this->toArray($ads);
    }


    /**
     * @param User $user
     * @return array
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
     * @param Company $company
     * @return array
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


    /**
     * @param string $address
     * @return array
     * @throws MongoDBException
     */
    #[Deprecated]
    public function findByAddress(string $address): array
    {
        $ads = $this->createQueryBuilder()
            ->field('address')->equals($address)
            ->getQuery()
            ->execute();
        return $this->toArray($ads);
    }


    /**
     * @param int $floor
     * @return array
     * @throws MongoDBException
     */
    #[Deprecated]
    public function findByFloor(int $floor): array
    {
        $ads = $this->createQueryBuilder()
            ->field('floor')->equals($floor)
            ->getQuery()
            ->execute();
        return $this->toArray($ads);
    }

    /**
     * @param Iterator|array $data
     * @return array
     */
    protected function toArray(Iterator|array $data): array
    {
        $array = [];
        foreach ($data as $datum) {
            $array[] = $datum;
        }
        return $array;
    }


    /**
     * @param AdSearchFilter $filter
     * @return array
     * @throws MongoDBException
     * @throws ReflectionException
     */
    public function search(AdSearchFilter $filter): array
    {
        $builder = $this->createQueryBuilder();
        SearchParamParser::parse($filter, $builder);

        return $this->toArray($builder->getQuery()->execute());
    }
}