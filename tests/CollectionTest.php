<?php

namespace Intervention\Image\Tests;

use Intervention\Image\Collection;

class CollectionTest extends TestCase
{
    public function testConstructor()
    {
        $collection = new Collection(['foo', 'bar', 'baz']);
        $this->assertInstanceOf(Collection::class, $collection);

        $collection = Collection::create(['foo', 'bar', 'baz']);
        $this->assertInstanceOf(Collection::class, $collection);
    }

    public function testIterator()
    {
        $collection = new Collection(['foo', 'bar', 'baz']);
        foreach ($collection as $key => $item) {
            switch ($key) {
                case 0:
                    $this->assertEquals('foo', $item);
                    break;

                case 1:
                    $this->assertEquals('bar', $item);
                    break;

                case 2:
                    $this->assertEquals('baz', $item);
                    break;
            }
        }
    }

    public function testCount()
    {
        $collection = new Collection(['foo', 'bar', 'baz']);
        $this->assertEquals(3, $collection->count());
        $this->assertEquals(3, count($collection));
    }

    public function testFirstLast()
    {
        $collection = new Collection(['foo', 'bar', 'baz']);
        $this->assertEquals('foo', $collection->first());
        $this->assertEquals('baz', $collection->last());

        $collection = new Collection();
        $this->assertNull($collection->first());
        $this->assertNull($collection->last());
    }

    public function testPush()
    {
        $collection = new Collection(['foo', 'bar', 'baz']);
        $this->assertEquals(3, $collection->count());
        $result = $collection->push('test');
        $this->assertEquals(4, $collection->count());
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testPushEach()
    {
        $collection = Collection::create()->pushEach(['foo', 'bar', 'baz'], function ($item) {
            return strtoupper($item);
        });
        $this->assertEquals(3, $collection->count());
        $this->assertEquals('FOO', $collection->get(0));
        $this->assertEquals('BAR', $collection->get(1));
        $this->assertEquals('BAZ', $collection->get(2));
    }

    public function testGet()
    {
        $collection = new Collection(['foo', 'bar', 'baz']);
        $this->assertEquals('foo', $collection->get(0));
        $this->assertEquals('bar', $collection->get(1));
        $this->assertEquals('baz', $collection->get(2));
        $this->assertNull($collection->get(3));
    }

    public function testToArray()
    {
        $collection = new Collection(['foo', 'bar', 'baz']);
        $this->assertEquals(['foo', 'bar', 'baz'], $collection->toArray());
    }

    public function testMap(): void
    {
        $collection = new Collection(['FOO', 'BAR', 'BAZ']);
        $mapped = $collection->map(function ($item) {
            return strtolower($item);
        });
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertInstanceOf(Collection::class, $mapped);
        $this->assertEquals(['FOO', 'BAR', 'BAZ'], $collection->toArray());
        $this->assertEquals(['foo', 'bar', 'baz'], $mapped->toArray());
    }
}
