<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Infrastructure\Specification\Dotenv;

interface DotenvDriver
{
    /**
     * @param string $data
     *
     * @return array
     */
    public function parse(string $data): array;
}
