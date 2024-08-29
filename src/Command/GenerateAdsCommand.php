<?php

namespace App\Command;

use App\Document\Ad;
use App\Entity\Company;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
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
    name: 'app:generate-ads',
    description: 'Generates ads with random values',
)]
class GenerateAdsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DocumentManager        $documentManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'amount',
            InputArgument::OPTIONAL,
            'Amount of ads to be generated [int]',
            1000);
    }

    /**
     * @throws MongoDBException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $time = microtime(true);

        $io = new SymfonyStyle($input, $output);
        $amount = $input->getArgument('amount');

        if (!is_numeric($amount) || $amount < 1 || $amount > 9999) {
            $io->error('Amount must be a positive integer less than 9999');
            return Command::FAILURE;
        }
        $amount = (int)$amount;

        $em = $this->entityManager;
        $dm = $this->documentManager;
        $faker = Factory::create();
        $counter = 0;

        $companyArray = $em->getRepository(Company::class)->getCompaniesAsArray();
        $userArray = $em->getRepository(User::class)->getUsersAsArray();

        for ($i = 0; $i < $amount; $i++) {
            try {
                $ad = new Ad();
                $ad->setName($faker->sentence);
                $ad->setUrl($faker->url);
                $ad->setDescription($faker->paragraph);
                $ad->setFloor(random_int(-2, 10));
                $ad->setAddress($faker->address);
                $ad->setM2(random_int(20, 200));

                $rand = random_int(0, 1);
                if ($rand) {
                    if (!empty($userArray)) {
                        $user = $userArray[array_rand($userArray)];
                        $ad->setUserId($user->getId());
                    } else {
                        $ad->setUserId(null);
                    }
                    $ad->setCompanyId(null);
                } else {
                    if (!empty($companyArray)) {
                        $company = $companyArray[array_rand($companyArray)];
                        $ad->setCompanyId($company->getId());
                    } else {
                        $ad->setCompanyId(null);
                    }
                    $ad->setUserId(null);
                }
                $randomTimestamp = random_int(
                    (new DateTimeImmutable("-3 months"))->getTimestamp(),
                    (new DateTimeImmutable())->getTimestamp());
                $ad->setCreatedAt((new DateTimeImmutable())->setTimestamp($randomTimestamp));

                $dm->persist($ad);
            } catch (Exception $e) {
                $io->error($e->getMessage() . "\n Continuing...");
                continue;
            }
            $counter++;
        }

        if ($counter == 0) {
            return Command::FAILURE;
        }
        $dm->flush();

        $io->info(sprintf('Took %s seconds!', microtime(true) - $time));
        $io->success(sprintf('Generated %s ads!', $counter));

        return Command::SUCCESS;
    }
}
