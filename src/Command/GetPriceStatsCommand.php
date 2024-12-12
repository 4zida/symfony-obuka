<?php

declare(strict_types=1);

namespace App\Command;

use App\Document\Ad;
use Doctrine\ODM\MongoDB\DocumentManager;
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->dm->getRepository(Ad::class)->createPriceStats();

        return Command::SUCCESS;
    }
}
