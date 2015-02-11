<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

class TableCoupon extends JTable
{

	/**
	* @param database A database connector object
	*/
	function __construct(&$db)
	{
		parent::__construct('#__j2store_coupons', 'coupon_id', $db );
	}


	function check()
	{
		if (empty($this->coupon_name))
		{
			$this->setError( JText::_( "Coupon name Required" ) );
			return false;
		}
		if (empty($this->coupon_code))
		{
			$this->setError( JText::_( "Coupon Code Required" ) );
			return false;
		}
		if (empty($this->value))
		{
			$this->setError( JText::_( "Coupon value Required" ) );
			return false;
		}
		if (empty($this->value_type))
		{
			$this->setError( JText::_( "Coupon type Required" ) );
			return false;
		}

		return true;
	}

}