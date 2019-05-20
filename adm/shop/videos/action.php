<?php

/**
 * @global $glam \Glam\GlamRentalAdmin
 */
require '../../common.php';

$videos = $_POST['video'];

foreach ($videos as $id => $video) {
	//$video = $videos[$id];
	$glam->setShopProduct($id, [
		'video' => $video
	]);
}

$glam->back();