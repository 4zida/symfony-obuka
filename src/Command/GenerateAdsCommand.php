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
    }

    /**
     * @throws MongoDBException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $em = $this->entityManager;
        $dm = $this->documentManager;
        $faker = Factory::create();
        $counter = 0;

        $companyArray = $em->getRepository(Company::class)->getCompaniesAsArray();
        $userArray = $em->getRepository(User::class)->getUsersAsArray();

        for ($i = 0; $i < 1000; $i++) {
            try {
                $ad = new Ad();
                $ad->setName($faker->sentence);
                $ad->setUrl($faker->url);
                $ad->setDescription($faker->paragraph);

                $rand = random_int(0, 1);
                if ($rand) {
                    $user = $userArray[array_rand($userArray)];
                    $ad->setUserId($user->getId());
                    $ad->setCompanyId(null);
                } else {
                    $company = $companyArray[array_rand($companyArray)];
                    $ad->setUserId(null);
                    $ad->setCompanyId($company->getId());
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

        $io->success(sprintf('Generated %s ads!', $counter));

        return Command::SUCCESS;
    }
}
