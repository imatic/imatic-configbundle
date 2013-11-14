<?php
namespace Imatic\Bundle\ConfigBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('imatic_config.config_manager');

        foreach ($container->findTaggedServiceIds('imatic_config.provider') as $id => $parameters) {
            $definition->addMethodCall('registerProvider', [
                new Reference($id),
                isset($parameters[0]['alias']) ? $parameters[0]['alias'] : $id
            ]);
        }
    }
}