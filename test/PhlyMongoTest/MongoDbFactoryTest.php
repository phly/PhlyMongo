<?php

namespace PhlyMongoTest;

use PhlyMongo\MongoConnectionFactory;
use PhlyMongo\MongoDbFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;

class MongoDbFactoryTest extends TestCase
{
    public function setUp()
    {
        if (!extension_loaded('mongo')) {
            $this->markTestSkipped('The mongo extension is required to run the unit tests');
        }
        $this->services = new ServiceManager();
        $this->services->setFactory('PhlyMongoTest\Mongo', new MongoConnectionFactory());
    }

    public function testFactoryCreatesAMongoDBInstance()
    {
        $factory = new MongoDbFactory('test', 'PhlyMongoTest\Mongo');
        $db      = $factory->createService($this->services);
        $this->assertInstanceOf('MongoDB', $db);
        $this->assertEquals('test', (string) $db);
    }
}
