<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Twig;

use Imatic\Bundle\ConfigBundle\Config\ConfigManagerInterface;
use Twig\Extension\RuntimeExtensionInterface;

class ImaticConfigRuntime implements RuntimeExtensionInterface
{
    private ConfigManagerInterface $manager;

    public function __construct(ConfigManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return mixed
     */
    public function getValue(string $key)
    {
        return $this->manager->getValue($key);
    }
}
