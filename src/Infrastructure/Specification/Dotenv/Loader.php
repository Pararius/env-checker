<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Infrastructure\Specification\Dotenv;

use Pararius\EnvChecker\Application\EnvVarLoader;
use Pararius\EnvChecker\Application\VarCollection;
use Symfony\Component\Dotenv\Dotenv;

final class Loader implements EnvVarLoader
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
