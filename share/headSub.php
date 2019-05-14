<?php
/**
 * @global $glam Glam\GlamBoard
 */

$glam = \Glam\Glam::that();
$head = &$glam->head;

$head->style(GLAM_CSSDOT . 'reset.css');
$head->style(GLAM_CSSDOT . 'dynamic/frame/1600.css');
$head->style(GLAM_CSSDOT . 'dynamic/grid.css');
$head->style(GLAM_CSSDOT . 'box.css');
$head->style(GLAM_CSSDOT . 'lift.css');
$head->style('https://fonts.googleapis.com/css?family=Noto+Sans+KR');

$head->style(GLAM_CSS . 'shop.css');
$head->style(GNU_THEME_CSS . 'global.css');

if (!isset($g5['title'])) {
	$g5['title'] = $config['cf_title'];
	$g5_head_title = $g5['title'];
} else {
	$g5_head_title = $g5['title']; // 상태바에 표시될 제목
	$g5_head_title .= " | " . $config['cf_title'];
}
