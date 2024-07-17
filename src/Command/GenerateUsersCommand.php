<?php

namespace App\Command;

require_once __DIR__.'/../../vendor/fzaninotto/faker/src/autoload.php';

use App\Entity\Company;
use App\Entity\User;
use App\Util\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
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
                $pass = $faker->password;

                $user = new User();
                $user->setName($faker->firstName);
                $user->setRole($role);
                $user->setCompany($company);
                $user->setSurname($faker->lastName);
                $user->setEmail($faker->email);
                $user->setPassword($this->passwordHasher->hashPassword($user, $pass));
                $user->setPasswordNoHash($pass);
                $e->persist($user);
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

        $io->success(sprintf('Generated %s users!', $counter));

        return Command::SUCCESS;
    }
}
