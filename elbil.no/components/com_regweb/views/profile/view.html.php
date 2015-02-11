<?php

defined('_JEXEC') or die;

require_once JPATH_LIBRARIES . '/regweb/vendor/autoload.php';
use JoomlaRegweb\JoomlaRegweb;

class RegwebViewProfile extends JViewLegacy {
	
	public function display($tpl = null) {
		$app = JFactory::getApplication();
		
		// Make sure this is a logged in regweb user
		$regweb = JoomlaRegweb::getInstance();
		if (!$regweb->api->isLoggedIn()) {
			$app->redirect(JRoute::_('index.php?option=com_regweb&view=login'),
					JText::_('COM_REGWEB_RESTRICTED_TO_MEMBER'));
			return;
		}
		
		$userData = $regweb->api->getUser();
		if (!$userData->isMember) {
			$app->redirect(JRoute::_('index.php?option=com_regweb&view=login'),
					JText::_('COM_REGWEB_RESTRICTED_TO_MEMBER'));
			return;
		}
		
		$this->getModel()->setFormData($app->getUserState('regweb.profile.formdata'));
		$app->setUserState('regweb.profile.formdata', null);
		
		$this->setLayout('default');
		
		// View variables
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