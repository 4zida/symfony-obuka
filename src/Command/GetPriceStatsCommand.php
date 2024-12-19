<?php

declare(strict_types=1);

namespace App\Command;

use App\Document\Ad;
use App\Document\AdFor;
use App\Document\Place;
use App\Document\PriceStats;
use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:get-price-stats', description: 'Get price stats for ads per place')]
class GetPriceStatsCommand extends Command
{
    public function __construct(
        private readonly DocumentManager $dm
    )
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $aggregationResult = $this->getAggregatedPriceStats();

        if (empty($aggregationResult)) throw new Exception('There are no aggregated results');

        foreach ($aggregationResult as $item) {
            $id = $item['_id'];

            $price = $item['prices'];

            if (empty($price)) throw new Exception('Price is empty');

            $placeId = $id['placeId'];
            $type = $id['type'];

            $place = $this->dm->getRepository(Place::class)->find($placeId);

            $max = max($price);
            $min = min($price);

            $price = $this->filterExtremes($price);

            if (count($price) < 10) continue;

            $avgPrice = $this->arrayAverage($price);

            $priceStats = PriceStats::create($place, $type, $max, $min, $avgPrice);
            $this->dm->persist($priceStats);
        }

        $this->dm->flush();

        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     * @return array{_id: array{placeId: string, type: string}, prices: int[]}
     */
    public function getAggregatedPriceStats(): array
    {
        $builder = $this->dm->createAggregationBuilder(Ad::class);
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

    public function filterExtremes(array $array): array
    {
        sort($array);
        return array_slice($array, 1, -1);
    }

    public function arrayAverage(array $array): int|float
    {
        return array_sum($array) / count($array);
    }
}
