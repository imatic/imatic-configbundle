<?php
namespace Imatic\Bundle\ConfigBundle\Provider;

class Definition
{
    /** @var string */
    private $key;

    /** @var string */
    private $type;

    /** @var mixed */
    private $default;

    /** @var array */
    private $options;

    /**
     * @param string $key
     * @param string $type
     * @param mixed $default
     * @param array $options
     */
    public function __construct($key, $type = 'text', $default = null, array $options = [])
    {
        $this->type = (string) $type;
        $this->key = (string) $key;
        $this->default = $default;
        $this->options = $options;
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

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options + ['required' => $this->default !== null];
    }
}