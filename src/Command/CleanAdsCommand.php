<?php

namespace App\Command;

use App\Document\Ad;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:clean-ads',
    description: 'Clears the database',
)]
class CleanAdsCommand extends Command
{
    public function __construct(
        private readonly DocumentManager $documentManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $ads = $this->documentManager->getRepository(Ad::class)->findAll();
        foreach ($ads as $ad)
        {
            $id = $ad->getId();
            $output->writeln(sprintf('Ad %s (%d) will be deleted', $ad->getName(), $ad->getId()));
            $this->documentManager->remove($ad);
            $output->writeln(sprintf('Ad %s (%d) has been deleted', $ad->getName(), $id));
        }

        $this->documentManager->flush();

        $io->success('The ads have been wiped.');

        return Command::SUCCESS;
    }
}
