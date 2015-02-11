<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

class RegwebModelLostpassword extends JModelForm
{
	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm('com_regweb.lostpassword', 'lostpassword', array('control' => 'jform', 'load_data' => $loadData));
		return $form;
	}
	
	public function processLostpasswordRequest($data) {
		
		$form = $this->getForm();
		$data = $this->validate($form, $data);
		
		if ($data === false) {
			foreach ($form->getErrors() as $message) {
				$this->setError($message);
			}
			return false;
		}
		return true;
	}
}