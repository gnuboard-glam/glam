<?php
/**
 * @global $glam Glam\GlamShopAdmin
 **/
require '../../_common.php';

Dot\Http\Request::post();

$jpgCounts = $_POST['jpg'];
$pngCounts = $_POST['png'];
$detailCounts = $_POST['detail'];

foreach ($jpgCounts as $id => $jpgCount) {
	$pngCount = $pngCounts[$id];
	$detailCount = $detailCounts[$id];

	$glam->setShopProduct($id, [
		'cdn_count' => \serialize([
			'jpg' => $jpgCount,
			'png' => $pngCount,
			'detail' => $detailCount
		])
	]);
}

$glam->back();