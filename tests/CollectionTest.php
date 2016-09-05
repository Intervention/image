<?php

use Intervention\Image\Collection;

class CollectionTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $collection = new Collection(array('one', 'two', 'three'));
        $this->assertInstanceOf('Intervention\Image\Collection', $collection);
    }

    public function testToString()
    {
        $collection = new Collection(array('one', 'two', 'three'));
        $this->assertEquals('one', (string) $collection);
    }

    public function testCount()
    {
        $collection = new Collection(array('one', 'two', 'three'));
        $this->assertEquals(3, $collection->count());
    }

    public function testFirst()
    {
        $collection = new Collection(array('one', 'two', 'three'));
        $this->assertEquals('one', $collection->first());

        $collection = new Collection(array(array('foo', 'bar'), 'two', 'three'));
        $this->assertEquals(array('foo', 'bar'), $collection->first());
    }

    public function testLast()
    {
        $collection = new Collection(array('one', 'two', 'three'));
        $this->assertEquals('three', $collection->last());

        $collection = new Collection(array('one', 'two', array('foo', 'bar')));
        $this->assertEquals(array('foo', 'bar'), $collection->last());
    }
}
