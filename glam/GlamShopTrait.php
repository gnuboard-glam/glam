<?php

namespace Glam;

use Dot\Strings;

trait GlamShopTrait
{
	protected $_shopImageServer;

	/**
	 * @var string
	 */
	public $_tableShopDefault;

	/**
	 * @var string
	 */
	public $_tableShopCategory = G5_SHOP_TABLE_PREFIX . 'category';

	/**
	 * @var string
	 */
	public $_tableShopProducts = G5_SHOP_TABLE_PREFIX . 'item';

	/**
	 * @var string
	 */
	public $_tableSmsConfig = 'sms5_config';

	protected $_shopExtendFields;
	protected $_shopListFields;
	protected $_shopDetailFields;
	protected $_shopCategoryFields;
	public $_shopTypes;
	public $_shopTypeLabels;

	public $_shopCategories;

	function glam_shop()
	{
		$g5 = &$this->g5;
		$this->_tableShopDefault = $g5['g5_shop_default_table'];

		$this->_shopExtendFields = [
			'category' => [
				'ca_10' => 'slug'
			],
			'list'     => [
				'it_10' => 'features',
				'it_9'  => 'video',
				'it_8'  => 'cdn'
			]
		];

		$this->_shopCategoryFields = [
				'ca_id',
				'ca_order',
				'ca_name'
			] + $this->_shopExtendFields['category'];

		$this->_shopListFields = [
				'it_id',
				'it_use',
				'it_name',
				'it_model',
				'it_price',
				'it_basic',
				'it_img1',
				'it_brand',
				'it_order',
				'ca_id',
				'ca_id2',
				'ca_id3',
				'it_type1',
				'it_type2',
				'it_type3',
				'it_type4',
				'it_type5',
				'it_tel_inq',
				'it_hit',
				'it_use_cnt',
				'it_use_avg'
			] + $this->_shopExtendFields['list'];

		$this->_shopDetailFields = array_merge([
			'ca_id',
			'ca_id2',
			'ca_id3',
			'it_name',
			'it_brand',
			'it_model',
			'it_price',
			'it_tel_inq',
			'it_hit',

			'it_explan',
			'it_mobile_explan',
			'it_basic'
		], $this->_shopListFields);

		$this->_shopTypes = [
			1 => 'hit',
			2 => 'recommend',
			3 => 'new',
			4 => 'best',
			5 => 'sale'
		];

		$this->_shopTypeLabels = [
			1 => '관심',
			2 => '추천',
			3 => '신규',
			4 => '인기',
			5 => '할인'
		];
	}

	function getShopCategories(array $options = [])
	{
		$_shopCategories = &$this->_shopCategories;
		if (!$_shopCategories) {
			$records = $this->db->selected($this->_tableShopCategory, $this->_shopCategoryFields)
				->result()
				->all();

			$categories = [
				'byId'   => [],
				'bySlug' => []
			];

			foreach ($records as $record) {
				$categories['byId'][$record['ca_id']] = $record;
				$categories['bySlug'][$record['slug']] = $record;
			}
			$_shopCategories = $categories;
		}

		return $_shopCategories;
	}

	function getShopCategory($id)
	{
		$categories = $this->getShopCategories();

		if (\is_numeric($id)) {
			return $categories['byId'][$id] ?? null;
		}

		return $categories['bySlug'][$id] ?? null;
	}

	function findShopCategory($id_or_slug)
	{
		if (is_array($id_or_slug)) {
			return $id_or_slug;
		}
		$categories = $this->getShopCategories();

		if (\is_numeric($id_or_slug)) {
			if (isset($categories[$id_or_slug])) {
				return $categories[$id_or_slug];
			}

			return null;
		}

		//todo slug
		return false;
	}

	function setImageServer(array &$cdn)
	{
		$cdn['productList'] = $cdn['productDir'] . $cdn['productList'];
		$cdn['productColor'] = $cdn['productDir'] . $cdn['productColor'];
		$cdn['productDetail'] = $cdn['productDir'] . $cdn['productDetail'];
		$cdn['brandDir'] = $cdn['brandServer'] . $cdn['brandDir'];
	}

	function getImageServer()
	{
		$cdn = &$this->_shopImageServer;
		if (!$cdn) {
			$cdn = $this->getOption('cdn');
		}

		return $cdn;
	}

