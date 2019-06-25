<?php

namespace Glam;

trait GlamBoardTrait
{

    /**
     * cache of board id list
     */
    protected $_boardList;

    function glam_board()
    {
    }

    function getBoardList()
    {
        $boardList = &$this->_boardList;
        if (!$boardList) {
            $cache = &$this->cache;
            $cached = $cache->get('boardList');
            if (!$cached) {
                $ids = $this->db->selected($this->g5['board_table'], 'bo_table')
                    ->column('bo_table');

                $cached = $ids;
                $cache->set('boardList', $cached);
            }
            $boardList = $cached;
        }
        return $boardList;
    }
}