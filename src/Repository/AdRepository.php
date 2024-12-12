<?php

namespace App\Repository;

use App\Document\Ad;
use App\Document\AdFor;
use App\Document\PriceStats;
use App\Entity\Company;
use App\Entity\User;
use App\Search\Filter\AdSearchFilter;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Exception;
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

        $builder->sort('premium', -1);

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @throws Exception
     */
    public function createPriceStats(): void
    {
        $aggregationResult = $this->getAggregatedPriceStats();

        if (empty($aggregationResult)) throw new Exception('There are no aggregated results');

        foreach ($aggregationResult as $item) {
            /** @var array{place: string, type: string} $id */
            $id = $item['_id'];

            /** @var array{prices: int[]} $price */
            $price = $item['prices'];

            if (empty($price)) throw new Exception('Price is empty');

            if (count($price) < 10) continue;

            $place = $id['placeId'];
            $type = $id['type'];

            sort($price);

            // minmax
            $max = max($price);
            $min = min($price);

            if (count($price) > 2) {
                $price = $this->filterExtremes($price);
            }

            // Average
            $avgPrice = $this->arrayAverage($price);

            $priceStats = new PriceStats();
            $priceStats->create($place, $type, $max, $min, $avgPrice);

            $this->getDocumentManager()->persist($priceStats);
        }

        $this->getDocumentManager()->flush();
    }

    /**
     * @throws Exception
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

    public function filterExtremes(array $price): array
    {
        return array_slice($price, 1, -1);
    }

    /**
     * @param array $price
     * @return float|int
     */
    public function arrayAverage(array $price): int|float
    {
        return array_sum($price) / count($price);
    }
}