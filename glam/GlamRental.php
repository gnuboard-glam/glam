<?php

namespace Glam;

class GlamRental extends GlamShop
{
	use GlamRentalTrait;

	public $_rentalOrderAction;
	PUBLIC $_rentalSmsConsultAction;

	function glam_ready()
	{
		parent::glam_ready();
		$this->glam_rental();
		$this->_rentalOrderAction = GLAM_URL . 'rental/order-action.php';
		$this->_rentalSmsConsultAction = GLAM_URL . 'rental/sms-consult-action.php';
	}

	function setRentalOrder()
	{

	}

	function monthlyPrice($price)
	{
		return '<span class="price"><span class="price-value">' . \number_format($price) . '</span><span class="price-unit">원/월</span></span>';
	}
}