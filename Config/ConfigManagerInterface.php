<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Config;

use Imatic\Bundle\ConfigBundle\Exception\InvalidKeyException;
use Imatic\Bundle\ConfigBundle\Exception\InvalidValueException;
use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Imatic\Bundle\ConfigBundle\Provider\ProviderInterface;

interface ConfigManagerInterface extends ConfigReaderInterface
{
    /**
     * @param mixed $value
     *
     * @throws InvalidKeyException
     * @throws InvalidValueException
     */
    public function setValue(string $key, $value, bool $flush = true);

    /**
     * @param scalar $value
     *
     * @throws InvalidKeyException
     * @throws InvalidValueException
     */
    public function setViewValue(string $key, $value, bool $flush = true);

    /**
     * @throws InvalidValueException
     */
    public function getValues(?string $filter = null): array;

    /**
     * @throws InvalidValueException
     */
    public function getViewValues(?string $filter = null): array;

    public function registerProvider(ProviderInterface $provider, string $name);

    /**
     * @throws InvalidKeyException
     */
    public function getDefinition(string $key): Definition;

    /**
     * @return Definition[]
     */
    public function getDefinitions(): array;
}
