<?php
defined('_JEXEC') or die;

$authParams = new JRegistry(JPluginHelper::getPlugin('authentication', 'regweb')->params);
?>

<h1><?php  echo JText::_('COM_REGWEB_LOST_PASSWORD_REQUESTED_TITLE'); ?></h1>

<p><?php echo $authParams->get('forgotpassconfirmtext'); ?></p>