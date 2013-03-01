<?php

namespace PhlyMongo;

use Countable;
use InvalidArgumentException;
use Iterator;
use MongoCursor;
use Zend\Stdlib\Hydrator\HydratorInterface;

class HydratingMongoCursor implements Countable, Iterator
{
    protected $cursor;
    protected $hydrator;
    protected $prototype;

    public function __construct(MongoCursor $cursor, HydratorInterface $hydrator, $prototype)
    {
        $this->cursor   = $cursor;
        $this->hydrator = $hydrator;

        if (!is_object($prototype)) {
            throw new InvalidArgumentException(sprintf(
                'Prototype must be an object; received "%s"',
                gettype($prototype)
            ));
        }
        $this->prototype = $prototype;
    }

    public function getCursor()
    {
        return $this->cursor;
    }

    public function getHydrator()
    {
        return $this->hydrator;
    }

    public function getPrototype()
    {
        return $this->prototype;
    }

    public function count()
    {
        return $this->cursor->count();
    }

    public function current()
    {
        $result = $this->cursor->current();
        if (!is_array($result)) {
            return $result;
        }

        return $this->hydrator->hydrate($result, clone $this->prototype);
    }

    public function key()
    {
        return $this->cursor->key();
    }

    public function next()
    {
        return $this->cursor->next();
    }

    public function rewind()
    {
        return $this->cursor->rewind();
    }

    public function valid()
    {
        return $this->cursor->valid();
    }
}
