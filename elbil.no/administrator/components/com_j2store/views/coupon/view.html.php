<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 */
class J2StoreViewCoupon extends J2StoreView
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		$model		= $this->getModel('coupon');
		$params = JComponentHelper::getParams('com_j2store');
		// get order data
		$data	= $this->get('Data');
		$isNew		= ($data->coupon_id < 1);

		if($isNew) {
			$data->state = 1;
		}

		$lists = array();
		$arr = array(JHTML::_('select.option', '0', JText::_('No') ),
					JHTML::_('select.option', '1', JText::_('Yes') )	);
		$lists['published'] = JHTML::_('select.radiolist', $arr, 'state', null, 'value', 'text', $data->state);

		$value_type_options = array(JHTML::_('select.option', 'F', JText::_('J2STORE_VALUE_TYPE_FIXED_PRICE') ),
				JHTML::_('select.option', 'P', JText::_('J2STORE_VALUE_TYPE_PERCENTAGE') )	);


		$lists['value_type'] = JHTML::_('select.radiolist', $value_type_options, 'value_type', null, 'value', 'text', $data->value_type);

		$logged_options = array(JHTML::_('select.option', '0', JText::_('No') ),
				JHTML::_('select.option', '1', JText::_('Yes') )	);
		$lists['logged'] = JHTML::_('select.radiolist', $logged_options, 'logged', null, 'value', 'text', $data->logged);

		$this->assignRef('item',	$data);
		$this->assignRef('lists',	$lists);
		$this->assignRef('params',	$params);

		$this->addToolBar();
		$toolbar = new J2StoreToolBar();
        $toolbar->renderLinkbar();

		parent::display($tpl);

	}

	protected function addToolBar() {
			 // setting the title for the toolbar string as an argument
			   JToolBarHelper::title(JText::_('J2STORE_COUPONS'),'j2store-logo');

				// Set toolbar items for the page
				$edit		= JRequest::getVar('edit',true);
				$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
				JToolBarHelper::title(   JText::_( 'J2STORE_COUPONS' ).': [ ' . $text.' ]' );
				JToolBarHelper::save();
				if (!$edit)  {
					JToolBarHelper::cancel();
				} else {
					// for existing items the button is renamed `close`
					JToolBarHelper::cancel( 'cancel', 'JTOOLBAR_CLOSE' );
				}


		 }
}
