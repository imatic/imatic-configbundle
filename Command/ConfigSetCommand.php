<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Command;

use Imatic\Bundle\ConfigBundle\Config\ConfigManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigSetCommand extends Command
{
    private ConfigManagerInterface $configManager;

    public function __construct(ConfigManagerInterface $configManager)
    {
        $this->configManager = $configManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('imatic:config:set')
            ->setDescription('Sets a given config variable.')
            ->addArgument('key', InputArgument::REQUIRED)
            ->addArgument('value', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = $input->getArgument('key');
        $value = $input->getArgument('value');

        $this->configManager->setViewValue($key, $value);

        $output->write(sprintf('Config variable <comment>%s</comment> was set to <info>%s</info>.', $key, $value));

        return 0;
    }
}
