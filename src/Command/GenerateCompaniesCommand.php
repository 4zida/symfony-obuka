<?php

namespace App\Command;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-companies',
    description: 'Generates companies with random values',
)]
class GenerateCompaniesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('amount', InputArgument::OPTIONAL, "Amount of companies to be generated", 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $e = $this->entityManager;
        $faker = Factory::create();
        $counter = 0;
        $amount = $input->getArgument('amount');

        if (!is_numeric($amount) || $amount < 1 || $amount > 100) {
            $io->error("Amount must be between 1 and 100");
            return Command::FAILURE;
        }

        $amount = (int) $amount;

        for ($i = 0; $i < $amount; $i++) {
            try {
                $company = (new Company())
                    ->setName($faker->company)
                    ->setAddress($faker->address);
                $e->persist($company);
            } catch (Exception $e) {
                $io->error($e->getMessage() . "\n Continuing...");
                continue;
            }
            $counter++;
        }

        if ($counter == 0) {
            return Command::FAILURE;
        }

        $e->flush();

        $io->success(sprintf('Generated %s companies!', $counter));

        return Command::SUCCESS;
    }
}
