<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\DependencyInjection;

use Imatic\Bundle\ConfigBundle\DependencyInjection\Compiler\CompilerPass;
use Imatic\Bundle\ConfigBundle\Provider\ProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ImaticConfigExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('Imatic\Bundle\ConfigBundle\Entity\ConfigRepository');
        $definition->replaceArgument(1, $config['entity_class']);

        $container->registerForAutoconfiguration(ProviderInterface::class)->addTag(CompilerPass::TAG);
    }
}
