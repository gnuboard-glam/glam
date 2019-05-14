<?php
/**
 * @global $glam Glam\GlamAdmin
 */

require '../../_common.php';

require './_global.php';

$records = [];
foreach ($defaults as $name => $gnuName) {
	$records[$gnuName] = $_POST[$name] ?? '';
}

$glam->db->updated($glam->_tableShopDefault, $records);

$records = [];
foreach ($services as $name) {
	$records[$name] = $_POST[$name] ?? '';
}

$glam->setOption('shopServices', $records);

$glam->back();