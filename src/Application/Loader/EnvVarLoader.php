<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Application\Loader;

interface EnvVarLoader
{
    /**
     * Returns a collection of all environment variable names that have been detected.
     *
     * @param string $path
     *
     * @return VarCollection
     */
    public function load(string $path): VarCollection;
}