<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass(repositoryClass=ConfigRepository::class)
 */
class Config
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private ?string $key;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $value;

    public function __construct(string $key = null, string $value = null)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
