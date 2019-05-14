<?php

/**
 * @global $glam \Glam\GlamRentalAdmin
 */

require '../common.php';

$icodeId = $_POST['icode-id'] ?? '';
$icodePassword = $_POST['icode-password'] ?? '';
$icodeSendNumber = $_POST['icode-send-number'] ?? '';

$glam->db->updated($glam->_tableConfig, [
	'cf_icode_id' => $icodeId,
	'cf_icode_pw' => $icodePassword
]);

$glam->setTextSender($icodeSendNumber);

$managerConsult = $_POST['managerConsult'] ?? null;
$userConsult = $_POST['userConsult'] ?? null;

$managerOrder = $_POST['managerOrder'] ?? null;
$userOrder = $_POST['userOrder'] ?? null;

$userCheck = $_POST['userCheck'] ?? false;
$userComplete = $_POST['userComplete'] ?? null;

$useUserCheck = $_POST['use-user-check'] ?? false;
$useUserComplete = $_POST['use-user-complete'] ?? null;

$glam->setOption('rentalTexts', [
	'uses' => [
		'userCheck' => $useUserCheck,
		'userComplete' => $useUserComplete
	],
	'contents' => [
		'managerConsult' => $managerConsult,
		'userConsult' => $userConsult,
		'managerOrder' => $managerOrder,
		'userOrder' => $userOrder,
		'userCheck' => $userCheck,
		'userComplete' => $userComplete
	]
]);

$glam->back();