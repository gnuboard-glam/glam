<?php

namespace Glam;

use Error;

class GlamShop extends GlamBoard
{
	use GlamShopTrait;

	/**
	 * @var int the shop product id
	 */
	public $_shopProduct;

	function glam_ready()
	{
		parent::glam_ready();
		$this->glam_shop();
	}

	function price($price)
	{
		return '<span class="price"><span class="price-value">' . \number_format($price) . '</span><span class="price-unit">원</span></span>';
	}

	function __get($name)
	{
		switch ($name) {
			case 'sms':
				$this->sms = new GlamSms;

				return $this->sms;
		}

		throw new Error("{$name} is not property");
	}

	function getBodyClass()
	{
		if (!$this->isShop) {

			return parent::getBodyClass();
		}

		$classList = ['shop'];

		$product = &$this->_shopProduct;

		if ($product) {
			$classList[] = 'shop_detail';
			$classList[] = 'shop_' . $product['it_id'];
		}else{
			$classList[] = 'shop_list';
		}

		return $classList;
	}
}