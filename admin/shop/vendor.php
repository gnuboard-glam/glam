<?php
/**
 * @global $glam Glam\GlamAdmin
 */

require '../_common.php';

require './vendor-global.php';

foreach ($defaults as $name => $gnuName) {
	${$name} = $default[$gnuName];
}

$_services = $glam->getOption('shopServices');

if ($_services) {
	foreach ($services as &$name) {
		${$name} = &$_services[$name];
	}
}

require '../head.php';
require './vendor.phtml';
require '../tail.php';