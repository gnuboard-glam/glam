<?php

namespace Glam;

use Dot\Html\Pagebar;

/**
 * @global $glam GlamShop;
 */

require '../common.php';

$url = $_GET['url'] ?? '';
$url = \rtrim($url, '/');
$slugs = \explode('/', $url);


$options = [];

$categories = $glam->getShopCategories();

$feedbacks = [
	'order-dialog'       => GNU_THEME . 'shop/order-dialog.phtml',
	'sms-consult-dialog' => GNU_THEME . 'shop/sms-consult-dialog.phtml'
];

$fallback = null;

if ($slugs) {
	$fallback = end($slugs);
	if (\in_array($fallback, \array_keys($feedbacks))) {
		\array_pop($slugs);
		$fallback = &$feedbacks[$fallback];
		$glam->setBodyClass('dot-dialog-wall');
	} else {
		$fallback = null;
	}

	if ($slugs) {
		$types = &$glam->_shopTypes;
		$type = $slugs[0];
		if (in_array($type, $types)) {
			array_shift($slugs);
			$type = array_search($type, $types);
			$options['type'] = $type;
		}

		$id = \end($slugs);
		if (\is_numeric($id)) {
			$product = $glam->getShopProduct($id);
		}

		$category = $glam->getShopCategory($slugs[0]);
		if ($category) {
			\array_shift($slugs);
			$options['category'] = $category;
		}
	}
}
if ($product) {
	require GNU_THEME . 'shop/detail.phtml';
	if ($fallback) {
		require $fallback;
	}
} else {
	$options['limit'] = 36;
	$options['page'] = $_GET['page'] ?? 1;

	$products = $glam->getShopProducts($options);
	if ($products->total) {
		$pagebar = new Pagebar($products->total, $options['limit'], $options['page']);
		$pagebar = $pagebar->toUl();
		require GNU_THEME . 'shop/index.phtml';
	} else {
		require GNU_THEME . 'shop/empty.phtml';
	}
}
