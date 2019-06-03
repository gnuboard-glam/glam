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

    // content stylesheet
    $styles = $theme_config['styles'] ?? null;
    if ($styles) {
        $head = &$glam->head;
        $styleContents = $styles['contents'] ?? false;
        $styleContentsDepth = $styles['contentsDepth'] ?? false;

        if ($styleContents ) {
            $head->styles->url(10,GNU_THEME_CSS . 'contents.css');
            if($styleContentsDepth === false) {
                $styles['contentsDepth'] = true;
            }
        }

        if ($styleContentsDepth === true) {
            $styleContentsDepth = 10;
        }

        if ($styleContentsDepth) {
            $slugs = explode('/', $url);

            // except locale root
            $styleLocale = $styles['locale'] ?? true;
            if (!$styleLocale) {
                $locales = $theme_config['locales'] ?? [];
                $locales = array_keys($locales);
                if (in_array($slugs[0], $locales)) {
                    array_shift($slugs);
                }
            }

            $depth = count($slugs);
            $current = 0;
            while ($current < $styleContentsDepth && $current < $depth) {
                $current++;
                $styleFile = implode('/', array_slice($slugs, 0, $current)) . '.css';
                $head->styles->url(10, GNU_THEME_CSS . $styleFile);
            }
        }
    }

    $cacheName = 'contents_' . $url;

    $content = $_SERVER['REQUEST_TIME'] - fileatime($contentFile) > 180 ?
        $glam->cache->get($cacheName) :
        null;
    require GNU_THEME . 'head.php';
    if ($content === null) {
        ob_start();
        require $contentFile;
        $content = ob_get_clean();
        $glam->cache->set($cacheName, $content, 86400);
    }
    echo $content;
    require GNU_THEME . 'tail.php';
} else {
    Header::notFound();
    $glam->redirect(GNU_URL);
}