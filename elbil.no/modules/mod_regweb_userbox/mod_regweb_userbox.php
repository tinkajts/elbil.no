<?php
defined('_JEXEC') or die('Restricted access');
require_once __DIR__ . '/helper.php';

$type	= modRegwebUserboxHelper::getType();
$return	= modRegwebUserboxHelper::getReturnURL($params, $type);
$user	= JFactory::getUser();

$authParams = new JRegistry(JPluginHelper::getPlugin('authentication', 'regweb')->params);

require JModuleHelper::getLayoutPath('mod_regweb_userbox');