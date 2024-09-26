<?php

namespace App\Command;

use App\Entity\Phone;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-phones',
    description: 'Generates phone numbers',
)]
class GeneratePhonesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->entityManager->getRepository(User::class)->findAll();
        $count = 0;

        for ($i = 0; $i < 50; $i++) {
            try {
                $this->generatePhone($users);
                $count++;
            } catch (Exception $e) {
                $io->error($e->getMessage());
            }
        }

        $this->entityManager->flush();

        if ($count == 0) {
            $io->error('Failed to generate Phones!');
            return Command::SUCCESS;
        }

        $io->success(sprintf('Generated %s phones!', $count));
        return Command::SUCCESS;
    }

    /**
     * @param array $users
     * @return void
     */
    public function generatePhone(array $users): void
    {
        $user = $users[array_rand($users)];

        $phone = (new Phone())
            ->setFromPhoneNumber(PhoneNumberUtil::getInstance()->getExampleNumber(Phone::REGION_CODE))
            ->setUser($user);
        $this->entityManager->persist($phone);
    }
}
