<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Provider;

interface ProviderInterface
{
    /**
     * @return Definition[]
     */
    public function getDefinitions(): array;
}
