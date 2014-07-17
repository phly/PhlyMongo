<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace PhlyMongo;

use MongoCollection;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MongoCollectionFactory implements FactoryInterface
{
    protected $collectionName;
    protected $dbService;

    public function __construct($collectionName, $dbService)
    {
        $this->collectionName    = $collectionName;
        $this->dbService         = $dbService;
    }

    public function createService(ServiceLocatorInterface $services)
    {
        $db = $services->get($this->dbService);
        return new MongoCollection($db, $this->collectionName);
    }
}
