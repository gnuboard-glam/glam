<?php

namespace Glam;

use Dot\Dev;
use function Dot\isLocalServer;

$autoloadFile = __DIR__ . '/vendor/autoload.php';

if (stream_resolve_include_path($autoloadFile) === false) {
	die('Composer not installed');
}

require $autoloadFile;

if (isLocalServer()) {
	Dev::mode([
		'errorReport' => E_ALL & ~E_NOTICE,
		'excludes'     => ['ajax.token.php', 'theme_update.php']
	]);
}