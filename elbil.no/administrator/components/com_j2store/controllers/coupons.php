<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_j2store/tables');
class J2StoreControllerCoupons extends J2StoreController
{

	function __construct($config = array())
	{
		parent::__construct($config);
		//	print_r(JRequest::get('post')); exit;
		// Register Extra tasks
		$this->registerTask( 'add',  'display' );
		$this->registerTask( 'edit', 'display' );
	}

	function display($cachable = false, $urlparams = array()) {
		$app = JFactory::getApplication();

		switch($this->getTask())
		{
			case 'add'     :
				{
					$app->input->set( 'hidemainmenu', 1 );
					$app->input->set( 'layout', 'edit'  );
					$app->input->set( 'view'  , 'coupon');
					$app->input->set( 'edit', false );

				} break;
			case 'edit'    :
				{
					$app->input->set( 'hidemainmenu', 1 );
					$app->input->set( 'layout', 'edit'  );
					$app->input->set( 'view'  , 'coupon');
					$app->input->set( 'edit', true );

				} break;
		}
		parent::display();
	}

	function save() {

		$post	= JFactory::getApplication()->input->getArray($_POST);
		JRequest::checkToken() or jexit('Invalid Token');
		$table = $this->getModel('coupon')->getTable('coupon');;
		if ($table->save($post)) {
			$msg = JText::_( 'J2STORE_COUPON_SAVED' );
			$link = 'index.php?option=com_j2store&view=coupons';
		} else {
			$msg = $table->getError();
			if($post['coupon_id']) {
				$link = 'index.php?option=com_j2store&view=coupons&task=edit&cid[]='.$post['coupon_id'];
			} else {
				$link = 'index.php?option=com_j2store&view=coupons&task=add';
			}
		}
		$this->setRedirect($link, $msg);
	}

	function publish()
	{

		$app = JFactory::getApplication();
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = $app->input->get( 'cid', array(), 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'J2STORE_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}

		$model = $this->getModel('coupon');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_j2store&view=coupons' );
	}


function unpublish()
	{

		$app = JFactory::getApplication();
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = $app->input->get( 'cid', array(), 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'J2STORE_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}

		$model = $this->getModel('coupon');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_j2store&view=coupons' );
	}


	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		// Checkin the j2store
		$this->setRedirect( 'index.php?option=com_j2store&view=coupons' );
	}


	function remove(){

		$model = $this->getModel('coupon');
		$table = $model->getTable();
		$cids = JFactory::getApplication()->input->get('cid', array(0), 'ARRAY');
		$error = 0;
		if (count( $cids ))
		{
			foreach($cids as $cid) {
				if(!$table->delete($cid)) {
					$error = 1;
				}
			}
		}

		if($error) {
			$msg = JText::_('J2STORE_ERROR_DELETING');
		} else {
			$msg = JText::sprintf('COM_J2STORE_N_ITEMS_DELETED', count($cids));
		}
		$this->setRedirect( 'index.php?option=com_j2store&view=coupons', $msg);
	}

}
