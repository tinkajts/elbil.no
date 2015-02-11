<?php
/*------------------------------------------------------------------------
 # plg_j2store_payment_paypal - j2store v 2.0
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://j2store.org
# Technical Support:  Forum - http://j2store.org/forum/index.html
-------------------------------------------------------------------------*/


// no direct access
defined('_JEXEC') or die('Restricted access');

class plgJ2StorePayment_paypalInstallerScript {

	function preflight( $type, $parent ) {
		
		$xmlfile = JPATH_ADMINISTRATOR.'/components/com_j2store/manifest.xml';
		$xml = JFactory::getXML($xmlfile);
		$version=(string)$xml->version;
		
		//check for minimum requirement
		// abort if the current J2Store release is older
		if( version_compare( $version, '1.2', 'lt' ) ) {
			Jerror::raiseWarning(null, 'You are using an old version of J2Store. Please upgrade to the latest version');
			return false;
		}

	}
	
}