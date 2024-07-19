<?php

namespace App\Command;

use App\Document\Ad;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:get-last-months-ads',
    description: 'Gets the months older than 30 days, but younger than 60 days and puts them in a CSV file',
)]
class GetLastMonthsAdsCommand extends Command
{
    public function __construct(
        private readonly DocumentManager $documentManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $ads = $this->documentManager->getRepository(Ad::class)->findBetween(new DateTimeImmutable("-2 months"), new DateTimeImmutable("-1 month"));

        $filename = 'ads.csv';
        $file = fopen($filename, 'w');

        if ($file === false) {
            $io->error('Unable to open file!');
            return Command::FAILURE;
        }

        fputcsv($file, [
            "Name",
            "Description",
            "Url",
            "Created at",
        ]);

        foreach ($ads as $ad) {
            fputcsv($file, [
                $ad->getName(),
                $ad->getDescription(),
                $ad->getUrl(),
                $ad->getCreatedAt()->format('Y-m-d H:i:s')
            ]);
        }

        fclose($file);

        $io->success('Generated file');

        return Command::SUCCESS;
    }
}
