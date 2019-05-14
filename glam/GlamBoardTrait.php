<?php
namespace Glam;

trait GlamBoardTrait {
    /**
     * @var string
     */
    // public $_tableNav = G5_TABLE_PREFIX . 'nav';
    public $_tableNav;

	function glam_board(){
	    $this->_tableNav = &$this->g5['menu_table'];
	}
}