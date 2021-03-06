<?php
/**
 * @package		Zen Tools
 * @subpackage	Zen Tools
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2013 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		1.11.5
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Create a category selector
class JFormFieldClose extends JFormField
{

	protected $type = 'close';

	protected function getInput()
	{
		// Output
		return '
		</div>
		';
	}

	protected function fetchTooltip($label, $description, &$node, $control_name, $name)
	{
		// Output
		return '&nbsp;';
	}
}
