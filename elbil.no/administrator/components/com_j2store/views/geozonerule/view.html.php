<?php
/*------------------------------------------------------------------------
 # com_j2store - J2Store
# ------------------------------------------------------------------------
# author    Sasi varna kumar - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://j2store.org
# Technical Support:  Forum - http://j2store.org/forum/index.html
-------------------------------------------------------------------------*/


/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.view');

class J2StoreViewGeozonerule extends J2StoreView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$view = $app->input->getWord('view', 'cpanel');
		$path = JPATH_ADMINISTRATOR.'/components/com_j2store/views/'.JString::strtolower($view).'/tmpl';
		$this->addTemplatePath($path);
		parent::display($tpl);
	}

}
