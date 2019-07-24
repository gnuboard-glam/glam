<?php


if($glam->is54){
    $listHref = GNU_URL . "{$bo_table}";
    $writeHref = $listHref . '/write';
    // todo check
    $updateHref = $writeHref . "?w=u";
}else{
    $categoryHref = GNU_URL . 'board.php?bo_table=' . $bo_table . '&sca=';
    $listHref = GNU_URL . 'bbs/board.php?bo_table=' . $bo_table;
    $writeHref = GNU_URL . 'bbs/write.php?bo_table=' . $bo_table;
    $updateHref = GNU_URL . 'bbs/write.php?bo_table=' . $bo_table . '&w=u&wr_id=';
    $deleteHref = GNU_URL . 'delete.php?bo_table=' . $bo_table . '&page=' . $page . '&token=' . $token . '&wr_id=';
}
