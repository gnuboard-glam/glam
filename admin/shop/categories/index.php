<?php
/** @global $glam \Glam\GlamShopAdmin */
require '../../_common.php';

$categories = $glam->getShopCategories();

require '../../head.php';
require './category.phtml';
require '../../tail.php';