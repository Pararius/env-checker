<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Infrastructure\Specification\Dotenv;

use Symfony\Component\Dotenv\Dotenv;

final class RealDotenvDriver implements DotenvDriver
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
    public function parse(string $data): array
    {
        return $this->dotenv->parse($data);
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->dotenv, $name], $arguments);
    }
}
