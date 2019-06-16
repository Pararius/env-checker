<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Tests\Infrastructure\Implementation\Kubernetes;

use ArrayIterator;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Pararius\EnvChecker\Infrastructure\Implementation\Kubernetes\Loader;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use Symfony\Component\Finder\Finder;

final class ProphesizedFinder extends Finder
{
    /**
     * Returns prophesized file info objects
     *
     * @inheritdoc
     */
    public function getIterator()
    {
        $prophet = new Prophet();

        foreach (parent::getIterator() as $fileInfo) {
            $prophecy = $prophet->prophesize(get_class($fileInfo));
            $prophecy->__call('getRealPath', [])->shouldBeCalled()->willReturn($fileInfo->getPathname());

            yield $prophecy->reveal();
        }
    }
}

final class LoaderTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->root = vfsStream::setup('fakeDir');
        $this->root = vfsStream::copyFromFileSystem(__DIR__ . '/../../../../examples/implementation', $this->root);
    }

    /**
     * @test
     */
    public function it_can_load_variables_from_kubernetes_configurations()
    {
        $basePath = $this->root->url();
        $finder = new ProphesizedFinder();
        $loader = new Loader($finder);
        $collection = $loader->load($basePath);

        $expected = [
            'DEFINED_IN_BOTH_1',
            'DEFINED_IN_BOTH_2',
            'DEFINED_IN_BOTH_3',
            'DEFINED_IN_BOTH_4',
            'DEFINED_IN_IMPLEMENTATION',
        ];

        Assert::assertEqualsCanonicalizing($expected, $collection->all());
    }
}
