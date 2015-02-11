<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

// import Joomla view library
jimport('joomla.application.component.view');

class J2StoreViewGeozones extends J2StoreView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $countries;

	function display($tpl = null)
	{
		// Get data from the model
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		// inturn calls getState in parent class and populateState() in model
		$this->state = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		//add toolbar
		$this->addToolBar();

		$toolbar = new J2StoreToolBar();
		$toolbar->renderLinkbar();
		$this->setDocument();
		// Display the template
		parent::display($tpl);

	}

	protected function addToolBar() {
		// setting the title for the toolbar string as an argument
		JToolBarHelper::title(JText::_('J2STORE_GEOZONES'),'j2store-logo');
		$state	= $this->get('State');
		JToolBarHelper::back();
		JToolBarHelper::divider();
		// check permissions for the users
		JToolBarHelper::addNew('geozone.add','JTOOLBAR_NEW');
		JToolBarHelper::divider();
		JToolBarHelper::editList('geozone.edit','JTOOLBAR_EDIT');
		JToolBarHelper::divider();
		JToolBarHelper::custom('geozones.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::custom('geozones.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		if($state == '-2' ) {
			JToolBarHelper::deleteList('', 'geozones.delete','JTOOLBAR_EMPTY_TRASH');
		} else {
			JToolBarHelper::trash('geozones.trash', 'JTOOLBAR_TRASH');
		}
	}


	protected function setDocument() {
		// get the document instance
		$document = JFactory::getDocument();
		// setting the title of the document
		$document->setTitle(JText::_('J2STORE_GEOZONES'));
	}


	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
				'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
				'a.state' => JText::_('JSTATUS'),
				'a.geozone_name' => JText::_('J2STORE_GEOZONE_NAME'),
				'a.geozone_id' => JText::_('J2STORE_GEOZONE_ID')
		);
	}

}
