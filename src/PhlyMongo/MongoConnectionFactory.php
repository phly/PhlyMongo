<?php

namespace PhlyMongo;

use Mongo;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MongoConnectionFactory implements FactoryInterface
{
    protected $server  = 'mongodb://localhost:27017';
    protected $options = array('connect' => true);

    public function __construct($server = null, array $options = null)
    {
        if (null !== $server) {
            $this->server = $server;
        }
        if (null !== $options) {
            $this->options = $options;
        }
    }

    public function createService(ServiceLocatorInterface $services)
    {
        return new Mongo($this->server, $this->options);
    }
}
