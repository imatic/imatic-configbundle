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
    public function __construct($key, $type, $default, array $options)
    {
        $this->type = (string) $type;
        $this->key = (string) $key;
        $this->default = $default;
        $this->options = $options;
    }

    /**
     * @param string $key
     * @param string $type
     * @param mixed $default
     * @param array $options
     * @return Definition
     */
    public static function create($key, $type = 'text', $default = null, array $options = [])
    {
        return new self($key, $type, $default, $options);
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
     * @param $default
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }
}