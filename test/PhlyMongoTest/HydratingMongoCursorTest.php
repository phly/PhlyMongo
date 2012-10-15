<?php

namespace PhlyMongoTest;

use PhlyMongo\HydratingMongoCursor;
use Zend\Stdlib\Hydrator\ObjectProperty;

class HydratingMongoCursorTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->hydrator   = new ObjectProperty();
        $this->prototype  = new TestAsset\Foo;
    }

    protected function seedCollection()
    {
        $this->collection->drop();
        $this->authors = $authors = array(
            'Matthew',
            'Mark',
            'Luke',
            'John',
        );
        for ($i = 0; $i < 100; $i += 1) {
            $authorIndex = array_rand($authors);
            $title       = uniqid();
            $data = array(
                'title'   => $title,
                'author'  => $authors[$authorIndex],
                'content' => str_repeat($title, $i + 1),
            );
            $this->collection->insert($data);
        }
    }

    public function testConstructorRaisesExceptionOnInvalidPrototype()
    {
        $rootCursor = $this->collection->find();
        $this->setExpectedException('InvalidArgumentException');
        $cursor = new HydratingMongoCursor($rootCursor, $this->hydrator, array());
    }

    public function testRootCursorIsAccessibleAfterInstantiation()
    {
        $rootCursor = $this->collection->find();
        $cursor = new HydratingMongoCursor($rootCursor, $this->hydrator, $this->prototype);
        $this->assertSame($rootCursor, $cursor->getCursor());
    }

    public function tetHydratorIsAccessibleAfterInstantiation()
    {
        $rootCursor = $this->collection->find();
        $cursor = new HydratingMongoCursor($rootCursor, $this->hydrator, $this->prototype);
        $this->assertSame($this->hydrator, $cursor->getHydrator());
    }

    public function tetPrototypeIsAccessibleAfterInstantiation()
    {
        $rootCursor = $this->collection->find();
        $cursor = new HydratingMongoCursor($rootCursor, $this->hydrator, $this->prototype);
        $this->assertSame($this->prototype, $cursor->getPrototype());
    }

    public function testCursorIsCountable()
    {
        $rootCursor = $this->collection->find();
        $cursor     = new HydratingMongoCursor($rootCursor, $this->hydrator, $this->prototype);

        $rootCount = $rootCursor->count();
        $testCount = count($cursor);
        $this->assertEquals($rootCount, $testCount, "Expected $rootCount did not match test $testCount");
        $this->assertGreaterThan(0, $testCount);
    }

    public function testIterationReturnsClonesOfPrototype()
    {
        $rootCursor = $this->collection->find();
        $cursor = new HydratingMongoCursor($rootCursor, $this->hydrator, $this->prototype);
        foreach ($cursor as $item) {
            $this->assertInstanceOf('PhlyMongoTest\TestAsset\Foo', $item);
            $this->assertInstanceOf('MongoId', $item->_id);
            $this->assertFalse(empty($item->title));
            $this->assertContains($item->author, $this->authors);
            $this->assertContains($item->title, $item->content);
        }
    }
}
