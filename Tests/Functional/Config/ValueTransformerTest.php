<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Tests\Functional\Config;

use Imatic\Bundle\ConfigBundle\Config\ValueTransformer;
use Imatic\Bundle\ConfigBundle\Exception\InvalidValueException;
use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject\WebTestCase;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ValueTransformerTest extends WebTestCase
{
    private ValueTransformer $valueTransformer;
    private Definition $definition;

    protected function setUp(): void
    {
        parent::setUp();

        $this->valueTransformer = self::getContainer()->get('imatic_config.value_transformer');
        $this->definition = Definition::create('key', DateType::class)->setOptions(['widget' => 'single_text']);
    }

    public function testTransform()
    {
        $this->assertEquals(
            '1970-01-01',
            $this->valueTransformer->transform($this->definition, new \DateTime('1970-01-01'))
        );
    }

    public function testTransformThrowsException()
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Expected a \DateTimeInterface.');

        $this->valueTransformer->transform($this->definition, 'value');
    }

    public function testTransformThrowsNotScalarException()
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('The form must be configured to return scalar view values.');

        $this->valueTransformer->transform($this->definition->setOptions([]), new \DateTime('1970-01-01'));
    }

    public function testReverseTransform()
    {
        $this->assertEquals(
            new \DateTime('1970-01-01'),
            $this->valueTransformer->reverseTransform($this->definition, '1970-01-01')
        );
    }

    public function testReverseTransformThrowsException()
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('This value is not valid.');

        $this->valueTransformer->reverseTransform($this->definition, 'value');
    }
}
