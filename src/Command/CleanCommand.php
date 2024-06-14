<?php

namespace App\Command;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:clean',
    description: 'Add a short description for your command',
)]
class CleanCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
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
        $e = $this->entityManager;

        try {
            $e->createQueryBuilder()
                ->delete()
                ->from(User::class, 'u')
                ->getQuery()
                ->execute();

            $e->createQueryBuilder()
                ->delete()
                ->from(Company::class, 'c')
                ->getQuery()
                ->execute();
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }

        $io->success('The database has been wiped.');

        return Command::SUCCESS;
    }
}
