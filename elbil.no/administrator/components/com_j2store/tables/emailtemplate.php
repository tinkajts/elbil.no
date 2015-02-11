<?php

// No direct access
defined('_JEXEC') or die;

class TableEmailtemplate extends JTable
{

	/**
	 * Constructor
	 *
	 * @param JDatabase A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__j2store_emailtemplates', 'emailtemplate_id', $db);
	}

}
