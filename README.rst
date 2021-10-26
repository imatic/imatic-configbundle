ImaticConfigBundle
==================

ImaticConfigBundle provides an easy way to manage configuration stored in the database.

## Installation

```bash
composer require imatic/config-bundle
```

### Enable the bundle

If you don't use Symfony Flex, register the bundle manually

```php
// in config/bundles.php
return [
    // ...
    Imatic\Bundle\ConfigBundle\ImaticConfigBundle::class => ['all' => true],
];
```

### Create config table

```php
// src/Entity/Config.php
<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Config extends \Imatic\Bundle\ConfigBundle\Entity\Config
{
}
```

If table is not defined in default schema, you need to specify your entity in bundle configuration.

```yaml
#config/packages/imatic_config.yaml
imatic_config:
    entity_class: App\Entity\Config
```

## Usage

Configuration is defined in class extending ProviderInterface.

```php
// src/Config/ConfigProvider.php
namespace App\Config;

use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Imatic\Bundle\ConfigBundle\Provider\ProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

final class ConfigProvider implements ProviderInterface
{
    public function getDefinitions(): array
    {
        return [
            new Definition('value', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ]),
        ];
    }
}
```

Configuration must be registered as services and tagged with the `imatic_config.provider` tag. If you're using the default Symfony services.yaml configuration, this is already done for you, thanks to autoconfiguration.

You can organize configuration in multiple files, with different aliases. Every configuration value is prefixed with alias name. Default alias name is `config`.

### Reading configuration

To read config value get a ConfigManager instance by type-hinting ConfigManagerInterface.

```php
// src/Controller/ConfigController.php
namespace App\Controller;

use Imatic\Bundle\ConfigBundle\Config\ConfigManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ConfigController extends AbstractController
{
    public function getConfig(ConfigManagerInterface $config)
    {
        $value = $config->getValue('config.value');
        // ...
    }
}
```

### Usage in Twig templates

The Twig extension in this bundle supports reading configuration directly in your template.

```twig
{{ imatic_config_value('config.value') }}
```

### Manage configuration

All you need to do is register bundle routing configuration and management will be available under `imatic_config_config` route.

```yaml
#config/routes/imatic_config.yaml
imatic_config:
    resource: '@ImaticConfigBundle/Resources/config/routing.xml'
    prefix: /
```

It excepts that your project base template is `base.html.twig`. If you use another base template you need to specify it in bundle configuration.

```yaml
#config/packages/imatic_config.yaml
imatic_config:
    templates:
        base: 'base/template.html.twig'
```
