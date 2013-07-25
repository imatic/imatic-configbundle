<?php
namespace Imatic\Bundle\ConfigBundle\Entity;

interface ConfigManagerInterface
{
    /**
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value);

    /**
     * @param string|null $pattern
     * @return array
     */
    public function all($pattern = null);
}