<?php
/**
 * @version		3.1.111 administrator/components/com_j2xml/views/categories/view.raw.php
 * @package		J2XML
 * @subpackage	com_j2xml
 * @since		1.5.3beta5.43
 *
 * @author		Helios Ciancio <info@eshiol.it>
 * @link		http://www.eshiol.it
 * @copyright	Copyright (C) 2010-2013 Helios Ciancio. All Rights Reserved
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL v3
 * J2XML is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access.');

jimport('eshiol.j2xml.exporter');

/**
 * J2XML Component Content View
 */
class J2XMLViewCategories extends JViewAbstract
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$cid = JRequest::getVar('cid');		
		$ids = explode(",", $cid);

		$params = JComponentHelper::getParams('com_j2xml');
		$images = array();
		
		$xml = J2XMLExporter::categories($ids,
			$params->get('export_images', '1'),
			true,
			$params->get('export_users', '1'),
			$images
			);
		foreach ($images as $image)
			$xml .= $image;	
		
		if (!J2XMLExporter::export(
				$xml,		
				$params->get('debug', 0), 
				$params->get('export_gzip', '0')
			))
			$app->redirect('index.php?option=com_categories&extension=com_content');
	}
}
?>