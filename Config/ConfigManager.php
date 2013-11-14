<?php
namespace Imatic\Bundle\ConfigBundle\Config;

use Doctrine\Common\Persistence\ObjectManager;
use Imatic\Bundle\ConfigBundle\Entity\Config;
use Imatic\Bundle\ConfigBundle\Entity\ConfigRepository;
use Imatic\Bundle\ConfigBundle\Exception\InvalidKeyException;
use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Imatic\Bundle\ConfigBundle\Provider\ProviderInterface;

class ConfigManager implements ConfigManagerInterface
{
    /** @var ValueTransformer */
    private $valueTransformer;

    /** @var ObjectManager */
    private $objectManager;

    /** @var ConfigRepository */
    private $repository;

    /** @var ProviderInterface[] */
    private $providers = [];

    /** @var array */
    private $definitions;

    /**
     * @param ObjectManager $objectManager
     * @param ValueTransformer $valueTransformer
     */
    public function __construct(ObjectManager $objectManager, ValueTransformer $valueTransformer)
    {
        $this->valueTransformer = $valueTransformer;
        $this->objectManager = $objectManager;
        $this->repository = $objectManager->getRepository('ImaticConfigBundle:Config');
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($key)
    {
        return $this->valueTransformer->reverseTransform($this->getDefinition($key), $this->getViewValue($key));
    }

    /**
     * {@inheritDoc}
     */
    public function setValue($key, $value, $flush = true)
    {
        return $this->setViewValue(
            $key,
            $this->valueTransformer->transform($this->getDefinition($key), $value),
            $flush
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getViewValue($key)
    {
        $definition = $this->getDefinition($key);

        if ($config = $this->repository->findOneByKey($key)) {
            return $config->getValue();
        }

        return $this->valueTransformer->transform($definition, $definition->getDefault());
    }

    /**
     * {@inheritDoc}
     */
    public function setViewValue($key, $value, $flush = true)
    {
        $this->valueTransformer->reverseTransform($this->getDefinition($key), $value);
        $config = $this->repository->findOneByKey($key) ? : new Config($key);
        $config->setValue($value);
        $this->objectManager->persist($config);

        if ($flush) {
            $this->objectManager->flush();
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getValues($filter = null)
    {
        $data = [];

        foreach ($this->getViewValues($filter) as $key => $value) {
            $data[$key] = $this->valueTransformer->reverseTransform($this->getDefinition($key), $value);
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getViewValues($filter = null)
    {
        $configs = $this->repository->findByFilter($filter);
        $data = [];

        foreach ($this->getDefinitions() as $definitions) {
            foreach ($definitions as $key => $definition) {
                /* @var $definition Definition */
                if ($filter === null || strpos($key, $filter) !== false) {
                    $data[$key] = isset($configs[$key])
                        ? $configs[$key]->getValue()
                        : $this->valueTransformer->transform($definition, $definition->getDefault());
                }
            }
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function registerProvider(ProviderInterface $provider, $name)
    {
        $this->providers[$name] = $provider;
        $this->definitions = null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefinition($key)
    {
        $this->getDefinitions();
        list($name,) = explode('.', $key, 2);

        if (!isset($this->definitions[$name][$key])) {
            throw new InvalidKeyException($key);
        }

        return $this->definitions[$name][$key];
    }

    /**
     * {@inheritDoc}
     */
    public function getDefinitions()
    {
        if ($this->definitions === null) {
            $this->definitions = [];

            foreach ($this->providers as $name => $provider) {
                foreach ($provider->getDefinitions() as $definition) {
                    $this->definitions[$name][sprintf('%s.%s', $name, $definition->getKey())] = $definition;
                }
            }
        }

        return $this->definitions;
    }
}