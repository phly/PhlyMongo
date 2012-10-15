<?php

namespace PhlyMongoTest;

use PhlyMongo\PaginatorAdapter;
use Zend\Paginator\Paginator;

class PaginatorAdapterTest extends AbstractTestCase
{
    public function testCountReturnsTotalNumberOfItems()
    {
        $cursor  = $this->collection->find();
        $adapter = new PaginatorAdapter($cursor);
        $this->assertEquals($cursor->count(), $adapter->count());
        $this->assertGreaterThan(1, $adapter->count());
    }

    public function testGetItemsReturnsCursor()
    {
        $cursor  = $this->collection->find();
        $adapter = new PaginatorAdapter($cursor);
        $test    = $adapter->getItems(5, 5);
        $this->assertSame($cursor, $test);
    }

    public function testIteratingReturnedItemsReturnsProperOffsetAndCount()
    {
        $cursor   = $this->collection->find();
        $adapter  = new PaginatorAdapter($cursor);
        $items    = $adapter->getItems(5, 5);
        $expected = array_slice($this->items, 5, 5);
        $test     = array();
        foreach ($items as $item) {
            $test[] = $item;
        }
        $this->assertEquals($expected, $test);
    }
}
