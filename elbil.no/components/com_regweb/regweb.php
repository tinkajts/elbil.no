<?php
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT . '/controller.php';

$controller = JControllerLegacy::getInstance('regweb');

$controller->execute(JFactory::getApplication()->input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();