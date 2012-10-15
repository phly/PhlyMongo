<?php

namespace PhlyMongoTest;

use PhlyMongo\MongoCollectionFactory;
use PhlyMongo\MongoConnectionFactory;
use PhlyMongo\MongoDbFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;

class MongoCollectionFactoryTest extends TestCase
{
    public function setUp()
    {
        if (!extension_loaded('mongo')) {
            $this->markTestSkipped('The mongo extension is required to run the unit tests');
        }
        $this->services = new ServiceManager();
        $this->services->setFactory('PhlyMongoTest\Mongo', new MongoConnectionFactory());
        $this->services->setFactory('PhlyMongoTest\MongoDB', new MongoDbFactory('test', 'PhlyMongoTest\Mongo'));
    }

    public function testFactoryCreatesAMongoCollectionInstance()
    {
        $factory    = new MongoCollectionFactory('test', 'PhlyMongoTest\MongoDB');
        $collection = $factory->createService($this->services);
        $this->assertInstanceOf('MongoCollection', $collection);
        $this->assertEquals('test', $collection->getName());
    }
}
