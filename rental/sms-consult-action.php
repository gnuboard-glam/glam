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
$phone = $_POST['phone'] ?? die('phone number is required');

$product = $glam->getShopProduct($productId);
if (!$product) {
	die("Product #{$product['it_id']} is not exists");
}


$phone = \preg_replace('#[^\d]#', '', $phone);

$managerNumber = isLocalServer() ?
	$glam->getOption('shopServices', 'localSmsNumber') :
	$sms5['cf_phone'];

if ($managerNumber) {
	$texts = $glam->getRentalTexts();
	$contents = &$texts['contents'];

	$sms = &$glam->sms;

	$textValues = [
		'phone' => $phone
	];

	$content = $glam->setRentalText($contents['managerConsult'], $product, $textValues);
	$sms->write($managerNumber, $managerNumber, $content);

	$content = $glam->setRentalText($contents['userConsult'], $product, $textValues);
	$sms->write($managerNumber, $phone, $content);

	$sms->send();
}

$glam->redirect($product['detailUrl']);