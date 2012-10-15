<?php

namespace PhlyMongo;

use MongoDB;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MongoDbFactory implements FactoryInterface
{
    protected $dbName;
    protected $connectionService;

    public function __construct($dbName, $connectionService)
    {
        $this->dbName            = $dbName;
        $this->connectionService = $connectionService;
    }

    public function createService(ServiceLocatorInterface $services)
    {
        $connection = $services->get($this->connectionService);
        return new MongoDB($connection, $this->dbName);
    }
}
