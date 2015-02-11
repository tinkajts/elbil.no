<?php
require_once JPATH_LIBRARIES . '/regweb/vendor/autoload.php';

use JoomlaRegweb\JoomlaRegweb;
defined('_JEXEC') or die();

jimport('joomla.application.component.helper');

/**
 * Authenticates towards the regweb system.
 * We send a hidden field when we want regweb login to allow other logins next
 * to this. So this only kicks in when "regweb_login" == '1' and we are on the site.
 * 
 * The system plugin must be configured for regweb authentication to kick in
 * when users try to access a restricted resource and is sent to login screen.
 * The normal joomla login will not initiate regweb login by design.
 */
class plgAuthenticationRegweb extends JPlugin {
	
	public $_name = 'Regweb authentication';
	
	function onUserAuthenticate(&$credentials, $options, &$response) {
		JFactory::getLanguage()->load('plg_authentication_regweb', JPATH_ADMINISTRATOR);
		$app = JFactory::getApplication();
		$input = $app->input;
		
		if ($app->isAdmin()) {
			return;
		}
		
		// Require a hidden field to allow other login types next to this
		if ($input->get('regweb_login') !== '1') {
			return;
		}
		
		$regweb = JoomlaRegweb::getInstance();
		
		if ($credentials['username'] == '' || $credentials['password'] == '') {
			$app->redirect(	'index.php?option=com_regweb&view=login',
                            $this->params->get('login_failed_text'),
							'error');
			return;
		}
		
		$loginResult = $regweb->authHandler->authorizeCredentials($credentials['username'], $credentials['password']);
		if (!$loginResult->success) {
			if ($loginResult->activeCheckFailed) {
				$errorMsg = $this->params->get('login_failed_not_active_text');
			} elseif ($loginResult->uniqueEmailCheckFailed) {
				$errorMsg = JText::_('PLG_AUTH_REGWEB_LOGIN_FAILED_UNIQUE_EMAIL_FAILED');
			} elseif ($loginResult->missingParams) {
				$errorMsg = $this->params->get('login_failed_text');
			} else {
				$errorMsg = $this->params->get('login_failed_text');
			}
			$app->redirect(	'index.php?option=com_regweb&view=login',
							$errorMsg,
							'error');
			return;
		}

		$userData = $regweb->api->getUser();
		
		if ($userData->email == '') {
			$app->redirect(	'index.php?option=com_regweb&view=login',
                            $this->params->get('login_failed_no_email_text'),
							'error');
			return;
		}

        $credentials['username'] = $userData->member->id;

		$response->status = JAuthentication::STATUS_SUCCESS;
        $response->username = $userData->member->id;
		$response->email = $userData->email;
		$response->fullname = $userData->firstname . ' ' . $userData->lastname;
		$response->password_clear = '';
		
		// Make sure user is in configured usergroup
		// We do not have a saved member on the first login
		$userId = JUserHelper::getUserId($userData->member->id);
		if ($userId) {
			$userGroup = $this->params->get('config_usergroup');
			if ($userGroup) {
				JUserHelper::addUserToGroup($userId, $userGroup);
			}
		}
	}
}