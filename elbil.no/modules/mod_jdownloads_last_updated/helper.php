<?php
/**
* @version $Id: mod_jdownloads_last_updated.php v2.0
* @package mod_jdownloads_last_updated
* @copyright (C) 2011 Arno Betz
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author Arno Betz http://www.jDownloads.com
*
*
* This modul shows you the last updated downloads from the jDownloads component. 
* It is only for jDownloads 1.9 and later (Support: www.jDownloads.com)
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class modJdownloadsLastUpdatedHelper
{
	static function getList($params)
	{
		$database   = JFactory::getDBO(); 
		$user       = JFactory::getUser(); 
		$config     = JFactory::getConfig();
		$app        = JFactory::getApplication();
		$appParams  = $app->getParams();
		
		$cat_id     = trim($params->get( 'cat_id' ) );
		$sum_view   = intval(($params->get( 'sum_view' ) ));
        $in_groups  = 0; 

		if ($sum_view == 0) $sum_view = 5;

		// get config value
		$database->setQuery("SELECT setting_value FROM #__jdownloads_config WHERE setting_name = 'days.is.file.updated'");
		$days = $database->loadResult();
		if (!$days) $days = 15;

		// set value for query
		$until_day = mktime(0,0,0,date("m"), date("d")-$days, date("Y"));

		$until = date('Y-m-d H:m:s', $until_day);

		// get user categories access rights
		/* special user group:
		 3 = author
		 4 = editor
		 5 = publisher
		 6 = manager
		 7 = admin
		 8 = super admin - super user
		*/ 
		
        $coreUserGroups = $user->getAuthorisedGroups();
        $aid = max ($user->getAuthorisedViewLevels());
        
        $access = '';
        if ($aid == 1) $access = '02'; // public
        if ($aid == 2) $access = '11'; // regged or member from custom joomla group
        if ($aid == 3 || in_array(3,$coreUserGroups) || in_array(4,$coreUserGroups) || in_array(5,$coreUserGroups) || in_array(6,$coreUserGroups)) $access = '22'; // special user
        if (in_array(8,$coreUserGroups) || in_array(7,$coreUserGroups)){
            // is admin or super user
            $access = '99';
        }
        if (!$access){
            if ($user->id){
                $access = '11';
            } else {
                $access = '02';
            }
        } 
		
        // get cat access groups
        if ($user->id){
            $database->setQuery("SELECT id FROM #__jdownloads_groups WHERE FIND_IN_SET($user->id, groups_members)");
            $in_groups = $database->loadColumn();
            if ($in_groups){
                $in_groups = implode(',', $in_groups); 
            } 
        } 
        if (!$in_groups) $in_groups = 9999999;
			
		// only given cat id's
		$catids = array(); 
		if ($cat_id != 0) {
			$database->setQuery("SELECT cat_id FROM #__jdownloads_cats WHERE published = 1 AND cat_id IN ($cat_id) AND (cat_access <= '$access' OR cat_group_access IN ($in_groups))");
		} else {
			// all categories
			$database->setQuery("SELECT cat_id FROM #__jdownloads_cats WHERE published = 1 AND (cat_access <= '$access' OR cat_group_access IN ($in_groups))");
		}
		$catids = $database->loadColumn(0);

		if ($catids){
			$catid = implode(',', $catids);
			$database->setQuery('SELECT * FROM #__jdownloads_files WHERE published = 1 AND cat_id IN ('.$catid.') AND update_active = 1 AND modified_date >= "'.$until.'" ORDER BY modified_date DESC LIMIT '.$sum_view);
		}
		$files = $database->loadObjectList();

		return $files;
	}
}	
?>