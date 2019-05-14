<?php
/**
 * @global $glam Glam\GlamShopAdmin
 */
require '../../common.php';

$rentalPrefix = $_POST['rentalPrefix'] ?? '';
$rental = $_POST['rental'] ?? '';
$rentalCategories = $_POST['rentalCategories'] ?? [];

$purchasePrefix = $_POST['purchasePrefix'] ?? '';
$purchase = $_POST['purchase'] ?? '';


$glam->setOption('naverShopping', [
	'rentalPrefix' => $rentalPrefix,
	'rental' => $rental,
	'rentalCategories' => $rentalCategories,
	'purchasePrefix' => $purchasePrefix,
	'purchase' => $purchase
]);

$glam->back();
