<?php
namespace Imatic\Bundle\ConfigBundle\Provider;

class ChainProvider implements ProviderInterface
{
    /** @var ProviderInterface[] */
    private $providers = [];

    /** @var Node[]|null */
    private $nodes;

    /**
     * @return Node[]
     */
    public function getNodes()
    {
        if ($this->nodes === null) {
            $this->nodes = [];

            foreach ($this->providers as $provider) {
                $this->nodes = array_merge($this->nodes, $provider->getNodes());
            }
        }

        return $this->nodes;
    }

    /**
     * @param ProviderInterface $provider
     * @param string $name
     * @return $this
     */
    public function registerProvider(ProviderInterface $provider, $name)
    {
        $this->providers[$name] = $provider;
        $this->nodes = null;

        return $this;
    }
}