<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Command;

use Imatic\Bundle\ConfigBundle\Config\ConfigManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigGetCommand extends Command
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
            ->setName('imatic:config:get')
            ->setDescription('Gets a value of the given config variable.')
            ->addArgument('key', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write($this->configManager->getViewValue($input->getArgument('key')));

        return 0;
    }
}
