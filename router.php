<?php

namespace Glam;

use Dot\Http\Header;

/**
 * @global $glam GlamBoard
 */

require './common.php';

$url = $_GET['url'] ?? '';

$contentFile = GNU_CONTENTS . $url . '.phtml';


if (stream_resolve_include_path($contentFile) !== false) {
	$glam->isContent = true;

	$cacheName = 'contents_' . $url;
	$content = $_SERVER['REQUEST_TIME'] - fileatime($contentFile) > 180 ?
		$glam->cache->get($cacheName) :
		null;

	if ($content === null) {
		ob_start();
		require $contentFile;
		$content = ob_get_clean();
		$glam->cache->set($cacheName, $content, 86400);

		require GNU_THEME . 'head.php';
		echo $content;
		require GNU_THEME . 'tail.php';
	}
} else {
	Header::notFound();
	$glam->redirect(GNU_URL);
}