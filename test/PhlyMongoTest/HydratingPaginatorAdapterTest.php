<?php

namespace PhlyMongoTest;

use PhlyMongo\HydratingMongoCursor;
use PhlyMongo\HydratingPaginatorAdapter;
use Zend\Stdlib\Hydrator\ObjectProperty;

class HydratingPaginatorAdapterTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->prototype  = new TestAsset\Foo;
        $this->hydrator   = new ObjectProperty;
        $this->rootCursor = $this->collection->find();
        $this->cursor     = new HydratingMongoCursor($this->rootCursor, $this->hydrator, $this->prototype);
        $this->adapter    = new HydratingPaginatorAdapter($this->cursor);
    }

    public function testCountReturnsTotalNumberOfItems()
    {
        $this->assertEquals($this->rootCursor->count(), $this->adapter->count());
        $this->assertGreaterThan(1, $this->adapter->count());
    }

    public function testGetItemsReturnsCursor()
    {
        $test = $this->adapter->getItems(5, 5);
        $this->assertSame($this->cursor, $test);
    }

    public function testIteratingReturnedItemsReturnsProperOffsetAndCountOfObjectsOfCorrectPrototype()
    {
        $items    = $this->adapter->getItems(5, 5);
        $expected = array_slice($this->items, 5, 5);
        foreach ($expected as $index => $item) {
            $expected[(string) $item['_id']] = $item;
            unset($expected[$index]);
        }
        foreach ($items as $key => $item) {
            $this->assertInstanceOf('PhlyMongoTest\TestAsset\Foo', $item);
            $this->assertEquals($expected[$key], (array) $item);
        }
    }
}
