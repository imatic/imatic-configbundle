<?php
namespace Imatic\Bundle\ConfigBundle\Tests\Command;

use Imatic\Bundle\ConfigBundle\Command\ConfigListCommand;

class ConfigListCommandTest extends ConfigCommandTestCase
{
    public function testExecute()
    {
        $this->commandTester->execute(['command' => $this->command->getName()], ['decorated' => false]);
        $output = <<<OUTPUT
+------------+------------+------+-------------+
| key        | value      | type | description |
+------------+------------+------+-------------+
| config.foo |            | text | config.foo  |
| config.bar | 1970-01-01 | date | config.bar  |
| config.baz |            | date | config.baz  |
+------------+------------+------+-------------+

OUTPUT;
        $this->assertStringConforms($output, $this->commandTester->getDisplay());
    }

    public function testExecuteShouldDisplayFilteredValues()
    {
        $this->commandTester->execute(
            ['command' => $this->command->getName(), 'filter' => 'ba'],
            ['decorated' => false]
        );
        $output = <<<OUTPUT
+------------+------------+------+-------------+
| key        | value      | type | description |
+------------+------------+------+-------------+
| config.bar | 1970-01-01 | date | config.bar  |
| config.baz |            | date | config.baz  |
+------------+------------+------+-------------+

OUTPUT;
        $this->assertStringConforms($output, $this->commandTester->getDisplay());
    }

    /**
     * @param string $expected
     * @param $actual
     * @param string $message
     */
    public static function assertStringConforms($expected, $actual, $message = '')
    {
        self::assertEquals($expected, str_replace("\r\n", "\n", $actual), $message);
    }

    /**
     * @return ConfigListCommand
     */
    protected function createCommand()
    {
        return new ConfigListCommand();
    }
}