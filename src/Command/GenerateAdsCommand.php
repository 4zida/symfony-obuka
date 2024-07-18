<?php

namespace App\Command;

use App\Document\Ad;
use App\Entity\Company;
use App\Entity\User;
use App\Util\UnixHelper;
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
                $rand = random_int(0, 1);
                $company = $companyArray[array_rand($companyArray)];
                $user = $userArray[array_rand($userArray)];
                $time = time() - rand(0, UnixHelper::MONTH * 3);

                $ad = new Ad();
                $ad->setName($faker->sentence);
                $ad->setUrl($faker->url);
                $ad->setDescription($faker->sentence);
                if ($rand) {
                    $ad->setUserId($user->getId());
                    $ad->setCompanyId(null);
                } else {
                    $ad->setUserId(null);
                    $ad->setCompanyId($company->getId());
                }
                $ad->setDateTime(date(DATE_ATOM, $time));
                $ad->setUnixTime($time);

                $dm->persist($ad);
            } catch (Exception $e) {
                $io->error($e->getMessage() . " Continuing...");
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
