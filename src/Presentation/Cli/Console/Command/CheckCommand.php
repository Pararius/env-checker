<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Presentation\Cli\Console\Command;

use Pararius\EnvChecker\Application\Loader\EnvVarLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckCommand extends Command
{
    private const GREEN_CHECKMARK = '<fg=green>✓</fg=green>';
    private const RED_CROSS = '<fg=red>✕</fg=red>';

    /**
     * @var EnvVarLoader
     */
    private $specificationLoader;

    /**
     * @var EnvVarLoader
     */
    private $implementationLoader;

    /**
     * @param EnvVarLoader $specificationLoader
     * @param EnvVarLoader $implementationLoader
     */
    public function __construct(EnvVarLoader $specificationLoader, EnvVarLoader $implementationLoader)
    {
        parent::__construct();

        $this->specificationLoader = $specificationLoader;
        $this->implementationLoader = $implementationLoader;
    }

    protected function configure()
    {
        $this->setName('check');

        $this->addArgument(
            'path-to-specification',
            InputArgument::REQUIRED,
            'The path to the .env or .env.dist file of your project.'
        );

        $this->addArgument(
            'path-to-implementation',
            InputArgument::REQUIRED,
            'The path to the directory containing your project\'s deployment (i.e. k8s yamls).'
        );

        $this->setDescription(
            'Checks whether all variables defined in your (project) specification, '.
            'are implemented by your (deployment) implementation files'
        );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $specCollection = $this->specificationLoader->load($input->getArgument('path-to-specification'));
        $implCollection = $this->implementationLoader->load($input->getArgument('path-to-implementation'));

        $table = new Table($output);
        $table->setHeaders(['Variable', 'In specification', 'In implementation']);
        $combined = $specCollection->combine($implCollection);

        foreach ($combined->all() as $variable) {
            $spec = $specCollection->has($variable) ? self::GREEN_CHECKMARK : self::RED_CROSS;
            $impl = $implCollection->has($variable) ? self::GREEN_CHECKMARK : self::RED_CROSS;

            $table->addRow([$variable, $spec, $impl]);
        }

        $table->render();

        $diff = $specCollection->diff($implCollection);

        if (!empty($diff)) {
            $output->writeln(sprintf(
                '<error>Uh oh! Not all variables have been defined on the implementation side (%s).</error>',
                $input->getArgument('path-to-implementation')
            ));

            return 1;
        }

        $output->writeln('<info>Great! All the expected variables have been defined on the implementation side!</info>');

        return 0;
    }
}
