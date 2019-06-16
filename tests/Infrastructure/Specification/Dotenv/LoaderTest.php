<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Tests\Infrastructure\Specification\Dotenv;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Pararius\EnvChecker\Infrastructure\Specification\Dotenv\DotenvDriver;
use Pararius\EnvChecker\Infrastructure\Specification\Dotenv\Loader;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

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
        $this->root = vfsStream::copyFromFileSystem(__DIR__ . '/../../../../examples/specification', $this->root);
    }

    /**
     * @test
     */
    public function it_can_load_variables_from_an_env_file()
    {
        $basePath = $this->root->getChild('.env.dist')->url();
        $expected = [
            'DEFINED_IN_BOTH_1',
            'DEFINED_IN_BOTH_2',
            'DEFINED_IN_BOTH_3',
            'DEFINED_IN_BOTH_4',
            'DEFINED_IN_IMPLEMENTATION',
        ];

        $parseResult = array_combine($expected, array_fill(0, count($expected), []));

        $driver = $this->prophesize(DotenvDriver::class);
        $driver
            ->__call('parse', [Argument::any()])
            ->shouldBeCalled()
            ->willReturn($parseResult)
        ;

        $loader = new Loader($driver->reveal());
        $collection = $loader->load($basePath);

        Assert::assertEqualsCanonicalizing($expected, $collection->all());
    }
}