	function getShopProducts(array $options = [])
	{
		$options += [
			'field'        => null,
			'group'        => null,
			'order'        => 'it_order desc, it_id',
			'random'       => null,
			'limit'        => null,
			'page'         => null,
			'cacheName'    => null,
			'cacheExpired' => 3600 * 3,

			'category' => null,
			'type'     => null,
			'target'   => 'it_name',
			'keyword'  => null,
			'brand'    => null,
			'size'     => null,
			'where'    => null
		];


		$field = $options['field'];
		$cacheName = $options['cacheName'];
		$target = $options['target'];
		$keyword = $options['keyword'];
		$limit = $options['limit'];
		$page = $options['page'];
		$category = $options['category'];
		$type = $options['type'];
		$where = $options['where'];
		$group = $options['group'];
		$order = $options['order'];
		$random = $options['random'];

		/*
		if($cacheName === true){
			$cacheName = 'shop';
			if($keyword){
				if($limit){
					$cacheName .= '_' . $limit;
				}
			}else{
				if($field){
					throw new \InvalidArgumentException('Cannot use the auto cache name with customized fields');
				}
				$cacheName = '_list';

				if($category){
					$cacheName .= '_' . $category;
				}
				if($limit) {
					$cacheName .= '_' . $limit;
					if($page){
						$cacheName .= '_' . $page;
					}
				}
			}
		}
		*/

		if (!$field) {
			$field = $this->_shopListFields;
		}

		if (\is_array($field) && !\in_array('it_id', $field)) {
			$field[] = 'it_id';
		} elseif (\is_string($field) && $field !== '*' && \str_pos($field, 'it_id') === false) {
			$field .= ', it_id';
		}

		$sql = $this->db->select($this->_tableShopProducts, $field);

		if ($where) {
			$sql->where($where);
		}
		if ($category) {
			if (\is_array($category)) {
				if (!isset($category['ca_id'])) {
					throw new \InvalidArgumentException('Cannot found the category[ca_id] in options');
				}
			} else {
				$category = $this->findShopCategory($category);
			}

			if ($category) {
				$categoryId = $category['ca_id'];
				$sql->where("(
					ca_id like '{$categoryId}%' OR
					ca_id2 like '{$categoryId}%' OR
					ca_id3 like '{$categoryId}%'
				)");
			}
		}

		if (!empty($keyword)) {
			$keywords = \preg_split('#\s+#', $keyword);
			$keywordWheres = [];
			foreach ($keywords as $keyword) {
				$keywordWheres[] = "{$target} like '%{$keyword}%'";
			}
			$sql->where(\implode(' OR ', $keywordWheres));
		}

		if ($type) {
			if (\is_numeric($type)) {
				$sql->where('it_type' . $type, 1);
			} else {
				throw new \InvalidArgumentException('Product Type should be a integer');
			}
		}

		/*
		if ($brand) {
			if (\is_array($brand)) {
				if (!isset($brand['id'])) {
					throw new \InvalidArgumentException('cannot found the brand i in option');
				}
			} else {
				$brand = $this->findShopBrand($brand);
			}

			if ($brand) {
				$brand = $brand['id'];
				$sql->where('it_brand', $brand);
			}
		}
		*/

		if ($group) {
			$sql->groupBy($group);
		}

		$total = null;
		if ($limit) {
			if ($page) {
				$total = $sql->count();
				$sql->offset(($page - 1) * $limit);
			}
			$sql->limit($limit);
		}

		if ($random) {
			$sql->order('rand()');
		} elseif ($order) {
			$sql->order($order);
		}

		$result = $sql->result();

		// todo: temp
		$result->total = $total;
		$result->map([$this, '_setShopProduct'], [$options]);

		return $result;
	}

	function getShopHitProducts(array $options = [])
	{
		$options['type'] = 1;

		return $this->getShopProducts($options);
	}

	function getShopRecommendProducts(array $options = [])
	{
		$options['type'] = 2;

		return $this->getShopProducts($options);
	}

	function getShopNewProducts(array $options = [])
	{
		$options['type'] = 3;

		return $this->getShopProducts($options);
	}

	function getShopBestProducts(array $options = [])
	{
		$options['type'] = 4;

		return $this->getShopProducts($options);
	}

	function getShopSaleProducts(array $options = [])
	{
		$options['type'] = 5;

		return $this->getShopProducts($options);
	}

	function _setShopProduct($record)
	{
		$modelSlug = Strings::toSlug($record['it_model']);

		$record['detailUrl'] = GNU_URL . 'shop/' . $record['it_id'];
		$record['detailModelUrl'] = GNU_URL . 'shop/' . $modelSlug;

		if (isset($record['ca_id'])) {
			$record['category1'] = $this->getShopCategory($record['ca_id']);
		}
		if (isset($record['ca_id2'])) {
			$record['category2'] = $this->getShopCategory($record['ca_id2']);
		}
		if (isset($record['ca_id3'])) {
			$record['category3'] = $this->getShopCategory($record['ca_id3']);
		}

		return $record;
	}

	function getShopProduct($id)
	{
		$fields = $this->_shopDetailFields;
		$record = $this->db->selected($this->_tableShopProducts, $fields, $id, 'it_id')
			->fetch();

		return $this->_setShopProduct($record);
	}
}