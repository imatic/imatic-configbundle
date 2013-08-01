<?php
namespace Imatic\Bundle\ConfigBundle\Tests\Command;

use Imatic\Bundle\ConfigBundle\Command\ConfigSetCommand;
use Imatic\Bundle\ConfigBundle\Entity\Config;

class ConfigSetCommandTest extends ConfigCommandTestCase
{
    public function testExecute()
    {
        $this->commandTester->execute(
            ['command' => $this->command->getName(), 'key' => 'config.foo', 'value' => 'foo'],
            ['decorated' => false]
        );
        $this->assertEquals('Config variable config.foo was set to foo.', $this->commandTester->getDisplay());
    }

    /**
     * @return ConfigSetCommand
     */
    protected function createCommand()
    {
        return new ConfigSetCommand();
    }

    /**
     * {@inheritDoc}
     */
    protected function createObjectManagerMock()
    {
        $objectManagerMock = parent::createObjectManagerMock();
        $objectManagerMock
            ->expects($this->once())
            ->method('persist')
            ->with(new Config('config.foo', 'foo'))
        ;
        $objectManagerMock
            ->expects($this->once())
            ->method('flush')
        ;

        return $objectManagerMock;
    }
}