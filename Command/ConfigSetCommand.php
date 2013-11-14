<?php
namespace Imatic\Bundle\ConfigBundle\Command;

use Imatic\Bundle\ConfigBundle\Config\ConfigManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigSetCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('imatic:config:set')
            ->setDescription('Sets a given config variable.')
            ->addArgument('key', InputArgument::REQUIRED)
            ->addArgument('value', InputArgument::REQUIRED);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument('key');
        $value = $input->getArgument('value');
        $this->getConfigManager()->setViewValue($key, $value);
        $output->write(sprintf('Config variable <comment>%s</comment> was set to <info>%s</info>.', $key, $value));
    }

    /**
     * @return ConfigManager
     */
    private function getConfigManager()
    {
        return $this->getContainer()->get('imatic_config.config_manager');
    }
}