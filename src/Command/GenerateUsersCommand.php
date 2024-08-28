<?php

namespace App\Command;

require_once __DIR__ . '/../../vendor/fzaninotto/faker/src/autoload.php';

use App\Entity\Company;
use App\Entity\User;
use App\Util\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:generate-users',
    description: 'Generates users with random values',
)]
class GenerateUsersCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'amount',
            InputArgument::OPTIONAL,
            "Amount of users to be generated (higher values take longer)",
            10);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $em = $this->entityManager;
        $faker = Factory::create();
        $companyArray = $em->getRepository(Company::class)->getCompaniesAsArray();
        $counter = 0;
        $amount = $input->getArgument('amount');

        if (!is_numeric($amount) || $amount < 1 || $amount > 1000) {
            $io->error("Amount of users to be generated must be between 1 and 1000");
            return Command::FAILURE;
        }

        $amount = (int) $amount;

        if ($amount > 100) {
            $answer = $io->ask(sprintf(
                "This will take approximately %s seconds, are you sure?",
                $amount * 0.5),
                "yes");

            if (strtolower($answer) !== 'yes') {
                return Command::FAILURE;
            }
        }

        $time = microtime(true);
        $io->progressStart($amount);

        for ($i = 0; $i < $amount; $i++) {
            try {
                $company = $companyArray[array_rand($companyArray)];
                $role = UserRole::cases()[array_rand(UserRole::cases())];
                $pass = $faker->password;

                $user = (new User())
                    ->setName($faker->firstName)
                    ->setRole($role)
                    ->setCompany($company)
                    ->setSurname($faker->lastName)
                    ->setEmail($faker->email);
                $user->setPassword($this->passwordHasher->hashPassword($user, $pass))
                    ->setPasswordNoHash($pass);
                $em->persist($user);
            } catch (Exception $e) {
                $io->error($e->getMessage() . "\n Continuing...");
                continue;
            }
            $counter++;
            $io->progressAdvance();
        }

        if ($counter == 0) {
            return Command::FAILURE;
        }

        $em->flush();
        $time = microtime(true) - $time;
        $io->progressFinish();

        $io->success(sprintf('Generated %s users!', $counter));
        $io->writeln(sprintf('Took: %s seconds.', $time));

        return Command::SUCCESS;
    }
}
