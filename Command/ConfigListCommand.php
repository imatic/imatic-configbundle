<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Command;

use Imatic\Bundle\ConfigBundle\Config\ConfigManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigListCommand extends Command
{
    private ConfigManagerInterface $configManager;

    private TranslatorInterface $translator;

    public function __construct(ConfigManagerInterface $configManager, TranslatorInterface $translator)
    {
        $this->configManager = $configManager;
        $this->translator = $translator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('imatic:config:list')
            ->setDescription('Lists all available config variables.')
            ->addArgument('filter', InputArgument::OPTIONAL, 'Filters variable keys by the given filter.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table->setHeaders(['key', 'value', 'type', 'description']);

        foreach ($this->configManager->getViewValues($input->getArgument('filter')) as $key => $viewValue) {
            $table->addRow([
                $key,
                $viewValue,
                $this->configManager->getDefinition($key)->getType(),
                $this->translator->trans($key, [], 'config'),
            ]);
        }

        $table->render();

        return 0;
    }
}
