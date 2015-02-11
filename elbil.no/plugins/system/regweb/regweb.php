<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.helper');

/**
 * Plugin to redirect from standard joomla login page to custom regweb login.
 * This is configurable in the plugin administration page.
 */
class plgSystemRegweb extends JPlugin {
	
	public $_name = 'Regweb system';
	
	function onAfterRoute() {
		$app = JFactory::getApplication();
		if (!$app->isSite()) {
			return;
		}
		
		$input = $app->input;
		
		// Variables used for routing
		$option = $input->get('option');
		$view = $input->get('view');
		$task = $input->get('task');
		
		if (!is_null($option) && $option == 'com_users') {
			
			if ((is_null($view) && is_null($task)) || ($view == 'login' && is_null($task)) || $task == 'login') {
				// Default page of com_users is login, or view login specified
				$option = $this->params->get('login_option', 'com_regweb');
				$view = $this->params->get('login_view', 'login');
				$app->redirect('index.php?option='.$option.(($view == '') ? '' : '&view='.$view));
				return;
				
			}
		}
	}
}