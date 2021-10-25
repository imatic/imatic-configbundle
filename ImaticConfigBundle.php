<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle;

use Imatic\Bundle\ConfigBundle\DependencyInjection\Compiler\CompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ImaticConfigBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CompilerPass());
    }
}
