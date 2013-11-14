<?php
namespace Imatic\Bundle\ConfigBundle\Tests\Config;

use Imatic\Bundle\ConfigBundle\Config\ValueTransformer;
use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormRegistry;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\Validator;

class ValueTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ValueTransformer */
    private $valueTransformer;

    /** @var Definition */
    private $definition;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->valueTransformer = new ValueTransformer($this->createFormFactory());
        $this->definition = Definition::create('key', 'date')->setOptions(['widget' => 'single_text']);
    }

    public function testTransform()
    {
        $this->assertEquals(
            '1970-01-01',
            $this->valueTransformer->transform($this->definition, new \DateTime('1970-01-01'))
        );
    }

    /**
     * @expectedException \Imatic\Bundle\ConfigBundle\Exception\InvalidValueException
     * @expectedExceptionMessage Expected a \DateTime.
     */
    public function testTransformThrowsException()
    {
        $this->valueTransformer->transform($this->definition, 'value');
    }

    /**
     * @expectedException \Imatic\Bundle\ConfigBundle\Exception\InvalidValueException
     * @expectedExceptionMessage The form must be configured to return scalar view values.
     */
    public function testTransformThrowsNotScalarException()
    {
        $this->valueTransformer->transform($this->definition->setOptions([]), new \DateTime('1970-01-01'));
    }

    public function testReverseTransform()
    {
        $this->assertEquals(
            new \DateTime('1970-01-01'),
            $this->valueTransformer->reverseTransform($this->definition, '1970-01-01')
        );
    }

    /**
     * @expectedException \Imatic\Bundle\ConfigBundle\Exception\InvalidValueException
     * @expectedExceptionMessage This value is not valid.
     */
    public function testReverseTransformThrowsException()
    {
        $this->valueTransformer->reverseTransform($this->definition, 'value');
    }

    /**
     * @return FormFactory
     */
    private function createFormFactory()
    {
        $resolvedFormTypeFactory = new ResolvedFormTypeFactory();
        $formRegistry = new FormRegistry(
            [new CoreExtension(), new ValidatorExtension($this->createValidator())],
            $resolvedFormTypeFactory
        );

        return new FormFactory($formRegistry, $resolvedFormTypeFactory);
    }

    /**
     * @return Validator
     */
    private function createValidator()
    {
        return new Validator(new ClassMetadataFactory(), new ConstraintValidatorFactory(), new Translator('en'));
    }
}