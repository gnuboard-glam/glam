<?php

namespace Glam;

class GlamShopAdmin extends GlamAdmin
{
	use GlamShopTrait;

	function glam_ready()
	{
		parent::glam_ready();
		$this->glam_shop();
	}

	function extendNav()
	{
		parent::extendNav();

		global $menu;

		$group = 'shop+';
		$this->setNavGroup($group, 450, '쇼핑몰 확장')
			// ->replaceNav('shop', 650, $group, 30, '간편 후기', 'shop/comment');
			->addNav($group, 10, '사업자 정보', 'shop/vendor')
			->addNav($group, 20, '분류 주소 설정', 'shop/categories')
			->addNav($group, 40, '유형 설정', 'shop/types')
			->addNav($group, 50, '영상 설정', 'shop/videos')
			->addNav($group, 60, '지식쇼핑 설정', 'shop/naver');

		return $this;
	}


	function setShopCategory($id, array $values = [])
	{
		if (\is_array($id)) {
			$values = $id;
			$id = $values['ca_id'] ?? null;
			if (!$id) {
				throw new Error('category id is required.');
			} else {
				unset($values['ca_id']);
			}
		}

		$values = $this->setExtendField($values, $this->_shopExtendFields['category']);

		return $this->db->updated($this->_tableShopCategory, $values, $id, 'ca_id');
	}

	function getShopCategoryAsOptions() : array
	{
		$categories = $this->getShopCategories();
		$options = [];
		$options[''] = '전체분류';
		foreach ($categories['byId'] as $category) {
			$options[$category['ca_id']] = $category['ca_name'];
		}

		return $options;
	}

	function setShopProduct($id, array $values = [])
	{
		if (\is_array($id)) {
			$values = $id;
			$id = $values['id_id'] ?? null;
			if (!$id) {
				throw new \Error('product id is required.');
			} else {
				unset($values['it_id']);
			}
		}

		$values = $this->setExtendField($values, $this->_shopListFields);

		return $this->db->updated($this->_tableShopProducts, $values, $id, 'it_id');
	}

	/**
	 * set text send number
	 * @param string $number
	 * @return \Dot\DB\DBStateInterface
	 */
	function setTextSender(string $number){
		return $this->db->updated($this->_tableSmsConfig, [
			'cf_phone' => $number
		]);
	}
}