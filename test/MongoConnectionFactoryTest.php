<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

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

        if (class_exists('MongoClient')) {
            $this->assertInstanceOf('MongoClient', $mongo);
        } else {
            $this->assertInstanceOf('Mongo', $mongo);
        }
    }

    public function testFactoryWillCreateAMongoInstanceBasedOnParameters()
    {
        $factory = new MongoConnectionFactory('mongodb://localhost:27017', ['connect' => false]);
        $mongo   = $factory->createService($this->services);
        if (class_exists('MongoClient')) {
            $this->assertInstanceOf('MongoClient', $mongo);
        } else {
            $this->assertInstanceOf('Mongo', $mongo);
        }
    }
}
