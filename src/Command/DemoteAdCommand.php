<?php

declare(strict_types=1);

namespace App\Command;

use App\Document\Ad;
use App\Service\PromotionService;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:demote_ads',
    description: 'Iterates through all ads and removes premium from the ones that have ended.'
)]
class DemoteAdCommand extends Command
{
    public function __construct(
        private readonly DocumentManager $documentManager,
        private readonly PromotionService $promotionService
    )
    {
        parent::__construct();
    }

    /**
     * @throws MongoDBException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = 0;
        $io = new SymfonyStyle($input, $output);
        $now = new DateTimeImmutable();

        foreach ($this->documentManager->getRepository(Ad::class)->findAll() as $ad) {
            if (!is_null($ad->getPremiumExpiresAt()) && $now >= $ad->getPremiumExpiresAt()) {
                $this->promotionService->demote($ad);
                $count++;
            }
        }

        $io->success(sprintf('%s ad(s) were demoted.', $count));
        return Command::SUCCESS;
    }
}
