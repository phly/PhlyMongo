<?php

namespace PhlyMongoTest;

use Mongo;
use MongoCollection;
use MongoDB;
use PhlyMongo\HydratingMongoCursor;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Stdlib\Hydrator\ObjectProperty;

abstract class AbstractTestCase extends TestCase
{
    public function setUp()
    {
        if (!extension_loaded('mongo')) {
            $this->markTestSkipped('Mongo extension is required to run tests');
        }

        $services   = Bootstrap::getServiceManager();
        $config     = $services->get('ApplicationConfig');
        $config     = $config['mongo'];
        $mongo      = new Mongo($config['server'], $config['server_options']);
        $db         = new MongoDB($mongo, $config['db']);
        $collection = new MongoCollection($db, $config['collection']);

        $this->mongo      = $mongo;
        $this->db         = $db;
        $this->collection = $collection;

        $this->seedCollection();
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
        $this->items = array();
        for ($i = 0; $i < 100; $i += 1) {
            $authorIndex = array_rand($authors);
            $title       = uniqid();
            $data = array(
                'title'   => $title,
                'author'  => $authors[$authorIndex],
                'content' => str_repeat($title, $i + 1),
            );
            $this->collection->insert($data);
            $this->items[] = $data;
        }
    }
}
