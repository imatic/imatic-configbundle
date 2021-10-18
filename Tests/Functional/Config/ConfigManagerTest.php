<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Tests\Functional\Config;

use Imatic\Bundle\ConfigBundle\Config\ConfigManagerInterface;
use Imatic\Bundle\ConfigBundle\Exception\InvalidKeyException;
use Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject\WebTestCase;

class ConfigManagerTest extends WebTestCase
{
    private ConfigManagerInterface $configManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configManager = self::getContainer()->get(ConfigManagerInterface::class);
    }

    public function testGetValue()
    {
        $this->assertEquals('foo', $this->configManager->getValue('config.foo'));
        $this->assertEquals(new \DateTime('1970-01-01'), $this->configManager->getValue('config.bar'));
    }

    public function testGetValueThrowsException()
    {
        $this->expectException(InvalidKeyException::class);
        $this->expectExceptionMessage('The given key "foo" is not provided by any registered provider.');

        $this->configManager->getValue('foo');
    }

    public function testSetValue()
    {
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
        $this->configManager->setViewValue('config.foo', 'value');
        $this->assertEquals('value', $this->configManager->getValue('config.foo'));

        $this->configManager->setViewValue('config.baz', '1970-02-01');
        $this->assertEquals(new \DateTime('1970-02-01'), $this->configManager->getValue('config.baz'));
    }

    public function testGetValues()
    {
        $this->assertEquals(
            [
                'config.foo' => 'foo',
                'config.bar' => new \DateTime('1970-01-01'),
                'config.baz' => null,
            ],
            $this->configManager->getValues()
        );

        $this->assertEquals(
            [
                'config.foo' => 'foo',
                'config.bar' => new \DateTime('1970-01-01'),
                'config.baz' => null,
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
                'config.foo' => 'foo',
                'config.bar' => '1970-01-01',
                'config.baz' => '',
            ],
            $this->configManager->getViewValues()
        );

        $this->assertEquals(
            [
                'config.foo' => 'foo',
                'config.bar' => '1970-01-01',
                'config.baz' => '',
            ],
            $this->configManager->getViewValues('config')
        );
        $this->assertEquals(['config.foo' => 'foo'], $this->configManager->getViewValues('foo'));
        $this->assertEmpty($this->configManager->getViewValues('test'));
    }
}
