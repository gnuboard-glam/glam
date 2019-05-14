<?php

namespace Glam;

use function Dot\isLocalServer;

trait GlamRentalTrait
{

	public $_tableRentalPrices = \G5_TABLE_PREFIX . 'glam_rental_prices';
	public $_tableRentalGifts = \G5_TABLE_PREFIX . 'glam_rental_gifts';
	public $_tableRentalOrders = G5_TABLE_PREFIX . 'glam_rental_orders';

	/**
	 * @var array
	 */
	protected $_rentalCdn;
	/**
	 * @var array
	 */
	protected $_rentalProductCdnPatterns;

	/**
	 * @var array
	 */
	protected $_rentalExtendFields;

	function glam_rental()
	{

		$this->_rentalExtendFields = [
			'list' => [
				'it_10_subj' => 'rental_prices',
				'it_9_subj'  => 'cdn_count'
			]
		];

		$this->_shopListFields += $this->_rentalExtendFields['list'];
		$this->_shopDetailFields += $this->_rentalExtendFields['list'];

		//$this->_shopTypeLabels[5] = '특가(할인)';
	}

	function getRentalItems(array $options = [])
	{
		$options += [
			'limit' => 24,
			'page'  => 1
		];

		$limit = $options['limit'];
		$page = $options['page'];

		//$this->db->nativeQuery("")
	}

	function getRentalCdn()
	{
		$_rentalCdn = &$this->_rentalCdn;
		if (!$_rentalCdn) {
			$_rentalCdn = $this->getOption('cdn', [
				'server'              => $_SERVER['HTTP_HOST'],
				'localServer'         => 'localhost',
				'resizeDir'           => 'resize',
				'productServer'       => '1',
				'productResizeServer' => '1',
				'productDir'          => 'products',
				'productSize'         => '640',
				'productResize'       => '80, 160, 320',
				'productJpg'          => '/{$category}/${model}/{$no}.jpg',
				'productPng'          => '/{$category}/${model}/{$no}.png',
				'productDetail'       => '/{$category}/${model}/detail-{$no}.png',
				'brandServer'         => '1',
				'brandDir'            => 'brands',
				'giftServer'          => '1',
				'giftResizeServer'    => '1',
				'giftDir'             => 'gifts/',
				'giftSize'            => '320',
				'giftResize'          => '80, 160',
				'giftJpg'             => '{$no}.jpg',
				'cardServer'          => '1',
				'cardPng'             => '${no}.png'
			]);
		}

		return $_rentalCdn;
	}

	function getRentalProductCdnPatterns(array $product = null)
	{
		$_rentalProductCdnPatterns = &$this->_rentalProductCdnPatterns;
		if (!$_rentalProductCdnPatterns) {
			$cdn = $this->getRentalCdn();
			$server = isLocalServer() ?
				$cdn['localServer'] :
				$cdn['server'];
			$productRoot = $server . $cdn['productDir'];
			$_rentalProductCdnPatterns = [
				'jpg'    => $productRoot . $cdn['productJpg'],
				'png'    => $productRoot . $cdn['productPng'],
				'detail' => $productRoot . $cdn['productDetail']
			];
		}
		if ($product) {
			$patterns = [];
			foreach ($_rentalProductCdnPatterns as $type => $pattern) {
				$pattern = str_replace('{$category}', $product['category1']['slug'], $pattern);
				$pattern = str_replace('{$model}', \trim($product['it_model']), $pattern);
				// todo: brand
				$patterns[$type] = $pattern;
			}

			return $patterns;
		}

		return $_rentalProductCdnPatterns;
	}

	function _setShopProduct($record)
	{
		$record = parent::_setShopProduct($record);
		if (isset($record['cdn_count'])) {

			$counts = \unserialize($record['cdn_count']);
			$record['cdn_count'] = $counts;
			$images = [
				'jpg'    => [],
				'png'    => [],
				'detail' => []
			];
			if ($counts && array_sum($counts)) {
				$cdn = $this->getRentalProductCdnPatterns($record);
				foreach ($images as $type => &$image) {
					$count = $counts[$type];
					$pattern = $cdn[$type];
					if ($type === 'detail') {
						$formats = ['dummy', 'gif', 'jpg'];
						for ($no = 1, $end = strlen($count) + 1; $no < $end; $no++) {
							$imageUrl = str_replace('{$no}', $no, $pattern);
							$image[] = substr($imageUrl, 0, -1) . $formats[$count[$no-1]];
						}
					} else {
						for ($no = 1, $end = $counts[$type] + 1; $no < $end; $no++) {
							$imageUrl = str_replace('{$no}', $no, $pattern);
							$image[] = $imageUrl;
						}
					}
				}
			}
			$record['cdn_images'] = $images;
		}

		if (isset($record['rental_prices'])) {
			$prices = \unserialize($record['rental_prices']);
			$record['prices'] = $prices;
			/*
			$record['purchase'] = $prices['purchase'];
			$record['regist'] = $prices['regist'];
			$record['install'] = $prices['install'];
			$record['checkCycle'] = $prices['checkCycle'];
			$record['options'] = $prices['options'];
			$record['minPrice'] = $prices['min'];
			*/
		}

		return $record;
	}

	function getRentalTexts()
	{
		return $this->getOption('rentalTexts', [
			'uses'     => [
			],
			'contents' => [
				'managerConsult' => '[$phone] $model 문자 상담',
				'managerOrder'   => '[$phone] $name님 $model 주문',
				'userConsult'    => '[$title] 문자상담을 요청 했습니다.',
				'userOrder'      => '[$title] $model 주문 했습니다.',
				'userCheck'      => '[$title] $model 주문 확인 중 입니다.',
				'userComplete'   => '[$title] $model 주문 완료 되었습니다.'
			]
		]);
	}


	function setRentalText(string $content, array $product, array $values)
	{
		global $default;

		return \str_replace(
			[
				'$title',
				'$name',
				'$phone',
				'$model'
			],
			[
				$default['cf_title'] ?: '렌탈',
				$values['name'] ?? '?',
				$values['phone'] ?? '?',
				$product['it_model'] ?? $product['it_name']
			],
			$content
		);
	}
}