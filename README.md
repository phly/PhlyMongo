PhlyMongo - ZF2 Module for Mongo Integration
============================================

[![Build Status](https://secure.travis-ci.org/weierophinney/PhlyMongo.png?branch=master)](http://travis-ci.org/weierophinney/PhlyMongo)

PhlyMongo provides the following to assist with Mongo usage in ZF2:

- Hydrating Mongo resultset
- Mongo paginator adapter
- Mongo paginator adapter for hydrating resultsets
- Configurable service factories for the Mongo, MongoDB, and MongoCollection classes

Installation
------------

Simplest is to add the following to `composer.json`:

```javascript
{
    "minimum-stability": "dev",
    "require": {
        "phly/phly-mongo": "dev-master"
    }
}
```

And then run:

```bash
php composer.phar install
```

Alternately, use git to install this as a submodule:

```bash
git submodule add git://github.com/weierophinney/PhlyMongo vendor/PhlyMongo
```

Usage
-----

### Services

In order to remain as flexible as possible, the service factories require that
you pass information to the constructors. As such, you should typically 
configure and setup the factories via your `Module.php` definition:

```php
namespace My;

use PhlyMongo\MongoCollectionFactory;
use PhlyMongo\MongoDbFactory;

class Module
{
    public function getServiceConfig()
    {
        return array('factories' => array(
            'My\Mongo'           => 'PhlyMongo\MongoConnectionFactory',
            'My\MongoDB'         => new MongoDbFactory('my-site', 'My\Mongo'),
            'My\MongoCollection' => new MongoCollectionFactory('some-stuff', 'My\MongoDB'),
        ));
    }
}
```

If you want the server, server options, database, collection, or any service
names to be dynamic, consider wrapping the factories in closures, and passing
in configuration:

```php
namespace My;

use PhlyMongo\MongoCollectionFactory;
use PhlyMongo\MongoConnectionFactory;
use PhlyMongo\MongoDbFactory;

class Module
{
    public function getServiceConfig()
    {
        return array('factories' => array(
            'My\Mongo'           => function ($services) {
                $config = $services->get('config');
                $config = $config['my']['mongo'];
                $factory = new MongoConnectionFactory($config['server'], $config['server_options']);
                return $factory->createService($services);
            },
            // and so on //
        ));
    }
}
```

However, if you need to do this, you might just as easily use the native Mongo
classes.

### Hydrating Cursor

The hydrating cursor is useful as a way to map result sets to objects.

Pass a `MongoCursor` instance to the constructor, along with a hydrator and a
prototype object, and you're set:

```php
use PhlyMongo\HydratingMongoCursor;
use Zend\Stdlib\Hydrator\ObjectProperty;

class Status
{
    public $_id;
    public $name;
    public $email;
    public $status;
}

$resultset = new HydratingMongoCursor(
    $collection->find(),
    new ObjectProperty,
    new Status
);
foreach ($resultset as $status) {
    printf('%s <%s>: %s', $status->name, $status->email, $status->status);
}
```

### Paginator Adapter

The paginator adapter allows you to use a `MongoCursor` with `Zend\Paginator`.

Pass a `MongoCursor` to the constructor, and then pass the adapter to the
paginator instance.

```php
use PhlyMongo\PaginatorAdapter as MongoPaginatorAdapter;
use Zend\Paginator\Paginator;

$adapter   = new MongoPaginatorAdapter($collection->find());
$paginator = new Paginator($adapter);
$paginator->setCurrentPageNumber(5);
$paginator->setItemCountPerPage(10);

foreach ($paginator as $item) {
    // only receiving up to 10 items, starting at offset 50
}
```

### Hydrating Paginator Adapter

This builds on the paginator adapter, and simply alters it to accept
specifically a `PhlyMongo\HydratingMongoCursor` in the constructor, allowing
you to return objects of a specific type during iteration.

```php
use PhlyMongo\HydratingMongoCursor;
use PhlyMongo\HydratingPaginatorAdapter as MongoPaginatorAdapter;
use Zend\Paginator\Paginator;

$adapter   = new MongoPaginatorAdapter(new HydratingMongoCursor(
    $collection->find(),
    new ObjectProperty,
    new Status
));
$paginator = new Paginator($adapter);
$paginator->setCurrentPageNumber(5);
$paginator->setItemCountPerPage(10);

foreach ($paginator as $item) {
    // only receiving up to 10 items, starting at offset 50
    printf('%s <%s>: %s', $status->name, $status->email, $status->status);
}
```
