<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GetLastMonthsAdsCommandTestCase extends KernelTestCase
{
    public function testExecute(): void
    {
        $this->bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('app:get-last-months-ads');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
    }
}
