<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Provider;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class Definition
{
    private string $key;

    private string $type;

    /**
     * @var mixed
     */
    private $default;

    private array $options;

    /**
     * @param mixed $default
     */
    public function __construct(string $key, string $type, $default = null, array $options = [])
    {
        $this->type = $type;
        $this->key = $key;
        $this->default = $default;
        $this->options = $options;
    }

    /**
     * @param mixed $default
     */
    public static function create(string $key, string $type = TextType::class, $default = null, array $options = []): self
    {
        return new self($key, $type, $default, $options);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getType(): string
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
     * @param mixed $default
     */
    public function setDefault($default): self
    {
        $this->default = $default;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }
}
