<?php
/*------------------------------------------------------------------------
 # com_j2store - J2Store
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://j2store.org
-------------------------------------------------------------------------*/


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.application.component.view');

if (version_compare(JVERSION, '3.0', 'ge'))
{
	class J2StoreView extends JViewLegacy
	{

	}

}
else
{
	class J2StoreView extends JView
	{

	}
}