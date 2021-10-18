<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Exception;

class InvalidKeyException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $key)
    {
        parent::__construct(sprintf('The given key "%s" is not provided by any registered provider.', $key));
    }
}
