<?php
/**
 * @global $glam \Glam\GlamRentalAdmin
 */

use Dot\Images;

require '../../_common.php';
if (!\Dot\isLocalServer()) {
	die('Only Available in localhost');
}

require '../../head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$cdn = $glam->getRentalCdn();

	$root = $_SERVER['DOCUMENT_ROOT'] . $cdn['localServer'];

	if (!is_dir($root)) {
		die($root . ' is not a available directory');
	}


	$resizeDir = $cdn['resizeDir'];
	$resizeRoot = $root . $resizeDir;

	if (!is_dir($resultRoot)) {
		mkdir($resultRoot);
	} else {
		$resizeImages = glob($resizeRoot . '/*');
		foreach ($resizeImages as $resizeImage) {
			//unlink($resizeImage);
		}
	}

	$detects = [
		[
			'dir'    => 'productDir',
			'resize' => ['productJpg', 'productPng'],
			'sizes'  => 'productResize'
		],
		[
			'dir'    => 'giftDir',
			'resize' => ['giftJpg'],
			'sizes'  => 'giftResize'
		]
	];

	require './_global.php';

	foreach ($detects as $detect) {
		$dir = $detect['dir'];
		$dir = $cdn[$dir];
		$originalRoot = $root . $dir;

		$sizes = $cdn[$detect['sizes']];
		$sizes = explode(',', $sizes);
		foreach ($sizes as &$size) {
			$size = trim($size);
		}
		$images = getImages($originalRoot);

		$fileNames = $detect['resize'];
		foreach ($fileNames as &$fileName) {
			$fileName = basename($cdn[$fileName]);
		}
		$filePattern = '#^(' . implode('|', $fileNames) . ')$#';
		$filePattern = str_replace(
			['.', '{$no}', '{$index}'],
			['\.', '\d+', '[\w-_]+'],
			$filePattern
		);

		foreach ($images as $image) {
			$imageName = basename($image);

			if (!preg_match($filePattern, $imageName)) {
				continue;
			}

			foreach ($sizes as $size) {
				$resizeTo = $resizeRoot . $size . '/' . $dir;
				$resizeName = preg_replace('#^' . $originalRoot . '#', '', $image);
				$resizeName = $resizeTo . $resizeName;

				if (preg_match('#//#', $resizeName)) {
					die('resize name has an error: ' . $resizeName);
				}

				$resizeDir = dirname($resizeName);
				if (!is_dir($resizeDir)) {
					mkdir($resizeDir, 0777, true);
				}

				Images::resize($image, $size, $resizeName);
			}
		}
	}

	$glam->back();
} else {
	?>

	<?= formOpen() ?>
	<?php if (\Dot\isLocalServer()): ?>
		<?= formSubmitOnly() ?>
	<?php else:?>
		운영 서버에서는 사용할 수 없습니다.
	<?php endif ?>

		<?= formClose ?>
	<?php
}
require '../../tail.php';