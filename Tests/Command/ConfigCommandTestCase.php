<?php
namespace Imatic\Bundle\ConfigBundle\Tests\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Imatic\Bundle\ConfigBundle\Entity\ConfigRepository;
use Imatic\Bundle\ConfigBundle\Config\ConfigManager;
use Imatic\Bundle\ConfigBundle\Config\ValueTransformer;
use Imatic\Bundle\ConfigBundle\Tests\Fixtures\Provider\ConfigProvider;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormRegistry;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\Translation\TranslatorInterface;

abstract class ConfigCommandTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerAwareCommand */
    protected $command;

    /** @var ConfigManager */
    protected $configManager;

    /** @var CommandTester */
    protected $commandTester;

    /**
     * @return ContainerAwareCommand
     */
    abstract protected function createCommand();

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->configManager = new ConfigManager(
            $this->createObjectManagerMock(),
            new ValueTransformer($this->createFormFactory())
        );
        $this->configManager->registerProvider(new ConfigProvider(), 'config');
        $this->command = $this->createCommand();
        $this->command->setContainer($this->createContainerMock());
        $this->commandTester = new CommandTester($this->command);
        (new Application())->add($this->command);
    }

    /**
     * @return ObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createObjectManagerMock()
    {
        $objectManagerMock = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $objectManagerMock
            ->expects($this->any())
            ->method('getRepository')
            ->with('ImaticConfigBundle:Config')
            ->will($this->returnValue($this->createConfigRepositoryMock()));

        return $objectManagerMock;
    }

    /**
     * @return FormFactory
     */
    protected function createFormFactory()
    {
        $resolvedFormTypeFactory = new ResolvedFormTypeFactory();

        return new FormFactory(
            new FormRegistry([new CoreExtension()], $resolvedFormTypeFactory),
            $resolvedFormTypeFactory
        );
    }

    /**
     * @return ContainerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createContainerMock()
    {
        $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE;
        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $containerMock
            ->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap([
                ['imatic_config.config_manager', $invalidBehavior, $this->configManager],
                ['translator', $invalidBehavior, $this->createTranslatorMock()]
            ]));

        return $containerMock;
    }

    /**
     * @return ConfigRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createConfigRepositoryMock()
    {
        return $this
            ->getMockBuilder('Imatic\Bundle\ConfigBundle\Entity\ConfigRepository')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createTranslatorMock()
    {
        $translatorMock = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translatorMock
            ->expects($this->any())
            ->method('trans')
            ->will($this->returnArgument(0));

        return $translatorMock;
    }
}