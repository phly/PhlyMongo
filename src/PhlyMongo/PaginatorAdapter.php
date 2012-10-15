<?php

namespace PhlyMongo;

use MongoCursor;
use Zend\Paginator\Adapter\AdapterInterface;

class PaginatorAdapter implements AdapterInterface
{
    protected $cursor;

    public function __construct(MongoCursor $cursor)
    {
        $this->cursor = $cursor;
    }

    public function count()
    {
        return $this->cursor->count();
    }

    public function getItems($offset, $itemCountPerPage)
    {
        $this->cursor->skip($offset);
        $this->cursor->limit($itemCountPerPage);
        return $this->cursor;
    }
}
