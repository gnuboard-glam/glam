<?php
namespace Glam;

/**
 * @global $glam GlamBoard
 */

require '../../_common.php';

$navs = $glam->getNavs();


require '../../head.php';
require './nav.phtml';
require '../../tail.php';