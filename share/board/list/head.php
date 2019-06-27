<?php


/**
 * @global $list array
 * @global $bo_table string
 * @global $page int
 * @global $token string
 * @global $sfl
 * @global $stx
 * @global $spt
 * @global $sca string
 * @global $sst
 * @global $sod
 */
$limit = count($list);

if (!isset($useListHead)) {
    $useListHead = true;
}

if (isset($useDelete) && $useDelete) {
    set_session('ss_delete_token', $token = uniqid(time()));
}

// $updateHref = 'write.php?bo_table=' . $bo_table . '&page='.$page.'&w=u&wr_id=';
$updateHref = GNU_URL . "{$bo_table}/write?page={$page}&w=u&wr_id=";
$deleteHref = GNU_URL . 'delete.php?bo_table=' . $bo_table . '&page=' . $page . '&token=' . $token . '&wr_id=';
$categoryHref = GNU_URL . 'board.php?bo_table=' . $bo_table . '&sca=';
$listHref = GNU_URL . "{$bo_table}";

if ($sca) {
    $write_href .= '&sca=' . $sca;
}

// todo: make this
$categories = [];
?>

<?php if ($useListHead): ?>
    <div class="board-list-head">
        <div class="dot-area">
            <h3><span>전체 <b><?= number_format($total_count) ?></b>건</span>, <b><?= $page ?></b>/<?= $total_page ?>쪽</h3>
        </div>
    </div>
<?php endif ?>

<form name="fboardlist" id="fboardlist" action="./board_list_update.php" method="post">
    <input type="hidden" name="bo_table" value="<?= $bo_table ?>">
    <input type="hidden" name="sfl" value="<?= $sfl ?>">
    <input type="hidden" name="stx" value="<?= $stx ?>">
    <input type="hidden" name="spt" value="<?= $spt ?>">
    <input type="hidden" name="sca" value="<?= $sca ?>">
    <input type="hidden" name="sst" value="<?= $sst ?>">
    <input type="hidden" name="sod" value="<?= $sod ?>">
    <input type="hidden" name="page" value="<?= $page ?>">
    <input type="hidden" name="sw" value="">