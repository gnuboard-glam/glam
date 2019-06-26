<?php
namespace Glam;

/**
 * @global $glam Glam\GlamBoard
 */

if (!isset($g5['title'])) {
    $g5['title'] = $config['cf_title'];
    $g5_head_title = $g5['title'];
} else {
    $g5_head_title = $g5['title']; // 상태바에 표시될 제목
    $g5_head_title .= " | " . $config['cf_title'];
}

$glam = Glam::that();
$glam->head->scripts
    ->url(GNU_JS . 'jquery-1.12.4.min.js')
    ->url(GNU_JS . 'common.js');

