<?php

namespace App\Command;

use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:clean',
    description: 'Clears the database',
)]
class CleanCommand extends Command
{
    public function __construct(
        private readonly CompanyRepository $companyRepository,
        private readonly UserRepository    $userRepository,
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

        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $id = $user->getId();
            $output->writeln(sprintf('User %s (%d) will be deleted', $user->getName(), $user->getId()));
            $this->userRepository->deleteUser($user);
            $output->writeln(sprintf('User %s (%d) has been deleted', $user->getName(), $id));
        }

        $companies = $this->companyRepository->findAll();
        foreach ($companies as $company) {
            $id = $company->getId();
            $output->writeln(sprintf('Company %s (%d) will be deleted', $company->getName(), $company->getId()));
            $this->companyRepository->deleteCompany($company);
            $output->writeln(sprintf('Company %s (%d) has been deleted', $company->getName(), $id));
        }

        $io->success('The database has been wiped.');

        return Command::SUCCESS;
    }
}
