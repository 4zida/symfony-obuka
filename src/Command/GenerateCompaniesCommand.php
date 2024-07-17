<?php

namespace App\Command;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-companies',
    description: 'Generates companies with random values',
)]
class GenerateCompaniesCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $e = $this->entityManager;
        $faker = Factory::create();
        $counter = 0;

        for ($i = 0; $i < 10; $i++) {
            try {
                $company = new Company();
                $company->setName($faker->company);
                $company->setAddress($faker->address);
                $e->persist($company);
            } catch (Exception $e) {
                $io->error($e->getMessage(). " Continuing...");
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
