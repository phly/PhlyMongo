<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongo;

class RangedHydratingPaginatorAdapter extends RangedPaginatorAdapter
{
    /**
     * Creates a range based hydrating adapter when using large collections
     *
     * Instead of using MongoCursor::skip, which forces the cursor to walk
     * a ranged based query will start from the current id.
     *
     * @param HydratingMongoCursor $cursor
     * @param mixed|\MongoId $currentId
     */
    public function __construct(HydratingMongoCursor $cursor, $currentId)
    {
        $this->cursor    = $cursor;
        $this->currentId = $currentId;
    }

    public function getItems($offset, $itemCountPerPage)
    {
        //offset is never used in range based
        //kept here to satisfy interface
        $composedCursor = $this->cursor->getCursor();
        $composedCursor->addOption('$min', ['_id' => $this->currentId]);
        $composedCursor->limit($itemCountPerPage);
        return $this->cursor;
    }
}
