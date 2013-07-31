<?php
namespace Imatic\Bundle\ConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ConfigRepository")
 */
class Config
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $key;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $value;

    /**
     * @param string|null $key
     * @param string|null $value
     */
    public function __construct($key = null, $value = null)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}