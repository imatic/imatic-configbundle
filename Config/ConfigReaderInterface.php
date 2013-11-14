<?php

namespace Imatic\Bundle\ConfigBundle\Config;

use Imatic\Bundle\ConfigBundle\Exception\InvalidKeyException;

interface ConfigReaderInterface
{
    /**
     * @param string $key
     * @return mixed
     * @throw InvalidKeyException
     */
    public function getValue($key);

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidKeyException
     */
    public function getViewValue($key);
}