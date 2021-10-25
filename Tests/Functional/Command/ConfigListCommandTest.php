<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Tests\Functional\Command;

use Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConfigListCommandTest extends WebTestCase
{
    public function testExecute()
    {
        $expected = <<<OUTPUT
+------------+------------+-----------------------------------------------------+-----------------+
| key        | value      | type                                                | description     |
+------------+------------+-----------------------------------------------------+-----------------+
| config.foo | foo        | Symfony\Component\Form\Extension\Core\Type\TextType | config.foo      |
| config.bar | 1970-01-01 | Symfony\Component\Form\Extension\Core\Type\DateType | config.bar      |
| config.baz |            | Symfony\Component\Form\Extension\Core\Type\DateType | Baz description |
+------------+------------+-----------------------------------------------------+-----------------+

OUTPUT;

        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('imatic:config:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringConforms($expected, $output);
    }

    public function testExecuteShouldDisplayFilteredValues()
    {
        $expected = <<<OUTPUT
+------------+------------+-----------------------------------------------------+-----------------+
| key        | value      | type                                                | description     |
+------------+------------+-----------------------------------------------------+-----------------+
| config.bar | 1970-01-01 | Symfony\Component\Form\Extension\Core\Type\DateType | config.bar      |
| config.baz |            | Symfony\Component\Form\Extension\Core\Type\DateType | Baz description |
+------------+------------+-----------------------------------------------------+-----------------+

OUTPUT;

        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('imatic:config:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'filter' => 'ba',
        ]);

        $output = $commandTester->getDisplay();

        $this->assertStringConforms($expected, $output);
    }

    public static function assertStringConforms(string $expected, string $actual, $message = '')
    {
        self::assertEquals($expected, str_replace("\r\n", "\n", $actual), $message);
    }
}
