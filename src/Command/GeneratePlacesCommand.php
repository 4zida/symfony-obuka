<?php

declare(strict_types=1);

namespace App\Command;

use App\Document\Place;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:generate-places', description: 'Generates places')]
class GeneratePlacesCommand extends Command
{
    public function __construct(
        private readonly DocumentManager $documentManager
    )
    {
        parent::__construct();
    }

    public array $places = [
        "Beograd",
        "Novi Sad",
        "Niš",
        "Kragujevac",
        "Subotica",
        "Zrenjanin",
        "Pančevo",
        "Čačak",
        "Kraljevo",
        "Vranje",
        "Senta",
        "Užice",
        "Valjevo",
        "Šabac",
        "Jagodina",
        "Leskovac",
        "Kruševac",
        "Požarevac",
        "Pirot",
        "Smederevo",
        "Novi Pazar",
        "Inđija",
        "Vršac",
        "Apatin",
        "Bačka Palanka",
        "Ruma",
        "Kovin",
        "Aleksinac",
        "Zaječar",
        "Ćuprija"
    ];

    /**
     * @throws MongoDBException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->places as $place) {
            try {
                $p = new Place();
                $p->setTitle($place);
                $this->documentManager->persist($p);
            } catch (Exception $e) {
                $output->writeln($e->getMessage());
            }
        }

        $this->documentManager->flush();

        return Command::SUCCESS;
    }
}
