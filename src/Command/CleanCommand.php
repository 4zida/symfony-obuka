<?php

namespace App\Command;

use App\Document\Ad;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
        private readonly UserRepository $userRepository,
        private readonly DocumentManager $documentManager,
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

        $users = $this->userRepository->findAll();
        foreach ($users as $user)
        {
            $id = $user->getId();
            $output->writeln(sprintf('User %s (%d) will be deleted', $user->getName(), $user->getId()));
            $this->userRepository->deleteUser($user);
            $output->writeln(sprintf('User %s (%d) has been deleted', $user->getName(), $id));
        }

        $companies = $this->companyRepository->findAll();
        foreach ($companies as $company)
        {
            $id = $company->getId();
            $output->writeln(sprintf('Company %s (%d) will be deleted', $company->getName(), $company->getId()));
            $this->companyRepository->deleteCompany($company);
            $output->writeln(sprintf('Company %s (%d) has been deleted', $company->getName(), $id));
        }

        $ads = $this->documentManager->getRepository(Ad::class)->findAll();
        foreach ($ads as $ad)
        {
            $id = $ad->getId();
            $output->writeln(sprintf('Ad %s (%d) will be deleted', $ad->getName(), $ad->getId()));
            $this->documentManager->remove($ad);
            $output->writeln(sprintf('Ad %s (%d) has been deleted', $ad->getName(), $id));
        }

        $this->documentManager->flush();

        $io->success('The database has been wiped.');

        return Command::SUCCESS;
    }
}
