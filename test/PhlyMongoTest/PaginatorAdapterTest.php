<?php

namespace PhlyMongoTest;

use PhlyMongo\PaginatorAdapter;

class PaginatorAdapterTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->cursor  = $this->collection->find();
        $this->adapter = new PaginatorAdapter($this->cursor);
    }

    public function testCountReturnsTotalNumberOfItems()
    {
        $this->assertEquals($this->cursor->count(), $this->adapter->count());
        $this->assertGreaterThan(1, $this->adapter->count());
    }

    public function testGetItemsReturnsCursor()
    {
        $test    = $this->adapter->getItems(5, 5);
        $this->assertSame($this->cursor, $test);
    }

    public function testIteratingReturnedItemsReturnsProperOffsetAndCount()
    {
        $items    = $this->adapter->getItems(5, 5);
        $expected = array_slice($this->items, 5, 5);
        $test     = array();
        foreach ($items as $item) {
            $test[] = $item;
        }
        $this->assertEquals($expected, $test);
    }
}
