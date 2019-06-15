<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Presentation\Cli\Console\Command;

use Humbug\SelfUpdate\Updater;
use PHAR;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class SelfUpdateCommand extends Command
{
    /**
     * @var Updater
     */
    private $updater;

    /**
     * @inheritdoc
     */
    public function __construct(Updater $updater)
    {
        parent::__construct();

        $this->updater = $updater;
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName('self-update')
            ->setAliases(['selfupdate', 'update'])
            ->setDescription(sprintf(
                'Update %s to the most recent stable build.',
                $this->getLocalPharName()
            ))
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->updater->update();

        if ($result) {
            $io->success(
                sprintf(
                    'Your PHAR has been updated from "%s" to "%s".',
                    $this->updater->getOldVersion(),
                    $this->updater->getNewVersion()
                )
            );
        } else {
            $io->success('Your PHAR is already up to date.');
        }

        return 0;
    }

    /**
     * @return string
     */
    private function getLocalPharName(): string
    {
        if ($phar = PHAR::running()) {
            return basename($phar);
        }

        return 'NON-PHAR-FILE';
    }
}
