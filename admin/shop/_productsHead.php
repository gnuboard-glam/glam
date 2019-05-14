<?php

use Dot\Html\Pagebar;

$page = $_GET['page'] ?? 1;
$category = $_GET['category'] ?? null;
$target = $_GET['target'] ?? 'it_name';
$keyword = $_GET['keyword'] ?? null;
$type = $_GET['type'] ?? null;

$limit = 24;
$products = $glam->getShopProducts([
    'limit' => $limit,
    'category' => $category,
    'page' => $page,
    'target' => $target,
    'keyword' => $keyword,
	'type' => $type
]);

$pagebar = new Pagebar($products->total, $limit, $page);
$pagebar->href('?category=' . $category . '&target=' . $target . '&keyword=' . $keyword);

require __DIR__ . '/../head.php';
require __DIR__ . '/_productsHead.phtml';