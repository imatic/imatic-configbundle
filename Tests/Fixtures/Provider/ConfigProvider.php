<?php
namespace Imatic\Bundle\ConfigBundle\Tests\Fixtures\Provider;

use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Imatic\Bundle\ConfigBundle\Provider\ProviderInterface;

class ConfigProvider implements ProviderInterface
{
    /**
     * @return Definition[]
     */
    public function getDefinitions()
    {
        return [
            Definition::create('foo', 'text'),
            Definition::create('bar', 'date', new \DateTime('1970-01-01'))->setOptions(['widget' => 'single_text']),
            Definition::create('baz', 'date')->setOptions(['widget' => 'single_text'])
        ];
    }
}