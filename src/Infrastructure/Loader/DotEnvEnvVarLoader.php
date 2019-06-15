<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Infrastructure\Loader;

use Pararius\EnvChecker\Application\Loader\EnvVarLoader;
use Pararius\EnvChecker\Application\Loader\VarCollection;
use Symfony\Component\Dotenv\Dotenv;

final class DotEnvEnvVarLoader implements EnvVarLoader
{
    /**
     * @inheritdoc
     */
    public function load(string $path): VarCollection
    {
        $engine = new Dotenv();
        $vars = $engine->parse(file_get_contents($path));

        return array_keys($vars);
    }
}
