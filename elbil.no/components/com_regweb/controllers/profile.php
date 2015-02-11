<?php
require_once JPATH_LIBRARIES . '/regweb/vendor/autoload.php';

use JoomlaRegweb\JoomlaRegweb;

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class RegwebControllerProfile extends JControllerLegacy
{
	
	public function save() {
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		$app = JFactory::getApplication();
		
		$model = $this->getModel('profile');
		if ($model->save($data)) {
			$this->setRedirect(JRoute::_('index.php?option=com_regweb&view=profile'),
								JText::_('COM_REGWEB_PROFILE_SAVE_SUCCESS'));
			return;
		} else {
			if (isset($data['regweb']['password'])) {
				unset($data['regweb']['password']);
				unset($data['regweb']['password2']);
			}
			$app->setUserState('regweb.profile.formdata', $data);
			$this->setRedirect(JRoute::_('index.php?option=com_regweb&view=profile'));
			return;
		}
	}
}
