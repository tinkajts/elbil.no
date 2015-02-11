<?php/** * @version		3.1.112 administrator/components/com_j2xml/controllers/categories.json.php *  * @package		J2XML * @subpackage	com_j2xml * @since		3.1.111 *  * @author		Helios Ciancio <info@eshiol.it> * @link		http://www.eshiol.it * @copyright	Copyright (C) 2010-2013 Helios Ciancio. All Rights Reserved * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL v3 * J2XML is free software. This version may have been modified pursuant * to the GNU General Public License, and as distributed it includes or * is derivative of works licensed under the GNU General Public License or * other free or open source software licenses. */ // no direct accessdefined('_JEXEC') or die('Restricted access.');jimport('joomla.application.component.controller');jimport('eshiol.j2xml.exporter');jimport('eshiol.j2xml.sender');
if (version_compare(JPlatform::RELEASE, '12', 'ge'))	jimport('cms.response.json');else	require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_languages'.DS.'helpers'.DS.'jsonresponse.php');/** * Content controller class. */class J2XMLControllerCategories extends JControllerAbstract{				function __construct($default = array())	{		parent::__construct();	}	public function display($cachable = false, $urlparams = false)	{		JRequest::setVar('view', 'categories');		parent::display($cachable, $urlparams);	}		function send()	{		if (!JSession::checkToken('request'))		{			// Check for a valid token. If invalid, send a 403 with the error message.			JError::raiseWarning(403, JText::_('JINVALID_TOKEN'));			echo (version_compare(JPlatform::RELEASE, '12', 'ge')) ? new JResponseJson() : new JJsonResponse();			return;		}		$cid = JRequest::getVar('cid', array(0), null, 'array');		$sid = JRequest::getVar('w_id', null, null, 'int');				if (!$sid)			$sid = JRequest::getVar('j2xml_send_id', null, null, 'int');				if (!$sid)		{			JError::raiseWarning(1, JText::_('UNKNOWN_HOST'));
			echo (version_compare(JPlatform::RELEASE, '12', 'ge')) ? new JResponseJson() : new JJsonResponse();			return;				}								$params = JComponentHelper::getParams('com_j2xml');		$images = array();		$xml = J2XMLExporter::categories($cid,			$params->get('export_images', '1'),			true,			$params->get('export_users', '1'),			$images			);		foreach ($images as $image)			$xml .= $image;		J2XMLSender::send(			$xml,					$params->get('debug', 0), 			$params->get('export_gzip', '0'),			$sid		);		echo (version_compare(JPlatform::RELEASE, '12', 'ge')) ? new JResponseJson() : new JJsonResponse();	}}?>