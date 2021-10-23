<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\DependencyInjection;

use Imatic\Bundle\ConfigBundle\DependencyInjection\Compiler\CompilerPass;
use Imatic\Bundle\ConfigBundle\Provider\ProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ImaticConfigExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->prependExtensionConfig('twig', ['globals' => ['imatic_config_templates' => $config['templates']]]);
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $definition = $container->getDefinition('Imatic\Bundle\ConfigBundle\Entity\ConfigRepository');
        $definition->replaceArgument(1, $config['entity_class']);

        $container->registerForAutoconfiguration(ProviderInterface::class)->addTag(CompilerPass::TAG);
    }
}
