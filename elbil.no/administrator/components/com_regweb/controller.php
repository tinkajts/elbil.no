<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RegwebController extends JControllerLegacy {
	
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	public function display($cachable = false, $urlparams = false) {
		parent::display($cachable, $urlparams);
	}
	
}