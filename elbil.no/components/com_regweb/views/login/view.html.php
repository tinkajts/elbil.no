<?php
defined('_JEXEC') or die;

jimport('joomla.application.module.helper');

class RegwebViewLogin extends JViewLegacy {
	
	public function display($tpl = null) {
		$module = JModuleHelper::getModule('mod_regweb_userbox');
		$this->loginBox = JModuleHelper::renderModule($module);
		parent::display($tpl);
	}
	
}