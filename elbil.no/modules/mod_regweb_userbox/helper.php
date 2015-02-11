<?php

class modRegwebUserboxHelper {
	
	// Copied from mod_login
	static function getReturnURL($params, $type)
	{
		if ($return = JRequest::getVar('return', '', 'method', 'base64')) {
			$return = base64_decode($return);
			if (JURI::isInternal($return)) {
				return base64_encode($return);
			}
		}
        $authParams = new JRegistry(JPluginHelper::getPlugin('authentication', 'regweb')->params);
        $configRedirect = $userGroup = $authParams->get('login_redirect');
		return base64_encode($configRedirect);
	}
	
	// Copies from mod_login
	static function getType()
	{
		$user = JFactory::getUser();
		return (!$user->get('guest')) ? 'logout' : 'login';
	}
}