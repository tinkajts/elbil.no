<?php
defined('_JEXEC') or die;

jimport('joomla.application.module.helper');

class RegwebViewSelectlogin extends JViewLegacy {
	
	public function display($tpl = null) {
		$this->user		= JFactory::getUser();
		
		$this->return = '';
		if ($return = JRequest::getVar('return', '', 'method', 'base64')) {
			$return = base64_decode($return);
			if (JURI::isInternal($return)) {
				$this->return = base64_encode($return);
			}
		}
        if ($this->return === '') {
            $authParams = new JRegistry(JPluginHelper::getPlugin('authentication', 'regweb')->params);
            $this->return = $userGroup = $authParams->get('login_redirect');
        }
		parent::display($tpl);
	}
}