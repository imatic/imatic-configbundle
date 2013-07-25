<?php
namespace Imatic\Bundle\ConfigBundle\Provider;

interface ProviderInterface
{
    /**
     * @return Node[]
     */
    public function getNodes();
}