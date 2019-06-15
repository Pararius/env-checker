<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Infrastructure\Loader;

use Pararius\EnvChecker\Application\Loader\EnvVarLoader;
use Pararius\EnvChecker\Application\Loader\VarCollection;
use Symfony\Component\Dotenv\Dotenv;

final class DotenvLoader implements EnvVarLoader
{
    /**
     * @var Dotenv
     */
    private $dotenv;

    /**
     * @param Dotenv $dotenv
     */
    public function __construct(Dotenv $dotenv)
    {
        $this->dotenv = $dotenv;
    }

    /**
     * @inheritdoc
     */
    public function load(string $path): VarCollection
    {
        $vars = $this->dotenv->parse(file_get_contents($path));

        return new VarCollection(array_keys($vars));
    }
}
