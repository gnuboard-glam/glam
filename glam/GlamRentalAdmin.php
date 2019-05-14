<?php

namespace Glam;

class GlamRentalAdmin extends GlamShopAdmin
{
	use GlamRentalTrait;

	public $_rentalOrderStates = [
		1 => '주문 요청',
		2 => '주문 확인',
		3 => '주문 완료'
	];

	function glam_ready()
	{
		parent::glam_ready();
		$this->glam_rental();
	}

	function extendNav()
	{
		parent::extendNav();

		$group = 'rental';
		$this->setNavGroup($group, '460', '렌탈')
			->replaceNav('shop+', 10, $group)
			->addNav($group, 20, '주문 확인', '/rental/orders')
			->addNav($group, 30, '가격 설정', '/rental/prices')
			//->addNav($group, 40, '유형 설정(작업 대기)', '#')
			->addNav($group, 50, '제휴 카드(대기)', '/rental/cards')
			->addNav($group, 60, '사은품(대기)', '#')
			->addNav($group, 60, 'CDN 서버', '/rental/cdn')
			->addNav($group, 61, 'CDN 이미지 생성', '/rental/cdn/resize.php')
			->addNav($group, 62, 'CDN 이미지 검증', '/rental/cdn/verify.php')
			->addNav($group, 70, '문자 설정', '/rental/texts')
			;
	}

	function getRentalOrders(array $options = [])
	{

		$sql = $this->db->select($this->_tableRentalOrders);

		$sql->order('inserted', 'desc');

		$orders = $sql->result();
		$orders->map([$this, '_setRentalOrder']);

		return $orders;
	}

	function _setRentalOrder(array $order){
		$order['stateText'] = $this->_rentalOrderStates[$order['state']];
		return $order;
	}
}