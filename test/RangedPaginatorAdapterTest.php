<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongoTest;

use PhlyMongo\RangedPaginatorAdapter;

class RangedPaginatorAdapterTest extends AbstractTestCase
{
    /**
     * @var \MongoCursor
     */
    protected $cursor;

    public function setUp()
    {
        parent::setUp();
        $this->cursor  = $this->collection->find();

    }

    public function testCountReturnsTotalNumberOfItems()
    {
        $adapter = new RangedPaginatorAdapter($this->cursor, '');

        $this->assertEquals($this->cursor->count(), $adapter->count());
        $this->assertGreaterThan(1, $adapter->count());
    }

    public function testGetItemsReturnsCursor()
    {
        $adapter = new RangedPaginatorAdapter($this->cursor, 5);
        $test    = $adapter->getItems(5, 5);
        $this->assertSame($this->cursor, $test);
    }

    public function testIteratingReturnedItemsReturnsProperOffsetAndCount()
    {
        $expected = array_slice($this->items, 5, 5);
        $adapter  = new RangedPaginatorAdapter($this->cursor, $expected[0]['_id']);
        $items    = $adapter->getItems(5, 5);
        $test     = [];
        foreach ($items as $item) {
            $test[] = $item;
        }
        $this->assertEquals($expected, $test);
    }
}
