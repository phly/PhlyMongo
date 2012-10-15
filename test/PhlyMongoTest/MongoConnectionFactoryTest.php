<?php

namespace PhlyMongoTest;

use PhlyMongo\MongoConnectionFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;

class MongoConnectionFactoryTest extends TestCase
{
    public function setUp()
    {
        if (!extension_loaded('mongo')) {
            $this->markTestSkipped('The mongo extension is required to run the unit tests');
        }
        $this->services = new ServiceManager();
    }

    public function testFactoryCreatesAMongoInstanceWhenNoConstructorParametersProvided()
    {
        $factory = new MongoConnectionFactory();
        $mongo   = $factory->createService($this->services);
        $this->assertInstanceOf('Mongo', $mongo);
    }

    public function testFactoryWillCreateAMongoInstanceBasedOnParameters()
    {
        $factory = new MongoConnectionFactory('mongodb://localhost:27017', array('connect' => false));
        $mongo   = $factory->createService($this->services);
        $this->assertInstanceOf('Mongo', $mongo);
    }
}
