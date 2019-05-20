<?php
/**
 * @global $glam Glam\GlamShopAdmin
 */
require '../common.php';

$ids = $_POST['id'] ?? [];
$orders = $_POST['order'] ?? [];
$uses = $_POST['use'] ?? [];
$soldouts = $_POST['soldout'] ?? [];
$type1s = $_POST['type1'] ?? [];
$type2s = $_POST['type2'] ?? [];
$type3s = $_POST['type3'] ?? [];
$type4s = $_POST['type4'] ?? [];
$type5s = $_POST['type5'] ?? [];

foreach ($ids as $id) {
	$order = $orders[$id] ?? 0;
	$use = isset($uses[$id]) ? 1 : 0;
	$soldout = isset($soldouts[$id]) ? 1 : 0;
	$type1 = isset($type1s[$id]) ? 1 : 0;
	$type2 = isset($type2s[$id]) ? 1 : 0;
	$type3 = isset($type3s[$id]) ? 1 : 0;
	$type4 = isset($type4s[$id]) ? 1 : 0;
	$type5 = isset($type5s[$id]) ? 1 : 0;

	$glam->setShopProduct($id, [
		'it_order' => $order,
		'it_use'     => $use,
		'it_soldout' => $soldout,
		'it_type1'   => $type1,
		'it_type2'   => $type2,
		'it_type3'   => $type3,
		'it_type4'   => $type4,
		'it_type5'   => $type5,
	]);
}

Dot\Dot::redirect($_SERVER['HTTP_REFERER']);