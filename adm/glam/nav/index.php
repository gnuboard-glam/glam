<?php

namespace Glam;

/**
 * @global $glam GlamBoard
 */

require '../../_common.php';

$navs = $glam->getNavs();

if ($navs->count() < 1) {
    $navs = [[
        'id' => 0,
        'code' => '',
        'depth' => 0,
        'name' => '',
        'class' => '',
        'icon' => '',
        'link' => '',
        'target' => 'self',
        'order' => '',
        'use' => true,
        'use2' => true
    ]];
}

require '../../head.php';
require './nav.phtml';
require '../../tail.php';