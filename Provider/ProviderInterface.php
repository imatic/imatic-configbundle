<?php
namespace Imatic\Bundle\ConfigBundle\Provider;

interface ProviderInterface
{
    /**
     * @return Definition[]
     */
    public function getDefinitions();
}