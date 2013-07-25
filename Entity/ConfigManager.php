<?php
namespace Imatic\Bundle\ConfigBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ObjectManager;
use Imatic\Bundle\ConfigBundle\Entity\ConfigRepository;

class ConfigManager implements ConfigManagerInterface
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var ConfigRepository */
    private $repository;

    /** @var ArrayCollection|null */
    private $configs;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->repository = $objectManager->getRepository('ImaticConfigBundle:Config');
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $configs = $this->getConfigs();

        if ($configs->containsKey($key)) {
            return $configs->get($key)->getValue();
        }

        return $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $andFlush
     * @return $this
     */
    public function set($key, $value, $andFlush = true)
    {
        $configs = $this->getConfigs();
        $config = $configs->containsKey($key) ? $configs->get($key) : new Config($key);
        $configs->set($key, $config);
        $this->objectManager->persist($config);

        if ($andFlush) {
            $this->objectManager->flush();
        }

        return $this;
    }

    /**
     * @param string|null $pattern
     * @return array
     */
    public function all($pattern = null)
    {
        $configs = $this->getConfigs();

        if ($pattern !== null) {
            $configs = $configs->matching((new Criteria())->where(Criteria::expr()->eq('key', "%{$pattern}%")));
        }

        return $configs->map('strval');
    }

    /**
     * @return ArrayCollection|Config[]
     */
    public function getConfigs()
    {
        if (!$this->configs) {
            $this->configs = new ArrayCollection($this->repository->findAll());
        }

        return $this->configs;
    }

    /**
     * @return ConfigRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}