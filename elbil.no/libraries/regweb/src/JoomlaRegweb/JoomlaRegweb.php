<?php
namespace JoomlaRegweb;

jimport('joomla.application.component.helper');

use Regweb\RegwebApi;
use Regweb\Authorization\AuthSessionHandler;
use Regweb\Rest\MetaData;
use Regweb\Logger\Logger;
use Regweb\Authorization\CredentialsAuthorization;

class JoomlaRegweb {
	public $authHandler;
	public $api;
	
	public function __construct() {
		$regwebParams 	= \JComponentHelper::getParams('com_regweb');
		$regwebUrl 		= $regwebParams->get('regweb_url');
		$clientId 		= $regwebParams->get('client_id');
		$clientSecret 	= $regwebParams->get('client_secret');
		
		$session = new AuthSessionHandler();
		$meta = new MetaData();
		$logger = new Logger($meta);
		
		$this->authHandler = new CredentialsAuthorization(	$regwebUrl,
															$clientId,
															$clientSecret,
															$session,
															$meta,
															$logger);
		
		$this->api = new RegwebApi($regwebUrl, $this->authHandler, $meta, $logger);
	}
	
	protected static $instance;
	
	/**
	 * Static instance
	 * @return RegwebApi
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new JoomlaRegweb();
		}
		return self::$instance;
	}
}