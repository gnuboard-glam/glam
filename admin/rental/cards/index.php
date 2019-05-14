<?php
namespace Glam;

require '../../_common.php';

$cards = [];

$mode = $_GET['mode'] ?? 'list';

$offsetIncludes = [
	'self' => '렌탈/할부료',
	'installment' => '무이자 할부',
	'apart' => '아파트 관리비',
	'tax' => '국세/지방세',
	'oversea' => '해외',
	'family' => '가족카드 합산'
];


require '../../head.php';

if($mode == 'list') {
	require './list.phtml';
}else if($mode == 'form'){
	require './form.phtml';
}

require '../../tail.php';