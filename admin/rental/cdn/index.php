<?php
/**
 * @global $glam Glam\GlamRentalAdmin
 */
require '../../_common.php';

$cdn = $glam->getRentalCdn();

$server = $cdn['server'] ?? '';
$localServer = $cdn['localServer'];

$resizeDir = $cdn['resizeDir'] ?? '';
$productServer = $cdn['productServer'] ?? '';

$productResizeServer = $cdn['productResizeServer'] ?? '';
$productDir = $cdn['productDir'] ?? '';
$productSize = $cdn['productSize'] ?? '';
$productResize = $cdn['productResize'] ?? '';
$productJpg = $cdn['productJpg'] ?? '';
$productPng = $cdn['productPng'] ?? '';
$productDetail = $cdn['productDetail'] ?? '';
$brandServer = $cdn['brandServer'] ?? '';
$brandDir = $cdn['brandDir'] ?? '';



$giftServer = $cdn['giftServer'] ?? '';
$giftResizeServer = $cdn['giftResizeServer'] ?? '';
$giftDir = $cdn['giftDir'] ?? '';
$giftSize = $cdn['giftSize'] ?? '';
$giftResize = $cdn['giftResize'] ?? '';
$giftJpg = $cdn['giftJpg'] ?? '';
$cardServer = $cdn['cardServer'] ?? '';
$cardPng = $cdn['cardPng'] ?? '';

require '../../head.php';
require './cdn.phtml';
require '../../tail.php';