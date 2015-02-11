<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RegwebController extends JControllerLegacy {
	
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	public function display($cachable = false, $urlparams = false) {
		$document	= JFactory::getDocument();
		$vName	 = JRequest::getCmd('view', 'login');
		$vFormat = $document->getType();
		
		if ($view = $this->getView($vName, $vFormat)) {
			switch ($vName) {
				case 'lostpassword':
					$model = $this->getModel($vName);
					$view->setModel($model, true);
					break;
				case 'profile':
					$model = $this->getModel('profile');
					$view->setModel($model, true);
					break;
			}
			$view->setLayout('default');
			$view->assignRef('document', $document);
			
			$view->display();
		}
	}
}