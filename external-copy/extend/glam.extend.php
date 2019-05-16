<?php
namespace Glam;

if (
	defined('_GNUBOARD_') ||
	strpos($_SERVER['REQUEST_URI'], '/install/') === false
) {
	define('GLAM', dirname(__DIR__) . '/glam/');

	require GLAM . 'autoload.php';

	$glam = Glam::load(__DIR__);
}