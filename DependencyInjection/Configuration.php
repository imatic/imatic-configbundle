<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\DependencyInjection;

use Imatic\Bundle\ConfigBundle\Entity\Config;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('imatic_config');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('entity_class')->defaultValue(Config::class)->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
