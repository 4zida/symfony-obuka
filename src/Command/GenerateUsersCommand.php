<?php

namespace App\Command;

require_once __DIR__.'/../../vendor/fzaninotto/faker/src/autoload.php';

use App\Entity\Company;
use App\Entity\User;
use App\Util\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-users',
    description: 'Add a short description for your command',
)]
class GenerateUsersCommand extends Command
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
        $faker = Factory::create();
        $companyArray = $e->getRepository(Company::class)->getCompaniesAsArray();
        $counter = 0;

        for ($i = 0; $i < 100; $i++) {
            try {
                $company = $companyArray[array_rand($companyArray)];
                $role = UserRole::cases()[array_rand(UserRole::cases())];

                $user = new User();
                $user->setName($faker->firstName);
                $user->setRole($role);
                $user->setCompany($company);
                $user->setSurname($faker->lastName);
                $e->persist($user);
            } catch (\Exception $e) {
                $io->error($e->getMessage(). " Continuing...");
                continue;
            }
            $counter++;
        }

        if ($counter == 0) {
            return Command::FAILURE;
        }

        $e->flush();

        $io->success(sprintf('Generated %s users!', $counter));

        return Command::SUCCESS;
    }
}
