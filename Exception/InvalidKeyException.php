<?php
namespace Imatic\Bundle\ConfigBundle\Exception;

class InvalidKeyException extends \RuntimeException implements ExceptionInterface
{
    /**
     * @param string $key
     */
    public function __construct($key)
    {
        parent::__construct(sprintf('The given key "%s" is not provided by any registered provider.', $key));
    }
}