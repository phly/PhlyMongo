<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongo;

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
