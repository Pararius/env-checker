<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Application\Loader;

final class VarCollection
{
    /**
     * @var array
     */
    private $vars;

    /**
     * @param array $vars
     */
    public function __construct(array $vars = [])
    {
        $this->vars = $vars;
    }

    /**
     * @param VarCollection $combineWith
     *
     * @return VarCollection
     */
    public function combine(VarCollection $combineWith): self
    {
        return new static(array_unique(array_merge($this->all(), $combineWith->all())));
    }

    /**
     * @param string $name
     */
    public function add(string $name): void
    {
        $this->vars[] = $name;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->vars;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return in_array($name, $this->vars);
    }

    /**
     * @param VarCollection $compareWith
     *
     * @return VarCollection
     */
    public function diff(VarCollection $compareWith): self
    {
        return new static(array_diff($this->vars, $compareWith->all()));
    }
}
