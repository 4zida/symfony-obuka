<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CleanAdsCommandTestCase extends KernelTestCase
{
    public function testExecute(): void
    {
        $this->bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('app:clean-ads');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
    }
}