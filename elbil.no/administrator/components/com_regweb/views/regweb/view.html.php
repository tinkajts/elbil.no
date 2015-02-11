<?php

defined('_JEXEC') or die;

class RegwebViewRegweb extends JViewLegacy {
	
	public function display($tpl = null) {
		JToolBarHelper::title(JText::_('COM_REGWEB_MANAGER_TITLE'));
		JToolBarHelper::preferences('com_regweb');
		
		parent::display($tpl);
		
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REGWEB_ADMINISTRATION'));
	}
	
}