<?php

defined('_JEXEC') or die;

class RegwebViewLostpassword extends JViewLegacy {
	
	public function display($tpl = null) {
		$this->form = $this->get('Form');
		
		parent::display($tpl);
	}
	
}