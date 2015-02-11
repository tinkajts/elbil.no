<?php
use JoomlaRegweb\JoomlaRegweb;
defined('_JEXEC') or die('Restricted access');

require_once JPATH_LIBRARIES . '/regweb/vendor/autoload.php';


jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldRegwebOptionalSelect extends JFormFieldList {
	
	public $type = 'RegwebOptionalSelect';
	
	public function getOptions() {
		$regweb = JoomlaRegweb::getInstance();
        $fieldId = substr($this->element['id'], -1);
		$values = $regweb->api->getOptionalSelectValues($fieldId);
		$options = array();
		
		$options[] = JHtml::_('select.option', 0, '&nbsp;');
		foreach ($values->values as $value) {
			$options[] = JHtml::_('select.option', $value->id, $value->label);
		}
		return $options;
	}
}