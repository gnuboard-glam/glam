<?php
/**
 * @global $glam Glam\GlamAdmin
 */

use Dot\Http\Request;

require '../../_common.php';

class _post
{
	static function get(string $name)
	{
		return $_POST[$name] ?? null;
	}

	static function required(string $name)
	{
		if (!isset($_POST[$name])) {
			throw new Error($name . ' is required');
		}

		return $_POST[$name];
	}
}

function postServer(string $name)
{
	$value = _post::required($name);
	$value = \rtrim($value, '/') . '/';

	return $value;
}

function postDir(string $name)
{
	$value = _post::required($name);
	$value = \trim($value, '/') . '/';

	return $value;
}

function postFile(string $name)
{
	// todo verify pattern;
	$value = _post::required($name);
	return $value;
}


function postSizes(string $name)
{
	// todo verify pattern
	$value = _post::required($name);

	return $value;
}

function postInt(string $name)
{
	$value = _post::required($name);

	return $value;
}

Request::post();

$values = [
	'server'        => postServer('server'),
	'localServer'   => postServer('localServer'),
	'resizeDir'     => postDir('resizeDir'),
	'productDir'    => postDir('productDir'),
	'productSize'   => postInt('productSize'),
	'productResize' => postSizes('productResize'),
	'productJpg'    => postFile('productJpg'),
	'productPng'    => postFile('productPng'),
	'productDetail' => postFile('productDetail'),

	'brandDir'   => postDir('brandDir'),
	'giftDir' => postDir('giftDir'),
	'giftSize'   => postInt('giftSize'),
	'giftResize' => postSizes('giftResize'),
	'giftJpg'    => postFile('giftJpg'),

	'cardPng' => postFile('cardPng'),
];

$glam->setOption('cdn', $values);
$glam->back();