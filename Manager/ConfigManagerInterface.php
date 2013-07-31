<?php
namespace Imatic\Bundle\ConfigBundle\Manager;

use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Imatic\Bundle\ConfigBundle\Provider\ProviderInterface;
use Imatic\Bundle\ConfigBundle\Exception\InvalidKeyException;
use Imatic\Bundle\ConfigBundle\Exception\InvalidValueException;

interface ConfigManagerInterface
{
    /**
     * @param string $key
     * @return mixed
     * @throw InvalidKeyException
     */
    public function getValue($key);

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $flush
     * @return $this
     * @throws InvalidKeyException
     * @throws InvalidValueException
     */
    public function setValue($key, $value, $flush = true);

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidKeyException
     */
    public function getViewValue($key);

    /**
     * @param string $key
     * @param scalar $value
     * @param bool $flush
     * @return $this
     * @throws InvalidKeyException
     * @throws InvalidValueException
     */
    public function setViewValue($key, $value, $flush = true);

    /**
     * @return array
     * @throws InvalidValueException
     */
    public function getValues();

    /**
     * @return array
     * @throws InvalidValueException
     */
    public function getViewValues();

    /**
     * @param ProviderInterface $provider
     * @param string $name
     * @return $this
     */
    public function registerProvider(ProviderInterface $provider, $name);

    /**
     * @param string $key
     * @return Definition
     * @throws InvalidKeyException
     */
    public function getDefinition($key);

    /**
     * @return array
     */
    public function getDefinitions();
}