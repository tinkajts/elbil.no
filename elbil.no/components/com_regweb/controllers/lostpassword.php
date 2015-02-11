<?php
require_once JPATH_LIBRARIES . '/regweb/vendor/autoload.php';

use JoomlaRegweb\JoomlaRegweb;

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class RegwebControllerLostpassword extends JControllerLegacy
{
	public function lostpassword() {
		$model = $this->getModel('lostpassword', 'RegwebModel');
		
		$view = $this->getView('lostpassword');
		$view->setModel($model);
		$view->setLayout('default');
		
		$view->display();
	}
	
	public function request() {
		JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
		
		$data = JRequest::getVar('jform', array(), 'request', 'array');
		$model = $this->getModel('lostpassword', 'RegwebModel');
		
		if (!$model->processLostpasswordRequest($data)) {
			$message = JText::_('COM_REGWEB_LOSTPASSWORD_REQUEST_FAILED');
			$this->setRedirect(JRoute::_('index.php?option=com_regweb&view=lostpassword', false), $message, 'notice');
			return false;
		}
		
		$regweb = new JoomlaRegweb();
		try {
			$response = $regweb->api->lostPassword($data['identification']);
			if (!$response['success']) {
				$message = JText::_('COM_REGWEB_LOSTPASSWORD_REQUEST_FAILED');
				$this->setRedirect(JRoute::_('index.php?option=com_regweb&view=lostpassword', false), $message, 'notice');
				return false;
			}
		} catch (Exception $e) {
			if (isset($e->response) && $e->response->statusCode == 404) {
				$message = JText::_('COM_REGWEB_LOSTPASSWORD_REQUEST_FAILED_NOT_FOUND');
			} else {
				$message = JText::_('COM_REGWEB_LOSTPASSWORD_REQUEST_FAILED');
			}
			$this->setRedirect(JRoute::_('index.php?option=com_regweb&view=lostpassword', false), $message, 'notice');
			return false;
		}
		
		
		$this->setRedirect(JRoute::_('index.php?option=com_regweb&view=lostpassword_requested'));
	}
}
