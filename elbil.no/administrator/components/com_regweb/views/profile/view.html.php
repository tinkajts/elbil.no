<?php

defined('_JEXEC') or die;

class RegwebViewProfile extends JViewLegacy {
	
	public function display($tpl = null) {
		$this->fieldsConfig = $this->get('FieldsConfig');
		$this->data = $this->get('DisplayData');
		$this->form = $this->get('Form');
		$this->fields = array();
		foreach ($this->form->getFieldset() as $field) {
			$this->fields[$field->fieldname] = $field;
		}
		// Get infotext to show above form
		$params = new JRegistry(JPluginHelper::getPlugin('user', 'regweb')->params);
		
		$this->pageTitle = $params->get('config_profile_title');
		$this->infoText = $params->get('config_profile_infotext');
		$this->formTitle = $params->get('config_profile_form_title');
		
		parent::display($tpl);
	}
	
}