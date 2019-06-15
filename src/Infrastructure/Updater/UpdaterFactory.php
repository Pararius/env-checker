<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Infrastructure\Updater;

use Humbug\SelfUpdate\Updater;

class UpdaterFactory
{
    public function create(): Updater
    {
        $filename = pathinfo(__FILE__, PATHINFO_FILENAME);

        $updater = new Updater('bin/'.$filename.'.phar', false);
        $updater->setStrategy(Updater::STRATEGY_GITHUB);

        /* @var GithubStrategy $strategy */
        $strategy = $updater->getStrategy();
        $strategy->setPackageName('pararius/env-checker');
        $strategy->setPharName($filename.'.phar');
    }
}
