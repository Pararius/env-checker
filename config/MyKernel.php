<?php

declare(strict_types=1);

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class MyKernel extends BaseKernel
{
    /**
     * @inheritdoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/services.yaml');
    }

    /**
     * @inheritdoc
     */
    public function registerBundles()
    {
        return [];
    }
}
