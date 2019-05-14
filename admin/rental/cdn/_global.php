<?php

function getImages($path)
{
	$pattern = rtrim($path, '/') . '/*';
	$files = glob($pattern);
	$images = [];
	foreach ($files as $file) {
		if (is_dir($file)) {
			$sub = getImages($file);
			foreach ($sub as $file) {
				$images[] = $file;
			}
		} elseif (preg_match('#\.(png|jpe?g)$#', $file)) {
			$images[] = $file;
		}
	}

	return $images;
}