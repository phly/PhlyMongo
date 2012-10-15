<?php

namespace PhlyMongo;

use InvalidArgumentException;
use MongoCursor;
use Zend\Stdlib\Hydrator\HydratorInterface;

class HydratingPaginatorAdapter extends PaginatorAdapter
{
    protected $cursor;
    protected $hydrator;
    protected $prototype;

    public function __construct(HydratingMongoCursor $cursor)
    {
        $this->cursor    = $cursor;
    }

    public function getItems($offset, $itemCountPerPage)
    {
        $composedCursor = $this->cursor->getCursor();
        $composedCursor->skip($offset);
        $composedCursor->limit($itemCountPerPage);
        return $this->cursor;
    }
}
