<?php
/**
 * @global $glam Glam\GlamRentalAdmin
 */

use Dot\Strings;

require '../../_common.php';

require '../../head.php';

$products = $glam->getShopProducts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$recountedProducts = [];
	$formats = ['gif', 'jpg'];
	foreach ($products as $product) {
		$model = $product['it_model'];
		$model = strtoupper($model);
		$patterns = $glam->getRentalProductCdnPatterns($product);

		$limits = [
			'jpg'    => 10,
			'png'    => 10,
			'detail' => 20
		];

		$counts = [
			'jpg' => 0,
			'png' => 0,
			'detail' => ''
		];

		$dirs = [];
		$verifies = [];

		foreach ($limits as $type => $limit) {
			$pattern = $_SERVER['DOCUMENT_ROOT'] . '/' . $patterns[$type];
			$dir = str_replace('/', DIRECTORY_SEPARATOR, dirname($pattern));
			$verify = ['dir' => $dir, 'exists' => true];
			if (is_dir($dir)) {
				for ($no = 1, $limit += 1; $no < $limit; $no++) {
					$fileName = str_replace('{$no}', $no, $pattern);
					// multi format
					if (Strings::ends($fileName, '.*')) {
						$_fileName = substr($fileName, 0, -2);
						$find = null;
						foreach ($formats as $formatIndex=>$format) {
							if (file_exists($_fileName . '.' . $format)) {
								$find = $formatIndex + 1;
								break;
							}
						}
						if ($find) {
							$counts[$type] .= $find;
						} else {
							break;
						}
					} else {
						if (file_exists($fileName)) {
							$counts[$type]++;
						}
					}
				}
			} else {
				$verify['exists'] = false;
				$exists[$type] = false;
			}
			$verifies[$type] = $verify;
		}

		$product['cdn-count'] = $counts;
		$product['cdn-verify'] = $verifies;
		$recountedProducts[] = $product;
	}

	$products = $recountedProducts;

	require './verify-count.phtml';
} else {
	require './count.phtml';
}

require '../../tail.php';