<?php
/**
 * @version		13.4.107 tables/content.php
 * @package		J2XML
 * @subpackage	lib_j2xml
 * @since		1.5.1
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

class eshTableContent extends eshTable
{	
	/**
	* @param database A database connector object
	*/
	function __construct(&$db) {
		parent::__construct('#__content', 'id', $db);
	}

	/**
	 * Export item list to xml
	 *
	 * @access public
	 */
	function toXML($mapKeysToText = false)
	{
		// Initialise variables.
		$xml = array();
		
		// Open root node.
		$xml[] = '<content>';

		$xml[] = parent::_serialize( // $excluded,$aliases,$jsons
			array('asset_id','sectionid','mask','checked_out','checked_out_time','parentid','title_alias'), 
			array(
				'catid'=>'SELECT path FROM #__categories WHERE id = '.(int)$this->catid,
				'created_by'=>'SELECT username FROM #__users WHERE id = '.(int)$this->created_by,
				'modified_by'=>'SELECT username modified_by FROM #__users WHERE id = '.(int)$this->modified_by,
				'featured'=>'SELECT IFNULL(f.ordering,0) FROM #__content_frontpage f RIGHT JOIN #__content a ON f.content_id = a.id WHERE a.id = '. (int)$this->id,
				'rating_sum'=>'SELECT IFNULL(rating_sum,0) FROM #__content_rating f RIGHT JOIN #__content a ON f.content_id = a.id WHERE a.id = '. (int)$this->id,
				'rating_count'=>'SELECT IFNULL(rating_count,0) FROM #__content_rating f RIGHT JOIN #__content a ON f.content_id = a.id WHERE a.id = '. (int)$this->id
				),
			array() //'attribs', 'metadata', 'images', 'urls')
			);

		// Close root node.
		$xml[] = '</content>';
				
		// Return the XML array imploded over new lines.
		return implode("\n", $xml);
	}
}
