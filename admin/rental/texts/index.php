<?php
/**
 * @global $glam \Glam\GlamRentalAdmin
 */
require '../../_common.php';

$icodeId = $config['cf_icode_id'];
$icodePassword = $config['cf_icode_pw'];
$icodeSendNumber = $sms5['cf_phone'];

$texts = $glam->getRentalTexts();
$uses = &$texts['uses'];
$contents = &$texts['contents'];

$useUserCheck = $uses['userCheck'] ?? null;
$useUserComplete = $uses['userComplete'] ?? null;
$contentManagerOrder = $contents['managerOrder'];
$contentUserOrder = $contents['userOrder'];
$contentUserCheck = $contents['userCheck'];
$contentUserComplete = $contents['userComplete'];

$contentManagerConsult = $contents['managerConsult'];
$contentUserConsult = $contents['userConsult'];

require '../../head.php';
require './texts.phtml';
require '../../tail.php';