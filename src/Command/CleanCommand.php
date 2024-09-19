<?php

namespace App\Command;

use App\Repository\CompanyRepository;
use App\Repository\PhoneRepository;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\EntityManagerInterface;
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
        private readonly CompanyRepository      $companyRepository,
        private readonly UserRepository         $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly PhoneRepository        $phoneRepository,
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

        $phones = $this->phoneRepository->findAll();
        foreach ($phones as $phone) {
            $name = $phone->getFull();
            $id = $phone->getId();
            $output->writeln(sprintf('Phone %s (%d) will be deleted', $name, $id));
            $this->phoneRepository->deletePhone($phone);
            $output->writeln(sprintf('Phone %s (%d) has been deleted', $name, $id));
        }

        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $name = $user->getName();
            $id = $user->getId();
            $output->writeln(sprintf('User %s (%d) will be deleted', $name, $id));
            $this->userRepository->deleteUser($user);
            $output->writeln(sprintf('User %s (%d) has been deleted', $name, $id));
        }

        $companies = $this->companyRepository->findAll();
        foreach ($companies as $company) {
            $name = $company->getName();
            $id = $company->getId();
            $output->writeln(sprintf('Company %s (%d) will be deleted', $name, $id));
            $this->companyRepository->deleteCompany($company);
            $output->writeln(sprintf('Company %s (%d) has been deleted', $name, $id));
        }

        $this->entityManager->flush();

        $io->success('The database has been wiped.');

        return Command::SUCCESS;
    }
}
