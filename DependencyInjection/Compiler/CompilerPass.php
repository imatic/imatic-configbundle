<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CompilerPass implements CompilerPassInterface
{
    public const TAG = 'imatic_config.provider';

    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('imatic_config.config_manager');

        foreach ($container->findTaggedServiceIds(self::TAG) as $id => $parameters) {
            $definition->addMethodCall('registerProvider', [new Reference($id), $parameters[0]['alias'] ?? 'config']);
        }
    }
}
