<?php
/**
 * @global $glam Glam\GlamShopAdmin
 */

use Glam\NaverShopProducts;

require '../../common.php';
require './NaverShopProducts.php';

$ns = new NaverShopProducts(\GLAM . 'shop/ep_all.txt');

$products = $glam->getShopProducts();

$scheme = $_SERVER['REQUEST_SCHEME'] . '://';
$server = $scheme . $_SERVER['SERVER_NAME'] . \rtrim(\GNU_URL, '/');

$naverShopping = $glam->getOption('naverShopping');
$rentalPrefix = &$naverShopping['rentalPrefix'];
$rental = &$naverShopping['rental'];
$rentalCategories = &$naverShopping['rentalCategories'];
$purchasePrefix = &$naverShopping['purchasePrefix'];
$purchase = &$naverShopping['purchase'];

foreach ($products as $product) {
	$options = $product['prices']['options'];


	$price = $product['it_price'];
	$cate1 = $product['category1']['ca_name'] ?? '';
	$caid1 = $cate1 ? $product['category1']['ca_id'] : '';

	if ($price) {
		$name = $purchase;
		if ($purchasePrefix) {
			$name = $purchasePrefix . ' ' . $name;
		}
	} else {
		$name = isset($rentalCategories[$caid1]) && $rentalCategories[$caid1] ?
			$rentalCategories[$caid1] :
			$rental;

		if ($rentalPrefix) {
			$name = $rentalPrefix . ' ' . $name;
		}
	}

	$name = str_replace(
		[
			'$name',
			'$model',
			'$category'
		],
		[
			$product['it_name'],
			$product['it_model'],
			$cate1
		],
		$name
	);

	if ($price) {

	} else {
		$name = \trim($name);

		foreach ($options as $key => $option) {
			$optionId = $key + 1;
			$optionName = \str_replace('$term', $option['term'], $name);
			$ns->add([
				'mapid' => $product['it_id'] . 'X' . $optionId,
				'pname' => $optionName,
				'price' => $option['year1'],
				'pgurl' => $server . $product['detailUrl'] . '#' . $optionId,
				'igurl' => $server . $product['cdn_images']['jpg'][0],
				'cate1' => $cate1,
				'cate2' => $product['category2']['ca_name'] ?? '',
				'cate3' => $product['category3']['ca_name'] ?? '',
				'caid1' => $caid1,
				'caid2' => $product['category2']['ca_id'] ?? '',
				'caid3' => $product['category3']['ca_id'] ?? '',
				'model' => $product['it_model']
			]);
		}
	}
}

$ns->render();
$glam->back();