<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Tests\Functional\Command;

use Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConfigSetCommandTest extends WebTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('imatic:config:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'key' => 'config.foo',
            'value' => 'test',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertEquals('Config variable config.foo was set to test.', $output);
    }
}
