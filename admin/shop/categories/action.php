<?php
/**
 * @global $glam \Glam\GlamShopAdmin
 */
require '../../_common.php';


use Dot\Http\Request;

Request::post();

$names = $_POST['name'] ?? null;
$slugs = $_POST['slug'] ?? null;

if ($names && $slugs) {
	foreach($names as $id => $name){
		$className = $slugs[$id];
		$glam->setShopCategory($id, [
			'ca_name' => $name,
			'slug' => $className
		]);
	}
}

$glam->back();