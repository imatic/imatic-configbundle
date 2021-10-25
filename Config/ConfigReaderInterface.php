<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Config;

use Imatic\Bundle\ConfigBundle\Exception\InvalidKeyException;

interface ConfigReaderInterface
{
    /**
     * @return mixed
     *
     * @throw InvalidKeyException
     */
    public function getValue(string $key);

    /**
     * @return mixed
     *
     * @throws InvalidKeyException
     */
    public function getViewValue(string $key);
}
