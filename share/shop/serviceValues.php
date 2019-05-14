<?php
/**
 * @global $glam Glam\GlamShop
 */

$services = $glam->getOption('shopServices');

$csNumber = $services['csNumber'] ?? null;
$csEmail = $services['csEmail'] ?? null;
$smsNumber = $services['smsNumber'] ?? null;
$kakaoYellowId = $services['kakaoYellowId'] ?? null;
$kakaoStory = $services['kakaoStory'] ?? null;
$facebook = $services['facebook'] ?? null;
$twitter = $services['twitter'] ?? null;
$instagram = $services['instagram'] ?? null;