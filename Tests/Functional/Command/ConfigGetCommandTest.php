<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Tests\Functional\Command;

use Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConfigGetCommandTest extends WebTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('imatic:config:get');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'key' => 'config.bar',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('1970-01-01', $output);
    }
}
