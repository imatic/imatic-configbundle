<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Config;

use Doctrine\ORM\EntityManager;
use Imatic\Bundle\ConfigBundle\Entity\Config;
use Imatic\Bundle\ConfigBundle\Entity\ConfigRepository;
use Imatic\Bundle\ConfigBundle\Exception\InvalidKeyException;
use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Imatic\Bundle\ConfigBundle\Provider\ProviderInterface;

class ConfigManager implements ConfigManagerInterface
{
    private EntityManager $em;

    private ValueTransformer $valueTransformer;

    private ConfigRepository $repository;

    /**
     * @var ProviderInterface[]
     */
    private array $providers = [];

    private ?array $definitions = null;

    /**
     * @param EntityManager $em
     * @param ValueTransformer $valueTransformer
     */
    public function __construct(EntityManager $em, ValueTransformer $valueTransformer)
    {
        $this->em = $em;
        $this->valueTransformer = $valueTransformer;
        $this->repository = $em->getRepository(Config::class);
    }

    public function getValue(string $key)
    {
        return $this->valueTransformer->reverseTransform($this->getDefinition($key), $this->getViewValue($key));
    }

    public function setValue(string $key, $value, bool $flush = true): self
    {
        return $this->setViewValue(
            $key,
            $this->valueTransformer->transform($this->getDefinition($key), $value),
            $flush
        );
    }

    public function getViewValue(string $key)
    {
        $definition = $this->getDefinition($key);

        if ($config = $this->repository->findOneByKey($key)) {
            return $config->getValue();
        }

        return $this->valueTransformer->transform($definition, $definition->getDefault());
    }

    public function setViewValue(string $key, $value, bool $flush = true): self
    {
        $this->invalidateResultCache($key);

        $className = $this->repository->getClassName();

        $this->valueTransformer->reverseTransform($this->getDefinition($key), $value);
        $config = $this->repository->findOneByKey($key, false) ?: new $className($key);
        $config->setValue($value);
        $this->em->persist($config);

        if ($flush) {
            $this->em->flush();
        }

        return $this;
    }

    protected function invalidateResultCache(string $key)
    {
        $this->em
            ->getConfiguration()
            ->getResultCacheImpl()
            ->delete($this->repository->getCacheKey($key));
    }

    public function getValues(?string $filter = null): array
    {
        $data = [];

        foreach ($this->getViewValues($filter) as $key => $value) {
            $data[$key] = $this->valueTransformer->reverseTransform($this->getDefinition($key), $value);
        }

        return $data;
    }

    public function getViewValues(?string $filter = null): array
    {
        $configs = $this->repository->findByFilter($filter);
        $data = [];

        foreach ($this->getDefinitions() as $definitions) {
            foreach ($definitions as $key => $definition) {
                if ($filter === null || strpos($key, $filter) !== false) {
                    $data[$key] = isset($configs[$key])
                        ? $configs[$key]->getValue()
                        : $this->valueTransformer->transform($definition, $definition->getDefault());
                }
            }
        }

        return $data;
    }

    public function registerProvider(ProviderInterface $provider, string $name): self
    {
        $this->providers[$name] = $provider;
        $this->definitions = null;

        return $this;
    }

    public function getDefinition(string $key): Definition
    {
        $this->getDefinitions();
        list($name) = explode('.', $key, 2);

        if (!isset($this->definitions[$name][$key])) {
            throw new InvalidKeyException($key);
        }

        return $this->definitions[$name][$key];
    }

    public function getDefinitions(): array
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
