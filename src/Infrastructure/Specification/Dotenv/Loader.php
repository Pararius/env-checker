<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Infrastructure\Specification\Dotenv;

use Pararius\EnvChecker\Application\EnvVarLoader;
use Pararius\EnvChecker\Application\VarCollection;

final class Loader implements EnvVarLoader
{
    /**
     * @var DotenvDriver
     */
    private $dotenv;

    /**
     * @param DotenvDriver $dotenv
     */
    public function __construct(DotenvDriver $dotenv)
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
