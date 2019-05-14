<?php

namespace Glam;

use Dot\File;

class NaverShopProducts
{
	protected $fields = [
		'mapid' => '',
		'pname' => '',
		'price' => '',
		'pgurl' => '',
		'igurl' => '',
		'cate1' => '',
		'cate2' => '',
		'cate3' => '',
		'cate4' => '',
		'caid1' => '',
		'caid2' => '',
		'caid3' => '',
		'caid4' => '',
		'model' => '',
		'brand' => '',
		'maker' => '',
		'origi' => '',
		'deliv' => 0,
		'event' => '',
		'pcard' => '',
		'point' => '',
		'revct' => ''
	];

	protected $products = [];

	/**
	 * filename as output
	 * @var string
	 */
	protected $fileName;

	function __construct(string $fileName)
	{
		$this->fileName = $fileName;
	}

	function add(array $product)
	{
		$this->products[] = $product;

		return $this;
	}

	function render(){
		$values = [];

		$fields = &$this->fields;
		foreach($this->products as $product){
			$values[] = '<<<begin>>>';

			foreach($fields as $key => $value){
				$value = [];
				$value[] = '<<<' . $key . '>>>';
				$value[] = $product[$key] ?? $value;
				$value = \implode('', $value);
				$values[] = $value;
			}

			$values[] = '<<<ftend>>>';
		}

		$values = \implode('\n', $values);
		$values = \iconv('utf-8', 'euc-kr', $values);

		File::write($this->fileName, $values);
	}
}

// $protocol = $_SERVER['REQUEST_SCHEME'] ?: 'http';
// $host = $protocol . '://' . $_SERVER['HTTP_POST'] . '/';

$ns = new NaverShopProducts(\GLAM . 'shop/ep_all.txt');


