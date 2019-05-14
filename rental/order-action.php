<?php
namespace Glam;

/**
 * @global $glam GlamRental
 */

use function Dot\isLocalServer;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	die('Bad Request');
}

require '../../common.php';

$productId = $_POST['product-id'] ?? die('product id is required');
$name = $_POST['name'] ?? die('name is required');
$phone = $_POST['phone'] ?? die('phone is required');


$product = $glam->getShopProduct($productId);
if (!$product) {
	die("Product #{$product['it_id']} is not exists");
}

$phone = \preg_replace('#[^\d]#', '', $phone);

$productModel = $product['it_model'];
$productName = $product['it_name'];

$term = $_POST['term'];
$address = $_POST['address'] ?? null;

$id = $product['it_id'] . time();

$glam->db->inserted($glam->_tableRentalOrders, [
	'id'            => $id,
	'product'       => $product['it_id'],
	'product_model' => $productModel,
	'product_name'  => $productName,
	'term'          => $term,
	'name'          => $name,
	'phone'         => $phone,
	'address'       => $address
]);

$services = $glam->getOption('shopServices');

$managerNumber = isLocalServer() ?
	$glam->getOption('shopServices', 'localSmsNumber') :
	$sms5['cf_phone'];

$sendManager = !!$managerNumber;
$sendUser = preg_match('#^0(10|20|11|16|17|18)#', $phone);

/**
 * @global $g5 array
 */
if ($managerNumber && ($sendManager || $sendUser)) {
	$texts = $glam->getRentalTexts();
	$contents = &$texts['contents'];

	$sms = &$glam->sms;

	$textValues = [
		'phone' => $phone,
		'name' => $name
	];

	if ($sendManager) {
		$contentManager = $glam->setRentalText($contents['managerOrder'], $product, $textValues);
		$sms->write($managerNumber, $managerNumber, $contentManager);
	}

	if ($sendUser) {
		$contentUser = $glam->setRentalText($contents['userOrder'], $product, $textValues);
		$sms->write($managerNumber, $phone, $contentUser);
	}

	$sms->send();
}

$glam->redirect($product['detailUrl']);