<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongo;

use MongoCursor;
use Zend\Paginator\Adapter\AdapterInterface;

class RangedPaginatorAdapter implements AdapterInterface
{
    /**
     * @var \MongoCursor
     */
    protected $cursor;

    /**
     * @var mixed|\MongoId
     */
    protected $currentId;

    /**
     * Creates a range based adapter when using large collections
     *
     * Instead of using MongoCursor::skip, which forces the cursor to walk
     * a ranged based query will start from the current id.
     *
     * @param MongoCursor $cursor
     * @param $currentId
     */
    public function __construct(MongoCursor $cursor, $currentId)
    {
        $this->cursor    = $cursor;
        $this->currentId = $currentId;
    }

    public function count()
    {
        return $this->cursor->count();
    }

    public function getItems($offset, $itemCountPerPage)
    {
        //offset is never used in range based
        //kept here to satisfy interface
        $this->cursor->addOption('$min', ['_id' => $this->currentId]);
        $this->cursor->limit($itemCountPerPage);
        return $this->cursor;
    }
}
