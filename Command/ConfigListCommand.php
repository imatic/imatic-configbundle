<?php
namespace Imatic\Bundle\ConfigBundle\Command;

use Imatic\Bundle\ConfigBundle\Manager\ConfigManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\Translator;

class ConfigListCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('imatic:config:list')
            ->setDescription('Lists all available config variables.')
            ->addArgument('filter', InputArgument::OPTIONAL, 'Filters valiable keys by the given filter.')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configManager = $this->getConfigManager();
        $table = $this->getTableHelper();
        $table->setHeaders(['key', 'value', 'type', 'description']);
        $translator = $this->getTranslator();

        foreach ($configManager->getViewValues($input->getArgument('filter')) as $key => $viewValue) {
            $table->addRow([
                $key,
                $viewValue,
                $configManager->getDefinition($key)->getType(),
                $translator->trans($key, [], 'config')
            ]);
        }

        $table->render($output);
    }

    /**
     * @return ConfigManager
     */
    private function getConfigManager()
    {
        return $this->getContainer()->get('imatic_config.manager.config_manager');
    }

    /**
     * @return TableHelper
     */
    private function getTableHelper()
    {
        return $this->getHelper('table');
    }

    /**
     * @return Translator
     */
    private function getTranslator()
    {
        return $this->getContainer()->get('translator');
    }
}