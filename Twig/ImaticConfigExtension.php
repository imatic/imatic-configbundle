<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ImaticConfigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('imatic_config_value', [ImaticConfigRuntime::class, 'getValue']),
        ];
    }
}
