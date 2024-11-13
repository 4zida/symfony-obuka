<?php

namespace App\Command;

use App\Document\Ad;
use App\Document\Image;
use App\Entity\Company;
use App\Entity\CreditTransactionLog;
use App\Entity\Phone;
use App\Entity\PromotionLog;
use App\Entity\User;
use App\Repository\CompanyRepository;
use App\Repository\CreditTransactionLogRepository;
use App\Repository\PhoneRepository;
use App\Repository\PromotionLogRepository;
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
        private readonly EntityManagerInterface $entityManager, private readonly DocumentManager $documentManager
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

        /** @var PhoneRepository $phoneRepo */
        $phoneRepo = $this->entityManager->getRepository(Phone::class);
        $phones = $phoneRepo->findAll();
        foreach ($phones as $phone) {
            $name = $phone->getFull();
            $id = $phone->getId();
            $output->writeln(sprintf('Phone %s (%d) will be deleted', $name, $id));
            $this->entityManager->remove($phone);
            $output->writeln(sprintf('Phone %s (%d) has been deleted', $name, $id));
        }

        /** @var UserRepository $userRepo */
        $userRepo = $this->entityManager->getRepository(User::class);
        $users = $userRepo->findAll();
        foreach ($users as $user) {
            $name = $user->getName();
            $id = $user->getId();
            $output->writeln(sprintf('User %s (%d) will be deleted', $name, $id));
            $this->entityManager->remove($user);
            $output->writeln(sprintf('User %s (%d) has been deleted', $name, $id));
        }

        /** @var CompanyRepository $companyRepo */
        $companyRepo = $this->entityManager->getRepository(Company::class);
        $companies = $companyRepo->findAll();
        foreach ($companies as $company) {
            $name = $company->getName();
            $id = $company->getId();
            $output->writeln(sprintf('Company %s (%d) will be deleted', $name, $id));
            $this->entityManager->remove($company);
            $output->writeln(sprintf('Company %s (%d) has been deleted', $name, $id));
        }

        /** @var CreditTransactionLogRepository $creditTransactionLogRepo */
        $creditTransactionLogRepo = $this->entityManager->getRepository(CreditTransactionLog::class);
        $creditTransactionLogs = $creditTransactionLogRepo->findAll();
        foreach ($creditTransactionLogs as $creditTransactionLog) {
            $id = $creditTransactionLog->getId();
            $output->writeln(sprintf('CreditTransactionLog %d will be deleted', $id));
            $this->entityManager->remove($creditTransactionLog);
            $output->writeln(sprintf('CreditTransactionLog %d has been deleted', $id));
        }

        /** @var PromotionLogRepository $promotionLogRepo */
        $promotionLogRepo = $this->entityManager->getRepository(PromotionLog::class);
        $promotionLogs = $promotionLogRepo->findAll();
        foreach ($promotionLogs as $promotionLog) {
            $id = $promotionLog->getId();
            $output->writeln(sprintf('PromotionLog %d will be deleted', $id));
            $this->entityManager->remove($promotionLog);
            $output->writeln(sprintf('PromotionLog %d has been deleted', $id));
        }

        $images = $this->documentManager->getRepository(Image::class)->findAll();
        /** @var Image $image */
        foreach ($images as $image) {
            $id = $image->getId();
            $output->writeln(sprintf('Image %s will be deleted', $id));
            $this->documentManager->remove($image);
            $output->writeln(sprintf('Image %s has been deleted', $id));
        }

        $ads = $this->documentManager->getRepository(Ad::class)->findAll();
        /** @var Ad $ad */
        foreach ($ads as $ad) {
            $id = $ad->getId();
            $output->writeln(sprintf('Ad %s will be deleted', $id));
            $this->documentManager->remove($ad);
            $output->writeln(sprintf('Ad %s has been deleted', $id));
        }

        $this->documentManager->flush();
        $this->entityManager->flush();

        $io->success('The database has been wiped.');

        return Command::SUCCESS;
    }
}
