<?php
namespace Imatic\Bundle\ConfigBundle\Tests\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Imatic\Bundle\ConfigBundle\Entity\Config;
use Imatic\Bundle\ConfigBundle\Entity\ConfigRepository;
use Imatic\Bundle\ConfigBundle\Manager\ConfigManager;
use Imatic\Bundle\ConfigBundle\Manager\ValueTransformer;
use Imatic\Bundle\ConfigBundle\Tests\Fixtures\Provider\ConfigProvider;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormRegistry;
use Symfony\Component\Form\ResolvedFormTypeFactory;

class ConfigManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ConfigManager */
    private $configManager;

    /** @var ObjectManager|\PHPUnit_Framework_MockObject_MockObject */
    private $objectManagerMock;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->objectManagerMock = $this->createObjectManagerMock();
        $this->configManager = new ConfigManager(
            $this->objectManagerMock,
            new ValueTransformer($this->createFormFactory())
        );
        $this->configManager->registerProvider(new ConfigProvider(), 'config');
    }

    public function testGetValue()
    {
        $this->assertEquals('foo', $this->configManager->getValue('config.foo'));
        $this->assertEquals(new \DateTime('1970-01-01'), $this->configManager->getValue('config.bar'));
    }

    /**
     * @expectedException \Imatic\Bundle\ConfigBundle\Exception\InvalidKeyException
     * @expectedExceptionMessage The given key "foo" is not provided by any registered provider.
     */
    public function testGetValueThrowsException()
    {
        $this->configManager->getValue('foo');
    }

    public function testSetValue()
    {
        $this->objectManagerMock
            ->expects($this->exactly(2))
            ->method('flush')
        ;
        $this->configManager->setValue('config.foo', 'value');
        $this->assertEquals('value', $this->configManager->getValue('config.foo'));
        $this->configManager->setValue('config.baz', new \DateTime('1970-02-01'));
        $this->assertEquals(new \DateTime('1970-02-01'), $this->configManager->getValue('config.baz'));
    }

    public function testGetViewValue()
    {
        $this->assertEquals('foo', $this->configManager->getViewValue('config.foo'));
        $this->assertEquals('1970-01-01', $this->configManager->getViewValue('config.bar'));
    }

    public function testSetViewValue()
    {
        $this->objectManagerMock
            ->expects($this->exactly(2))
            ->method('flush')
        ;
        $this->configManager->setViewValue('config.foo', 'value');
        $this->assertEquals('value', $this->configManager->getValue('config.foo'));
        $this->configManager->setViewValue('config.baz', '1970-02-01');
        $this->assertEquals(new \DateTime('1970-02-01'), $this->configManager->getValue('config.baz'));
    }

    public function testGetValues()
    {
        $this->assertEquals(
            [
                'config.foo' => null,
                'config.bar' => new \DateTime('1970-01-01'),
                'config.baz' => null
            ],
            $this->configManager->getValues()
        );
        $this->assertEquals(
            [
                'config.foo' => 'foo',
                'config.bar' => new \DateTime('1970-01-01'),
                'config.baz' => null
            ],
            $this->configManager->getValues('config')
        );
        $this->assertEquals(['config.foo' => 'foo'], $this->configManager->getValues('foo'));
        $this->assertEmpty($this->configManager->getValues('test'));
    }

    public function testGetViewValues()
    {
        $this->assertEquals(
            [
                'config.foo' => '',
                'config.bar' => '1970-01-01',
                'config.baz' => ''
            ],
            $this->configManager->getViewValues()
        );
        $this->assertEquals(
            [
                'config.foo' => 'foo',
                'config.bar' => '1970-01-01',
                'config.baz' => ''
            ],
            $this->configManager->getViewValues('config')
        );
        $this->assertEquals(['config.foo' => 'foo'], $this->configManager->getViewValues('foo'));
        $this->assertEmpty($this->configManager->getViewValues('test'));
    }

    /**
     * @return ObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createObjectManagerMock()
    {
        $objectManagerMock = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $objectManagerMock
            ->expects($this->any())
            ->method('getRepository')
            ->with('ImaticConfigBundle:Config')
            ->will($this->returnValue($this->createConfigRepositoryMock()))
        ;

        return $objectManagerMock;
    }

    /**
     * @return ConfigRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createConfigRepositoryMock()
    {
        $configRepositoryMock = $this
            ->getMockBuilder('Imatic\Bundle\ConfigBundle\Entity\ConfigRepository')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $configRepositoryMock
            ->expects($this->any())
            ->method('findOneByKey')
            ->will($this->returnValueMap([
                ['config.foo', new Config('config.foo', 'foo')],
                ['config.baz', new Config('config.baz')]
            ]))
        ;
        $configRepositoryMock
            ->expects($this->any())
            ->method('findByFilter')
            ->will($this->returnValueMap([
                ['config', ['config.foo' => new Config('config.foo', 'foo')]],
                ['foo', ['config.foo' => new Config('config.foo', 'foo')]],
            ]))
        ;

        return $configRepositoryMock;
    }

    /**
     * @return FormFactory
     */
    private function createFormFactory()
    {
        $resolvedFormTypeFactory = new ResolvedFormTypeFactory();

        return new FormFactory(
            new FormRegistry([new CoreExtension()], $resolvedFormTypeFactory),
            $resolvedFormTypeFactory
        );
    }
}