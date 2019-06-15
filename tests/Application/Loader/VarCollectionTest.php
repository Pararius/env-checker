<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Tests\Application\Loader;

use Pararius\EnvChecker\Application\Loader\VarCollection;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class VarCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_constructed_with_initial_vars()
    {
        $vars = ['foo', 'bar'];
        $collection = new VarCollection($vars);

        Assert::assertEquals($vars, $collection->all());
    }

    /**
     * @test
     */
    public function it_returns_all_names()
    {
        $collection = new VarCollection(['foo']);
        $collection->add('bar');

        Assert::assertEquals(['foo', 'bar'], $collection->all());
    }

    /**
     * @test
     */
    public function it_can_tell_if_a_name_has_been_added()
    {
        $collection = new VarCollection(['foo']);

        Assert::assertTrue($collection->has('foo'));
        Assert::assertFalse($collection->has('bar'));
    }

    /**
     * @test
     */
    public function it_can_differentiate_with_another_collection_as_a_new_instance()
    {
        $collection1 = new VarCollection(['foo', 'bar']);
        $collection2 = new VarCollection(['watermelon']);
        $diffCollection = $collection1->diff($collection2);

        Assert::assertEquals(['foo', 'bar'], $diffCollection->all());
        Assert::assertNotSame($collection1, $diffCollection);
    }

    /**
     * @test
     */
    public function it_can_combine_with_another_collection_as_a_new_instance()
    {
        $collection1 = new VarCollection(['foo', 'bar']);
        $collection2 = new VarCollection(['watermelon']);
        $combinedCollection = $collection1->combine($collection2);

        Assert::assertEquals(['foo', 'bar', 'watermelon'], $combinedCollection->all());
        Assert::assertNotSame($collection1, $combinedCollection);
    }
}