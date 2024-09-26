<?php

namespace App\Command;

use App\Document\Ad\Ad;
use App\Document\Ad\Image;
use App\Repository\AdRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:clean-ads',
    description: 'Clears the Ad documents',
)]
class CleanAdsCommand extends Command
{
    public function __construct(
        private readonly DocumentManager $documentManager
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

        /** @var AdRepository $adRepository */
        $adRepository = $this->documentManager->getRepository(Ad::class);
        $ads = $adRepository->findAll();

        foreach ($ads as $ad) {
            $id = $ad->getId();
            $name = $ad->getName();
            foreach ($ad->getImages() as $image) {
                $this->documentManager->getRepository(Image::class)->remove($image);
            }
            $output->writeln(sprintf('Ad %s (%s) will be deleted', $name, $id));
            $adRepository->remove($ad);
            $output->writeln(sprintf('Ad %s (%s) has been deleted', $name, $id));
        }

        $this->documentManager->flush();

        $io->success('The ads have been wiped.');

        return Command::SUCCESS;
    }
}
