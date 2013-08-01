<?php
namespace Imatic\Bundle\ConfigBundle\Tests\Command;

use Imatic\Bundle\ConfigBundle\Command\ConfigGetCommand;

class ConfigGetCommandTest extends ConfigCommandTestCase
{
    public function testExecute()
    {
        $this->commandTester->execute([
            'command' => $this->command->getName(),
            'key' => 'config.bar'
        ]);
        $this->assertEquals('1970-01-01', $this->commandTester->getDisplay());
    }

    /**
     * @return ConfigGetCommand
     */
    protected function createCommand()
    {
        return new ConfigGetCommand();
    }
}