<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject\ImaticConfigBundle\Provider;

use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Imatic\Bundle\ConfigBundle\Provider\ProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ConfigProvider implements ProviderInterface
{
    public function getDefinitions(): array
    {
        return [
            Definition::create('foo'),
            Definition::create('bar', DateType::class, new \DateTime('1970-01-01'))->setOptions(['widget' => 'single_text']),
            Definition::create('baz', DateType::class)->setOptions(['widget' => 'single_text']),
        ];
    }
}
