<?php

if (!defined('_GNUBOARD_')) {
    die;
}

$glam->head->script(GLAM_URL . 'js/board/write.js');

function boardWriteFormOpen()
{
    global $action_url, $w, $bo_table, $wr_id, $sca, $sfl, $stx, $spt, $sst, $sod, $page;

    $uniqueId = get_uniqid();
    return <<<HTML
    <form name="fwrite" id="fwrite" action="{$action_url}" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="uid" value="{$uniqueId}">
    <input type="hidden" name="w" value="{$w}">
    <input type="hidden" name="bo_table" value="{$bo_table}">
    <input type="hidden" name="wr_id" value="{$wr_id}">
    <input type="hidden" name="sca" value="{$sca}">
    <input type="hidden" name="sfl" value="{$sfl}">
    <input type="hidden" name="stx" value="{$stx}">
    <input type="hidden" name="spt" value="{$spt}">
    <input type="hidden" name="sst" value="{$sst}">
    <input type="hidden" name="sod" value="{$sod}">
    <input type="hidden" name="page" value="{$page}">
HTML;
}
