<?php
namespace Imatic\Bundle\ConfigBundle\Command;

use Imatic\Bundle\ConfigBundle\Manager\ConfigManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigGetCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('imatic:config:get')
            ->setDescription('Gets a value of the given config variable.')
            ->addArgument('key', InputArgument::REQUIRED)
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write($this->getConfigManager()->getViewValue($input->getArgument('key')));
    }

    /**
     * @return ConfigManager
     */
    private function getConfigManager()
    {
        return $this->getContainer()->get('imatic_config.manager.config_manager');
    }
}