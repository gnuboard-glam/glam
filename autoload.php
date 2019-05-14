<?php
namespace Glam;

use Dot\Dev;
use function Dot\isLocalServer;

require __DIR__ . '/vendor/autoload.php';

if(isLocalServer()){
	Dev::mode([
		'errorReport' => E_ALL & ~E_NOTICE,
		'exclude' => 'ajax.token.php'
	]);
}