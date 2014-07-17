<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongo;

use MongoGridFS;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MongoGridFsFactory implements FactoryInterface
{
    protected $prefix;
    protected $dbService;

    public function __construct($prefix, $dbService)
    {
        $this->prefix    = $prefix;
        $this->dbService = $dbService;
    }

    public function createService(ServiceLocatorInterface $services)
    {
        $db = $services->get($this->dbService);
        return new MongoGridFS($db, $this->prefix);
    }
}
