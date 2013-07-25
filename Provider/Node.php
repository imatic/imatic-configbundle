<?php
namespace Imatic\Bundle\ConfigBundle\Provider;

class Node
{
    /** @var string */
    private $key;

    /** @var string */
    private $type;

    /** @var mixed */
    private $default;

    /**
     * @param string $key
     * @param string $type
     * @param mixed $default
     */
    public function __construct($key, $type = 'text', $default = null)
    {
        $this->type = (string) $type;
        $this->key = (string) $key;
        $this->default = $default;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }
}