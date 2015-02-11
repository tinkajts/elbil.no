<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

class TableOrderCoupons extends JTable
{

	/**
	* @param database A database connector object
	*/
	function __construct(&$db)
	{
		parent::__construct('#__j2store_order_coupons', 'order_coupon_id', $db );
	}

	function save($data,  $orderingFilter = '', $ignore = '') {

		if(!parent::save($data,  $orderingFilter = '', $ignore = '')) {
			$this->setError($this->getError());
			return false;
		}
		return true;
	}

}