<?php
/*
  # jDownloads content plugin
  # version 3.0
  # for Joomla 3.1
  # Original created by Marco Pelozzi - marco.u3@bluewin.ch - www.redorion.com/plugindemo
  # modified by Arno Betz - jDownloads.com
*/
defined( '_JEXEC' ) or die( 'Restricted access' ); 

jimport( 'joomla.plugin.plugin' );

global $cat_link_itemidsPlg;
$database = JFactory::getDBO();
$mainframe = JFactory::getApplication(); 

if(!defined('DS')){
    define('DS',DIRECTORY_SEPARATOR);
} 

// get all published single category menu links
$database->setQuery("SELECT id, link from #__menu WHERE link LIKE 'index.php?option=com_jdownloads&view=viewcategory&catid%' AND published = 1");
$cat_link_itemidsPlg = $database->loadAssocList();
if ($cat_link_itemidsPlg){
    for ($i=0; $i < count($cat_link_itemidsPlg); $i++){
         $cat_link_itemidsPlg[$i][catid] = substr( strrchr ( $cat_link_itemidsPlg[$i][link], '=' ), 1);
    }    
}

//Globals definition
$GLOBALS['jDFPitemid'] = jd_CalcItemid();
$GLOBALS['jDFPOnlineLayout'] = '';
$GLOBALS['jlistConfigM'] = buildjlistConfigM();
$GLOBALS['jDownloadsMessage'] = 0;
$GLOBALS['jDownloadsTested'] = 0;
$GLOBALS['jDownloadsInstalled'] = 0;
$GLOBALS['jDownloadsVersion'] = 0;
$GLOBALS['jDFPshowunpublished'] = 0;
$GLOBALS['jDFPconsiderrights'] = 1;
$GLOBALS['jDFPv14'] = 0;
$GLOBALS['jDFPsfolders'] = jd_SymbolFolders();
$GLOBALS['jDFPpluginversion'] = '1.5';
$GLOBALS['jDFPrank'] = 1;
$GLOBALS['jDFPison'] = 1;
$GLOBALS['jDFPcatids'] = '';
$GLOBALS['jDFPloaded'] = 0;
$GLOBALS['jDLayoutTitleExists'] = false;

  // Parameters if Joomla! = 1.5 1.5 1.5 1.5 1.5 1.5 1.5 1.5 1.5 1.5 1.5 1.5 1.5 1.5
  // Register the plugin
  //$mainframe->registerEvent( 'onPrepareContent', 'jdownloads' );

  
  class plgContentJdownloads extends JPlugin
{

    function plgContentJdownloads(&$subject, $params)
    {
        parent::__construct($subject, $params);
    }

    function onContentPrepare($context, &$article, &$params)
    {
        global $mainframe, $jDFPplugin_live_site, $jDFPloaded, $jlistConfigM;         
        //
        $lang = JFactory::getLanguage();
        $lang->load('com_jdownloads', JPATH_ADMINISTRATOR);
      
        // Live site
        $GLOBALS['jDFPlive_site'] = JURI::base();
        // Live site of plugin
        $GLOBALS['jDFPplugin_live_site'] = $GLOBALS['jDFPlive_site'].'plugins/';

        // Absolute path
        $GLOBALS['jDFPabsolute_path'] = JPATH_SITE.DS;
        
        $document = JFactory::getDocument(); 
        
        if ($jDFPloaded == 0){
            $document->addStyleSheet( $jDFPplugin_live_site."content/jdownloads/jdownloads/css/mos_jdownloads_file.css", 'text/css', null, array() );
            if ($jlistConfigM['use.lightbox.function']){
                // Only when lightbox is activated in jD
                $document->addScript($jDFPplugin_live_site.'content/jdownloads/jdownloads/lightbox/lightbox.js');
                $document->addStyleSheet($jDFPplugin_live_site."content/jdownloads/jdownloads/lightbox/lightbox.css", 'text/css', null, array() );
            } 
        }     
        $jDFPloaded = 1;
        if (isset( $_GET['mjdfpp'])){
            if ($_GET['mjdfpp'] == 'show'){
                $article->text = jd_file_parameters().$row->text;
                return true;
            }
        }
        $regex = "#{jd_file (.*?)==(.*?)}#s";
        $article->text = preg_replace_callback($regex, "jd_file_callback", $article->text);

        return true;
    }
}

// Calculate Symbolfolders depending on jDownloads 1.3 and 1.4 and set global jDownloads version 1.4 or <= 1.3
function jd_SymbolFolders(){
   global $jlistConfigM, $jDFPv14;
   $jd_l_folders = array();
   $jd_l_folders['thumb'] = 'images/jdownloads/screenshots/thumbnails/';
   $jd_l_folders['screenshot'] = 'images/jdownloads/screenshots/';
   $jDFPv14 = 1;
   $jd_l_folders['symbolfolder'] = 'images/jdownloads/';
   $jd_l_folders['cat'] = 'images/jdownloads/catimages/';
   $jd_l_folders['download'] = 'images/jdownloads/downloadimages/';
   $jd_l_folders['file'] = 'images/jdownloads/fileimages/';
   $jd_l_folders['hot'] = 'images/jdownloads/hotimages/';
   $jd_l_folders['mini'] = 'images/jdownloads/miniimages/';
   $jd_l_folders['new'] = 'images/jdownloads/newimages/';
   $jd_l_folders['upd'] = 'images/jdownloads/updimages/';
   return $jd_l_folders;
}

function jd_checkAccess(){
  global $jDFPconsiderrights;
  
    // special user group:
    // 3 = author
    // 4 = editor
    // 5 = publisher
    // 6 = manager
    // 7 = admin
    // 8 = super admin - super user
      
    if ($jDFPconsiderrights == 0){
        $access = '22';
        return $access;
    }
    
    $user = JFactory::getUser();
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
    
    return $access;
}  


function jd_emailcloak($p_email, $p_author){
  
  
  if (!$p_author) return JHTML::_('email.cloak', $p_email);
  else return JHTML::_('email.cloak', $p_email, true, $p_author, false);
  
}

function jd_SefRel($p_Url){
  
      return JRoute::_($p_Url);
  
}

function jd_DateForm($p_Date){
  global $jlistConfigM;
  
    return JHTML::Date($p_Date, $jlistConfigM['global.datetime'], $offset = NULL);
  
}

// Read configuration of jDownloads from database
function buildjlistConfigM(){
  global $jDFPOnlineLayout;
  $database = JFactory::getDBO();
  $jlistConfig = array();
  $database->setQuery("SELECT setting_name, setting_value FROM #__jdownloads_config");
  $jlistConfigObj = $database->loadObjectList();
  if(!empty($jlistConfigObj)){
    foreach ($jlistConfigObj as $jlistConfigRow){
      $jlistConfig[$jlistConfigRow->setting_name] = $jlistConfigRow->setting_value;
    }
    if (!$jlistConfig['days.is.file.updated']){
      $jlistConfig['days.is.file.updated'] = 0;
    }
    if (!$jlistConfig['jd.version.svn']){
      $jlistConfig['jd.version.svn'] = 0;
    }
  }
  if ($jDFPOnlineLayout == '') {
    $jDFPOnlineLayout = $jlistConfig['fileplugin.defaultlayout'];
  }
  return $jlistConfig;
}

function DatumsDifferenz_JDm($Start,$Ende) {
    $Tag1=(int) substr($Start, 8, 2);
    $Monat1=(int) substr($Start, 5, 2);
    $Jahr1=(int) substr($Start, 0, 4);

    $Tag2=(int) substr($Ende, 8, 2);
    $Monat2=(int) substr($Ende, 5, 2);
    $Jahr2=(int) substr($Ende, 0, 4);

    if (checkdate($Monat1, $Tag1, $Jahr1)and checkdate($Monat2, $Tag2, $Jahr2)){
        $Datum1=mktime(0,0,0,$Monat1, $Tag1, $Jahr1);
        $Datum2=mktime(0,0,0,$Monat2, $Tag2, $Jahr2);

        $Diff=(Integer) (($Datum1-$Datum2)/3600/24);
        return $Diff;
    } else {
        return -1;
    }
}

function mos_jdownloads_file15( &$row, &$params, $page=0 ) {
	global $mainframe, $jDFPplugin_live_site, $jDFPloaded, $jlistConfigM;
    $document = JFactory::getDocument(); 
    
if ($jDFPloaded == 0)
{

    $mainframe->addCustomHeadTag( "<link href=\"".$jDFPplugin_live_site."content/jdownloads/jdownloads/css/mos_jdownloads_file.css\" rel=\"stylesheet\" type=\"text/css\"/>" );
    if ($jlistConfigM['use.lightbox.function']){
        // Only when lightbox is activated in jD
        $document->addScript($jDFPplugin_live_site.'content/jdownloads/jdownloads/lightbox/lightbox.js');
        $document->addStyleSheet($jDFPplugin_live_site."content/jdownloads/jdownloads/lightbox/lightbox.css", 'text/css', null, array() ); 
    }
}
  $jDFPloaded = 1;
  if (isset( $_GET['mjdfpp'])){
    if ($_GET['mjdfpp'] == 'show'){
       $row->text = jd_file_parameters().$row->text;
       return true;
    }
  }
  $regex = "#{jd_file (.*?)==(.*?)}#s";
  $row->text = preg_replace_callback($regex, "jd_file_callback", $row->text);

  return true;
}


function jd_file_callback($matches){
  global $jDownloadsTested, $jDownloadsMessage, $jDownloadsInstalled, $jDownloadsVersion, $jlistConfigM, $jDFPOnlineLayout, $jDFPrank, $jDFPison;
  $database =  JFactory::getDBO();
  $jdf_whatcontent = $matches[1];

  if ($jdf_whatcontent == 'plugin'){
     switch ($matches[2]){
	 case 'on':
       $jDFPison = 1;
       break;
	 case 'off':
       $jDFPison = 0;
       break;
	 case 'silent':
       $jDFPison = 2;
       break;
     }
     return '';
  }
  if ($jDFPison == 0){
	return $matches[0];
  }
  if ($jDFPison == 2){
	return '';
  }

  // Tester si jDownloads >= 1.3 install?.
  if ($jDownloadsTested == 0) {
    $jDownloadsTested = true;
    $comSQLquery = "SELECT * FROM #__extensions WHERE type = 'component' AND element = 'com_jdownloads'";
    $database->setQuery( $comSQLquery );
    $comrows = $database->loadObjectList();
    if (!$comrows) {
      $jDownloadsInstalled = 0;
    } else {
      $jDownloadsInstalled = 1;
      $jDiVersion = substr($jlistConfigM['jd.version'],0,3);
    }
  }

  // sinon message d'erreur (1 fois) et abandon du programme.
  if ($jDownloadsInstalled == 0) {
    if ($jdf_whatcontent != 'mjdfpp') {
      if ($jDownloadsMessage == 0){
        $jDownloadsMessage = 1;
        return _JDPLUGIN_FRONTEND_JDOWNLOADS_NOTINSTALLED;
      } else {
        return "";
      }
    }
  } 

  // Layouts laden
  if ($jDFPOnlineLayout == '') {
    $jDFPOnlineLayout = $jlistConfigM['fileplugin.defaultlayout'];
  }

  switch ($jdf_whatcontent) {
    case 'file':
      $jDFPrank = '';
      $id_result = jd_file_createdownload($matches);
      break;
    case 'durl':
      $jDFPrank = '';
      $id_result = jd_file_createdownload($matches);
      break;
    case 'mjdfpp':
      $id_result = jd_file_parameters();
      break;
    case 'onlinelayout':
      jd_set_newlayout($matches);
      $id_result = '';
      break;
    case 'latest':
      $id_result =jd_file_latest_hottest($matches);
      break;
    case 'hottest':
      $id_result = jd_file_latest_hottest($matches);
      break;
    case 'updated':
      $id_result = jd_file_latest_updated($matches);
      break;       
    case 'considerrights':
      $id_result = jd_file_changerights($matches);
      break;
    case 'showunpublished':
      $id_result = jd_file_changeunpublished($matches);
      break;
    case 'category':
      $id_result = jd_file_createcategory($matches);
      break;  
    }
  return $id_result;
}

function jd_file_changerights($matches){
	global $jDFPconsiderrights;
    if ($matches[2] == 'off'){
       $jDFPconsiderrights = 0;
    }
    if ($matches[2] == 'on'){
       $jDFPconsiderrights = 1;
    }
}

function jd_file_changeunpublished($matches){
	global $jDFPshowunpublished;
    if ($matches[2] == 'off'){
       $jDFPshowunpublished = 0;
    }
    if ($matches[2] == 'on'){
       $jDFPshowunpublished = 1;
    }
}

function jd_file_parameters (){
  global $jlistConfigM, $jDFPshowunpublished, $jDFPOnlineLayout, $jDFPpluginversion, $jDFPsfolders, $jDFPabsolute_path, $jDFPplugin_live_site, $jDFP_JLanguage, $jDFPitemid, $jDFPloaded, $jDFPv14;
  $database =  JFactory::getDBO();
  //$jconfig = JFactory::getConfig();
  //echo $jconfig->getValue("config.db");

  $showConfig = "";
  $mytdleft = '<tr><td class="jdpf_parameters_l">';
  $mytdmiddle = '</td><td class="jdpf_parameters_r">';
  $mytdright = '</td></tr>';
  $mytdlefth = '<tr><td class="jdpf_parameters_title_l">';
  $mytdmiddleh = '</td><td class="jdpf_parameters_title_r">';
  $mytdrighth = '</td></tr>';
  $showConfig .= '<table class="jdpf_parameters">';
  $showConfig .= '<tr><td colspan=2 class="jdpf_parameters_header"> Current Plugin-Parameters for mos_jdownloads_file:</td></tr>';
  $showConfig .= $mytdlefth."Parameter".$mytdmiddleh."Value".$mytdrighth;
  //$showConfig .= $mytdleft."Joomla version / frontend-language" .$mytdmiddle.$jDFP_JVersion." / ".$jDFP_JLanguage.$mytdright;
  $showConfig .= $mytdleft."Plugin version".$mytdmiddle.$jDFPpluginversion.$mytdright;
  $showConfig .= $mytdleft."jd.version".$mytdmiddle.$jlistConfigM['jd.version']." (".$jlistConfigM['jd.version.state']." SVN:".$jlistConfigM['jd.version.svn'].")".$mytdright;
  $showConfig .= $mytdleft."Flag is v14".$mytdmiddle.$jDFPv14.$mytdright;
  $showConfig .= $mytdleft."Database prefix".$mytdmiddle.$database->getPrefix().$mytdright;
  $showConfig .= $mytdleft."Absolute path".$mytdmiddle.$jDFPabsolute_path.$mytdright;
  $showConfig .= $mytdleft."Live site of plugin".$mytdmiddle.$jDFPplugin_live_site.$mytdright;
  $showConfig .= $mytdleft."ItemID".$mytdmiddle.$jDFPitemid.$mytdright;
  $showConfig .= $mytdleft."Plugin loaded".$mytdmiddle.$jDFPloaded.$mytdright;
  $showConfig .= $mytdleft."Symbols folder".$mytdmiddle.$jDFPsfolders['symbolfolder']."...".$mytdright;

  $database->setQuery("SELECT setting_name, setting_value FROM #__jdownloads_config WHERE setting_name LIKE 'fileplugin%'");
  $jlistConfigObj = $database->loadObjectList();
  if(!empty($jlistConfigObj)){
    foreach ($jlistConfigObj as $jlistConfigRow){
      $s_value = $jlistConfigRow->setting_value;
      if (($jlistConfigRow->setting_name == 'fileplugin.layout_disabled') || ($jlistConfigRow->setting_name == 'fileplugin.show_hot') || ($jlistConfigRow->setting_name == 'fileplugin.show_new')){
        $s_value .= '&nbsp;&nbsp;<font color="#000088">(Notice: Obsolete since 1.4)</font>';
      }

      $showConfig .= $mytdleft.$jlistConfigRow->setting_name.$mytdmiddle.$s_value.$mytdright;
    }
  } else {
    $showConfig .= $mytdleft."Database error".$mytdmiddle."Datbase jdownloads_config missing!".$mytright;
  }
  $showConfig .= $mytdleft."Access level".$mytdmiddle.jd_checkAccess().$mytdright;
  $showConfig .= $mytdleft."Show unpublished".$mytdmiddle.$jDFPshowunpublished.$mytdright;
  $showConfig .= $mytdleft."&nbsp;".$mytdmiddle."&nbsp;".$mytdright;

  $tSQLquery = "SELECT * FROM #__jdownloads_templates WHERE (template_name = '".$jDFPOnlineLayout."') AND (template_typ = 2)";
  $database->setQuery($tSQLquery);
  $onlrows = $database->loadObjectList();
  if (!$onlrows) {
    $OnlineLayoutComment = '<font color="#FF0000"> MISSING !</font>';
  }
  else{
    $OnlineLayoutComment = '<font color="#008800"> AVAILABLE !</font>';
  }
  $showConfig .= $mytdlefth."Layout online".$mytdmiddleh."&quot;".$jDFPOnlineLayout."&quot;".$OnlineLayoutComment.$mytdrighth;
  $onltext = $onlrows[0]->template_text;
  $showConfig .= $mytdleft."Layout online text".$mytdmiddle.'<textarea cols="45" rows="5">'.$onltext."</textarea>".$mytdright;
  $showConfig .= '</table>';
  return $showConfig;
}

function jd_set_newlayout($matches){
  global $jDFPOnlineLayout;
  $jDFPOnlineLayout = $matches[2];
  return '';
}

function inh_rights($pcatid){
    $database =  JFactory::getDBO();
    $sql = "SELECT cat_id, parent_id, cat_access, cat_group_access FROM #__jdownloads_cats WHERE cat_id = ".$pcatid;
    $database->setQuery($sql);
    $crow = $database->loadObjectList();
    if (!$crow){
	  return '00';
    }
    if ($crow[0]->parent_id == 0){
	  return $crow[0]->cat_access;
    }
    $therights = $crow[0]->cat_access;

    while ( $crow[0]->parent_id > 0 ){
      $sql = "SELECT cat_id, parent_id, cat_access, cat_group_access FROM #__jdownloads_cats WHERE cat_id = ".$crow[0]->parent_id;
      $database->setQuery($sql);
      $crow = $database->loadObjectList();
      if ($crow[0]->cat_access > $therights){
      	$therights = $crow[0]->cat_access;
	  }
    }
    return $therights;
}

function inh_published($pcatid){
    $database =  JFactory::getDBO();
    $sql = "SELECT cat_id, parent_id, published FROM #__jdownloads_cats WHERE cat_id = ".$pcatid;
    $database->setQuery($sql);
    $crow = $database->loadObjectList();
    if (!$crow){
	  return '1';
    }
    if ($crow[0]->parent_id == 0){
	  return $crow[0]->published;
    }
    $therights = $crow[0]->published;

    while ( $crow[0]->parent_id > 0 ){
      $sql = "SELECT cat_id, parent_id, published FROM #__jdownloads_cats WHERE cat_id = ".$crow[0]->parent_id;
      $database->setQuery($sql);
      $crow = $database->loadObjectList();
      if ($crow[0]->published < $therights){
      	$therights = $crow[0]->published;
	  }
    }
    return $therights;
}

function inh_published_file($filepublish, $pcatid){
   $catpublish = inh_published($pcatid);
   if ($catpublish < $filepublish){
	return $catpublish;
   }
   else{
	return $filepublish;
   }
}

function get_catids($p_subcat){
  global $jDFPcatids, $jDFPshowunpublished, $jDFPconsiderrights;
  $database =  JFactory::getDBO();
  $access = jd_checkAccess();
  $sql = "SELECT * FROM #__jdownloads_cats WHERE parent_id = ".$p_subcat." {pppp}{rrrr}ORDER BY ordering";
  if ($jDFPshowunpublished == 0){
    $sql = str_replace('{pppp}','AND published = 1 ',$sql);
  }
  else{
    $sql = str_replace('{pppp}','',$sql);
  }
  if ($jDFPconsiderrights == 0){
    $sql = str_replace('{rrrr}','',$sql);
  }
  else{
    $sql = str_replace('{rrrr}','AND cat_access <= '.$access.' ',$sql);
  }
  $database->setQuery($sql);
  $frows = $database->loadObjectList();
  if (!$frows){
  	return false;
  }
  foreach ($frows as $therow){
    $jDFPcatids .= $therow->cat_id.',';
  	get_catids($therow->cat_id);
  }
  return '';
}

function jd_file_latest_updated($matches){
   global $jDFPshowunpublished, $jDFPrank, $jDFPcatids, $jlistConfigM;
   $database =  JFactory::getDBO();

   $jDFPcatids = '';
   $bidon = get_catids(0);

   $days = $jlistConfigM['days.is.file.updated'];
   if (!$days) $days = 15;

   $until_day = mktime(0,0,0,date("m"), date("d")-$days, date("Y"));
   $until = date('Y-m-d H:m:s', $until_day);

   $filesql ="SELECT file_id FROM #__jdownloads_files WHERE {xxxx}cat_id IN (".substr($jDFPcatids,0,-1).") AND (update_active = 1) AND (modified_date >= '.$until.') ORDER BY {dado} DESC LIMIT ".$database->escape($matches[2]).";";
   if ($matches[1] == 'updated'){
     $filesql = str_replace("{dado}",'modified_date',$filesql);
   }
   else{
     $filesql = str_replace("{dado}",'downloads',$filesql);
   }

   if ($jDFPshowunpublished == 1){
      $filesql = str_replace("{xxxx}",'',$filesql);
   }
   else{
      $filesql = str_replace("{xxxx}",'published = 1 AND ',$filesql);
   }

   $database->setQuery($filesql);
   $files = $database->loadObjectList();
   $filetable = '';
   $jDFPrank = 1;
   if ($files){
       foreach ($files as $thefile){
           $sim_matches = array("", "file", $thefile->file_id);
        $filetable .= jd_file_createdownload($sim_matches);
        $jDFPrank++;
       }
   }
   return $filetable;
}

function jd_file_latest_hottest($matches){
   global $jDFPshowunpublished, $jDFPrank, $jDFPcatids;
   $database =  JFactory::getDBO();

   $jDFPcatids = '';
   $bidon = get_catids(0);

   $filesql = "SELECT file_id FROM #__jdownloads_files WHERE {xxxx}cat_id IN (".substr($jDFPcatids,0,-1).") ORDER BY {dado} DESC LIMIT ".$database->escape($matches[2]).";";
   if ($matches[1] == 'latest'){
     $filesql = str_replace("{dado}",'date_added',$filesql);
   }
   else{
     $filesql = str_replace("{dado}",'downloads',$filesql);
   }

   if ($jDFPshowunpublished == 1){
      $filesql = str_replace("{xxxx}",'',$filesql);
   }
   else{
      $filesql = str_replace("{xxxx}",'published = 1 AND ',$filesql);
   }

   $database->setQuery($filesql);
   $files = $database->loadObjectList();
   $filetable = '';
   $jDFPrank = 1;
   if ($files){
   	foreach ($files as $thefile){
   		$sim_matches = array("", "file", $thefile->file_id);
        $filetable .= jd_file_createdownload($sim_matches);
        $jDFPrank++;
   	}
   }
   return $filetable;
}

function jd_file_createdownload($matches){
  global $jlistConfigM, $jDFPitemid, $jDFPOnlineLayout, $jDFPshowunpublished, $jDFPsfolders;
  $database = JFactory::getDBO();
  $user = JFactory::getUser();
  
  // Chercher Layout dans la bd
  $jdLayout = $jDFPOnlineLayout;
  $tSQLquery = "SELECT * FROM #__jdownloads_templates WHERE (template_name = '".$jdLayout."') AND (template_typ = 2)";
  $database->setQuery($tSQLquery);
  $trows = $database->loadObjectList();

  // Tester si le layout existe, sinon message d'erreur.
  if (!$trows) {
    $ReturnValue = str_replace("{thelayout}",$jdLayout,JText::_('COM_JDOWNLOADS_FRONTEND_SETTINGS_FILEPLUGIN_LAYOUTUNKNOWN')).'<br />';
     return $ReturnValue;
  }

  $jd_template              = $trows[0]->template_text;
  $jd_template_header       = $trows[0]->template_header_text;
  $jd_template_subheader    = $trows[0]->template_subheader_text;
  $jd_template_footer       = $trows[0]->template_footer_text;
  $jd_template_symbol_off   = $trows[0]->symbol_off;

  //Chercher LE fichier selon ID dans Mambot
  $fSQLquery = "SELECT * FROM #__jdownloads_files WHERE file_id = ".$database->escape($matches[2]);
  $database->setQuery($fSQLquery);
  $frows = $database->loadObjectList();
  if (!$frows){
    $fSQLquery = "SELECT * FROM #__jdownloads_files WHERE file_title = '".$database->escape($matches[2])."'";
    $database->setQuery($fSQLquery);
    $frows = $database->loadObjectList();
  }

  if (!$frows) {
    $jd_filepic = JURI::base().'/plugins/content/jdownloads/jdownloads/images/offline.gif';
    $jd_filetitle = str_replace("{fileid}",$matches[2],JText::_('COM_JDOWNLOADS_FRONTEND_SETTINGS_FILEPLUGIN_FILEUNKNOWN'));
    $jd_template = jd_file_fill_nodownload($jd_template,$jd_filetitle,'',$jd_filepic);
    if ($jlistConfigM['fileplugin.enable_plugin'] == 0) {
    	if ($jlistConfigM['fileplugin.show_jdfiledisabled'] == 0){
          $jd_template = '';
    	}
      }
    return $jd_template;
  }
  // Quitter si le fichier n'est pas publi? et que l'affichage des fichier non-publi?s est d?sactiv?
  if ($jDFPshowunpublished == 0){
     $l_fpublished = inh_published_file($frows[0]->published, $frows[0]->cat_id);
     if ($l_fpublished == 0){
       return "";
     }
  }

  // Chercher la cat?gorie pour voir si visiteur a des droits sur le fichier
  $l_frights = inh_rights($frows[0]->cat_id);

  // Calculer si le visiteur peut voir le fichier
  $user_download_access = jd_checkAccess();
  
    if ($user->id > 0){
        $user_is_in_groups = getUserGroupsPlg();
    } else {
        $user_is_in_groups = 0;
    }    
    $can_view = false;
    $user_groups = '';                                                  
    if ($user_is_in_groups) $user_groups = split(',',$user_is_in_groups);  
    
    $database->setQuery("SELECT cat_group_access FROM #__jdownloads_cats WHERE cat_id = '".$frows[0]->cat_id."'");
    $cat_group_access = $database->loadResult();
    if ($user_is_in_groups){
        if (in_array($cat_group_access, $user_groups)) $can_view = true;
    }    
  
  if ($user_download_access < $l_frights && !$can_view){
  	return "";
  }

  // Calculer si le visiteur peut t?l?charger
  $user_download_access = (int)substr($user_download_access,0,1);
  $file_download_access = (int)substr($l_frights,1,1);
  $jd_dallowed = 0;
  if ($file_download_access <= $user_download_access || $can_view){
	$jd_dallowed = 1;
  }
  
  

  // Plugin enabled or disabled
  if ($jlistConfigM['fileplugin.enable_plugin'] == 0){
    if ($jlistConfigM['fileplugin.show_jdfiledisabled'] == 0){
      $jd_template = '';
    } else {
        $jd_filetitle = $jlistConfigM['fileplugin.offline_title'];
        $jd_filepic = JURI::base().'/plugins/content/jdownloads/jdownloads/images/offline.gif';
        $jd_filedescription = $jlistConfigM['fileplugin.offline_descr'];
        if ($jlistConfigM['fileplugin.show_downloadtitle'] == 1){
          $jd_filetitle = $frows[0]->file_title;
          $jd_filepic = $jDFPsfolders['file'].$frows[0]->file_pic;
          $jd_filedescription = $jlistConfigM['fileplugin.offline_title'].'&nbsp;'.$jlistConfigM['fileplugin.offline_descr'];
        }
        $jd_template = jd_file_fill_nodownload($jd_template,$jd_filetitle,$jd_filedescription,$jd_filepic);
    }
    return $jd_template;
  }

  $jd_template = jd_file_fill_downloadok($jd_template, $frows[0], $jd_template_symbol_off, $matches[1], $jd_dallowed);

  return $jd_template;
}

function jd_file_fill_nodownload($p_Template, $p_Title, $p_Description, $p_Filepic){
  global $jlistConfigM, $jDFPitemid, $jDFPplugin_live_site, $jDFPlive_site;

  $l_Template = str_replace("{{{","[[[",$p_Template);
  $jd_file_pic = '<img src="'.$jDFPlive_site.$p_Filepic.'" align="absmiddle" border="0" height="'.$jlistConfigM['file.pic.size'].'" width="'.$jlistConfigM['file.pic.size'].'" alt="" />';
  $l_Template = str_replace("{file_pic}",$jd_file_pic,$l_Template);
  $jd_file_pic = '<img src="'.$jDFPplugin_live_site.'content/jdownloads/jdownloads/images/nodownload.gif">';
  $l_Template = str_replace("{checkbox_list}",$jd_file_pic,$l_Template);
  $l_Template = str_replace("{file_title}",$p_Title,$l_Template);
  $l_Template = str_replace("{file_title_only}",$p_Title,$l_Template);
  $l_Template = str_replace("{description}",$p_Description,$l_Template);
  $l_Template = str_replace("{release}",'',$l_Template);
  $l_Template = str_replace("{size}",'',$l_Template);
  $l_Template = str_replace("{downloads}",'',$l_Template);
  $l_Template = str_replace("{pic_is_new}",'',$l_Template);
  $l_Template = str_replace("{pic_is_hot}",'',$l_Template);
  $l_Template = str_replace("{license}",'',$l_Template);
  $l_Template = str_replace("{date_added}",'',$l_Template);
  $l_Template = str_replace("{language}",'',$l_Template);
  $l_Template = str_replace("{system}",'',$l_Template);
  $l_Template = str_replace("{url_download}",'',$l_Template);
  $l_Template = str_replace("{file_id}",'',$l_Template);
  $l_Template = str_replace("{ordering}",'',$l_Template);
  $l_Template = str_replace("{published}",'',$l_Template);
  $l_Template = str_replace("{cat_id}",'',$l_Template);
  $l_Template = str_replace("{mirror_1}",'',$l_Template);
  $l_Template = str_replace("{mirror_2}",'',$l_Template);
  $l_Template = str_replace("{link_to_details}",'',$l_Template);
  $l_Template = str_replace("{thumbnail}",'',$l_Template);
  $l_Template = str_replace("{screenshot}",'',$l_Template);
  $l_Template = str_replace("{pic_is_updated}",'',$l_Template);
  $l_Template = str_replace("{rank}",'',$l_Template);

  $l_Template = str_replace("{hits_title}",'',$l_Template);
  $l_Template = str_replace("{hits_value}",'',$l_Template);
  
  if (strpos($l_Template, "{screenshot_end}") > 0) {
    $pos_end = strpos($l_Template, '{screenshot_end}');
    $pos_beg = strpos($l_Template, '{screenshot_begin}');
    $l_Template = substr_replace($l_Template, '', $pos_beg, ($pos_end - $pos_beg) + 16);
  }
  if (strpos($l_Template, "{screenshot_end2}") > 0) {
    $pos_end = strpos($l_Template, '{screenshot_end2}');
    $pos_beg = strpos($l_Template, '{screenshot_begin2}');
    $l_Template = substr_replace($l_Template, '', $pos_beg, ($pos_end - $pos_beg) + 17);
  }
    if (strpos($l_Template, "{screenshot_end3}") > 0) {
    $pos_end = strpos($l_Template, '{screenshot_end3}');
    $pos_beg = strpos($l_Template, '{screenshot_begin3}');
    $l_Template = substr_replace($l_Template, '', $pos_beg, ($pos_end - $pos_beg) + 17);
  }
  $l_Template = str_replace("{thumbnail_lightbox}",'',$l_Template);
  $l_Template = str_replace("{thumbnail_gallery}",'',$l_Template);
  $l_Template = str_replace("{created_by_title}",'',$l_Template);
  $l_Template = str_replace("{created_by_value}",'',$l_Template);
  $l_Template = str_replace("{created_date_title}",'',$l_Template);
  $l_Template = str_replace("{created_date_value}",'',$l_Template);
  $l_Template = str_replace("{modified_by_title}",'',$l_Template);
  $l_Template = str_replace("{modified_by_value}",'',$l_Template);
  $l_Template = str_replace("{modified_date_title}",'',$l_Template);
  $l_Template = str_replace("{modified_date_value}",'',$l_Template);
  $l_Template = str_replace("{price_title}",'',$l_Template);
  $l_Template = str_replace("{price_value}",'',$l_Template);
  $l_Template = str_replace("{system_title}",'',$l_Template);
  $l_Template = str_replace("{system_text}",'',$l_Template);
  $l_Template = str_replace("{license_title}",'',$l_Template);
  $l_Template = str_replace("{license_text}",'',$l_Template);
  $l_Template = str_replace("{language_title}",'',$l_Template);
  $l_Template = str_replace("{language_text}",'',$l_Template);
  $l_Template = str_replace("{filesize_title}",'',$l_Template);
  $l_Template = str_replace("{filesize_value}",'',$l_Template);
  $l_Template = str_replace("{author}",'',$l_Template);
  $l_Template = str_replace("{url_author}",'',$l_Template);
  $l_Template = str_replace("{author_title}",'',$l_Template);
  $l_Template = str_replace("{author_text}",'',$l_Template);
  $l_Template = str_replace("{url_home}",'',$l_Template);
  $l_Template = str_replace("{author_url_title}",'',$l_Template);
  $l_Template = str_replace("{author_url_text}",'',$l_Template);
  $l_Template = str_replace("{files_title_begin}",'',$l_Template);
  $l_Template = str_replace("{files_title_text}",'',$l_Template);
  $l_Template = str_replace("{files_title_end}",'',$l_Template);
  $l_Template = str_replace("{mp3_player}",'',$l_Template);
  $l_Template = str_replace("{mp3_id3_tag}",'',$l_Template);
  $l_Template = str_replace("{google_adsense}",'',$l_Template);
  $l_Template = str_replace("{report_link}",'',$l_Template);
  $l_Template = str_replace("{sum_jcomments}",'',$l_Template);
  $l_Template = str_replace("{rating}",'',$l_Template);
  $l_Template = str_replace("{file_date}", '', $l_Template); 
  $l_Template = str_replace("{file_date_title}", '', $l_Template);
  
       // delete the tabs placeholder 
       $l_Template = str_replace('{tabs begin}', '', $l_Template);
       $l_Template = str_replace('{tab description}', '', $l_Template);
       $l_Template = str_replace('{tab description end}', '', $l_Template);
       $l_Template = str_replace('{tab pics}', '', $l_Template);
       $l_Template = str_replace('{tab pics end}', '', $l_Template);
       $l_Template = str_replace('{tab mp3}', '', $l_Template);
       $l_Template = str_replace('{tab mp3 end}', '', $l_Template);
       $l_Template = str_replace('{tab data}', '', $l_Template);
       $l_Template = str_replace('{tab data end}', '', $l_Template);
       $l_Template = str_replace('{tab download}', '', $l_Template);
       $l_Template = str_replace('{tab download end}', '', $l_Template);
       $l_Template = str_replace('{tab custom1}', '', $l_Template);
       $l_Template = str_replace('{tab custom1 end}', '', $l_Template);      
       $l_Template = str_replace('{tab custom2}', '', $l_Template);
       $l_Template = str_replace('{tab custom2 end}', '', $l_Template);
       $l_Template = str_replace('{tab custom3}', '', $l_Template);
       $l_Template = str_replace('{tab custom3 end}', '', $l_Template);
       $l_Template = str_replace('{tabs end}', '', $l_Template);
       
    // remove custom fields
    for ($x=1; $x<15; $x++){
         $l_Template = str_replace("{custom_title_$x}", '', $l_Template);
         $l_Template = str_replace("{custom_value_$x}", '', $l_Template);
    }             
  
  return str_replace("[[[","{",$l_Template);
}

function jd_file_fill_downloadok($p_Template, $p_Frow, $p_Symbol_Off, $p_DownloadType, $p_dallowed){
  global $jlistConfigM, $jDFPitemid, $jDFPsfolders, $jDFPrank, $jDFPv14, $jDFPlive_site, $jDFPplugin_live_site, $jDFPabsolute_path, $cat_link_itemidsPlg, $jDLayoutTitleExists;

  $database =  JFactory::getDBO();
  $jdlink_author_text   = '';
  $createdbyname        = '';
  $modifiedbyname       = '';
  
  $l_Template = str_replace("{{{","[[[",$p_Template);

  $jdpic_license = '';
  $jdpic_date = '';
  $jdpic_author = '';
  $jdpic_website = '';
  $jdpic_system = '';
  $jdpic_language = '';
  $jdpic_download = '';
  $jdpic_hits = ''; 
  $jdpic_size = '';
  $jdpic_price = '';
  $cat_itemid = 0;
  
  if ($p_Symbol_Off == 0){
    $msize = $jlistConfigM['info.icons.size'];
    $jdpic_license = '<img src="'.JURI::base().$jDFPsfolders['mini'].'license.png" style="vertical-align:middle;" width="'.$msize.'" height="'.$msize.'" border="0"  alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_LICENCE').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_LICENCE').'" />&nbsp;';
    $jdpic_date = '<img src="'.JURI::base().$jDFPsfolders['mini'].'date.png" style="vertical-align:middle;" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_DATE').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_DATE').'" />&nbsp;';
    $jdpic_author = '<img src="'.JURI::base().$jDFPsfolders['mini'].'contact.png" style="vertical-align:middle;" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_AUTHOR').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_AUTHOR').'" />&nbsp;';
    $jdpic_website = '<img src="'.JURI::base().$jDFPsfolders['mini'].'weblink.png" style="vertical-align:middle;" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_WEBSITE').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_WEBSITE').'" />&nbsp;';
    $jdpic_system = '<img src="'.JURI::base().$jDFPsfolders['mini'].'system.png" style="vertical-align:middle;" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_SYSTEM').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_SYSTEM').'" />&nbsp;';
    $jdpic_language = '<img src="'.JURI::base().$jDFPsfolders['mini'].'language.png" style="vertical-align:middle;" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_LANGUAGE').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_LANGUAGE').'" />&nbsp;';
    $jdpic_download = '<img src="'.JURI::base().$jDFPsfolders['mini'].'download.png" style="vertical-align:middle;" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_DOWNLOAD').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_DOWNLOAD').'" />&nbsp;';
    $jdpic_hits = '<img src="'.JURI::base().$jDFPsfolders['mini'].'download.png" style="vertical-align:middle;" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_DOWNLOAD').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_DOWNLOAD_HITS').'" />&nbsp;';
    $jdpic_size = '<img src="'.JURI::base().$jDFPsfolders['mini'].'stuff.png" style="vertical-align:middle;" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_FILESIZE').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_FILESIZE').'" />&nbsp;';
    $jdpic_price = '<img src="'.JURI::base().$jDFPsfolders['mini'].'currency.png" style="vertical-align:middle;" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_PRICE').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_PRICE').'" />&nbsp;';
  }
  $jdextern_url_pic = '<img src="'.$jDFPplugin_live_site.'content/jdownloads/jdownloads/images/link_extern.gif" style="vertical-align:middle;" alt="" title="" />';

  $jd_file_pic = '<img src="'.JURI::base().$jDFPsfolders['file'].$p_Frow->file_pic.'" style="vertical-align:middle;" border="0" height="'.$jlistConfigM['file.pic.size'].'" width="'.$jlistConfigM['file.pic.size'].'" alt="" title="" />';
  $jd_cat_id = $p_Frow->cat_id;
  $jd_filename = $p_Frow->url_download;
  $jd_language = $p_Frow->language;
  $jd_system = $p_Frow->system;

  //Chercher cat?gorie du fichier pour calculer le r?pertoire
  $cSQLquery = "SELECT * FROM #__jdownloads_cats WHERE cat_id = ".$jd_cat_id;
  $database->setQuery($cSQLquery);
  $crows = $database->loadObjectList();

  // get license data and build link
  $jd_license = $p_Frow->license;
  if ($jd_license){
    $database->setQuery('SELECT * FROM #__jdownloads_license WHERE id = '.$jd_license);
    $jdlic = $database->loadObjectList();
  }
  $jd_filelicense = '';
  if (isset($jdlic[0])){
    if (!$jdlic[0]->license_url == '') {
      $jd_filelicense = $jdpic_license.'<a href="'.$jdlic[0]->license_url.'" target="_blank" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_LICENCE').'">'.$jdlic[0]->license_title.'</a> '.$jdextern_url_pic;
    } else {
      if (!$jdlic[0]->license_title == '') {
        $jd_filelicense = $jdpic_license.$jdlic[0]->license_title;
      } else {
        $jd_filelicense = '';
      }
    }
  }
  //Chercher la langue dans la config de jDownloads
  $file_lang_values = explode(',' , $jlistConfigM['language.list']);
  if ($jd_language == 0 ) {
    $jd_showlanguage = '';
  } else {
    $jd_showlanguage = $jdpic_language.$file_lang_values[$jd_language];
  }

  //Chercher le System dans la config de jDownloads
  $file_sys_values = explode(',' , $jlistConfigM['system.list']);
  if ($jd_system == 0 ) {
     $jd_showsystem = '';
  } else {
     $jd_showsystem = $jdpic_system.$file_sys_values[$jd_system];
  }
  
  // build hits values
  $jd_showhits = $jdpic_hits.$p_Frow->downloads;

  //Calculer author URL
  if (!$p_Frow->url_home == '') {
     if (strpos($p_Frow->url_home, 'http://') === false){
         $jd_urlhome = $jdpic_website.'<a href="http://'.$p_Frow->url_home.'" target="_blank" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE').'">'.JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE').'</a> '.$jdextern_url_pic;
     } else {
         $jd_urlhome = $jdpic_website.'<a href="'.$p_Frow->url_home.'" target="_blank" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE').'">'.JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE').'</a> '.$jdextern_url_pic;
     }
  } else {
       $jd_urlhome = '';
  }

  // build author link
  $jd_mail_encode = '';
  if (!$p_Frow->author){
    $jd_author_name = '';
  } else {
    $jd_author_name = $p_Frow->author;
  }
  if ($p_Frow->url_author <> '') { // url_author is set
    if (strpos($p_Frow->url_author, '@')){ // url_author is email
      if (!$jd_author_name) $jd_author_name = JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_AUTHOR');
      if ($jlistConfigM['mail.cloaking']){
        $jd_mail_encode = $jdpic_author.jd_emailcloak($p_Frow->url_author, $jd_author_name);
      } else {
        $jd_mail_encode = $jdpic_author.'<a href="mailto:'.$p_Frow->url_author.'">'.$jd_author_name.'</a>';
      }

    } else { // url_author is website
      if ($jd_author_name == ''){
        $jd_author_name = JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE');
      }
      if (strpos($p_Frow->url_author, 'http://') !== false) { // link has http
        $jd_mail_encode = $jdpic_website.'<a href="'.$p_Frow->url_author.'" target="_blank">'.$jd_author_name.'</a>';
      } else { // link has no http.
        $jd_mail_encode = $jdpic_website.'<a href="http://'.$p_Frow->url_author.'" target="_blank">'.$jd_author_name.'</a>';
      }
    }
  }
  if ($jd_mail_encode != '') {
       $jdlink_author_text = $jd_mail_encode.' '.$jdextern_url_pic;
  } else {
    if ($jd_author_name != ''){
       $jdlink_author_text = $jdpic_author.$jd_author_name;
    }   
  }
   
  //Calculer URL-Download
  // exists a single category menu link for it? 
  if ($cat_link_itemidsPlg){  
      $cat_itemid = '';
      for ($i2=0; $i2 < count($cat_link_itemidsPlg); $i2++) {
           if ($cat_link_itemidsPlg[$i2][catid] == $jd_cat_id){
               $cat_itemid = $cat_link_itemidsPlg[$i2][id];
           }     
      }
  } 

  if (!$cat_itemid){
      // use global itemid when no single link exists
      $cat_itemid = $jDFPitemid;
  }
 
  if ($jlistConfigM['direct.download']){
      $jd_url_download_file = jd_SefRel('index.php?option=com_jdownloads&amp;Itemid='.$cat_itemid.'&amp;view=finish&cid='.$p_Frow->file_id.'&catid='.$jd_cat_id);    
  } else {    
      $jd_url_download_file = jd_SefRel('index.php?option=com_jdownloads&amp;Itemid='.$cat_itemid.'&amp;view=summary&cid='.$p_Frow->file_id.'&catid='.$jd_cat_id);    
  }    
  if (trim($p_Frow->extern_file) != ''){
    $jd_url_download_durl = $p_Frow->extern_file;
  } else {
  $jd_url_download_durl = $jDFPlive_site.$jlistConfigM['files.uploaddir']."/".$crows[0]->cat_dir."/".$jd_filename;
  }

  if ($p_DownloadType == 'durl') {
    $jd_url_download_link = $jd_url_download_durl;
  } else {
    $jd_url_download_link = $jd_url_download_file;
  }

  $blank_window = '';
  $view_types = array();
  $view_types = explode(',', $jlistConfigM['file.types.view']);
  $file_extension = strtolower(substr(strrchr($jd_filename,"."),1));
  if (in_array($file_extension, $view_types)){
        $blank_window = 'target="_blank"';
  }    
  // check is set link to a new window?
  /*if ($file->extern_file && $file->extern_site   ){
      $blank_window = 'target="_blank"';
  } */ 
  
  if ($p_dallowed == 1){
      $jd_url_download = $jdpic_download.'<a '.$blank_window.' href="'.$jd_url_download_link.'">'.JText::_('COM_JDOWNLOADS_LINKTEXT_DOWNLOAD_URL').'</a>';
  }
  else{
    $jd_url_download = $jdpic_download.JText::_('COM_JDOWNLOADS_LINKTEXT_DOWNLOAD_URL');
  }

  if ($jlistConfigM['download.pic.plugin'] != ''){
      $jd_downloadpict = '<img src="'.JURI::base().$jDFPsfolders['download'].$jlistConfigM['download.pic.plugin'].'" border="0" alt="" />';   
  } else {    
      $jd_downloadpict = "<img src=\"".$jDFPplugin_live_site."content/jdownloads/jdownloads/images/download.gif\" border = \"0\" alt=\"\" />";
  }
    
  if ($p_dallowed == 1){
    $jd_download = '<a '.$blank_window.' href="'.$jd_url_download_link.'">'.$jd_downloadpict.'</a>';
  }
  else {
	$jd_download = $jd_downloadpict;
  }

  //Calculer link to details
  if ($jlistConfigM['view.detailsite']){
      $jd_titel_link = jd_SefRel('index.php?option=com_jdownloads&amp;Itemid='.$cat_itemid.'&amp;view=viewdownload&catid='.$p_Frow->cat_id.'&cid='.$p_Frow->file_id);
      $jd_detail_link_text = '<a href="'.$jd_titel_link.'">'.JText::_('COM_JDOWNLOADS_FE_DETAILS_LINK_TEXT_TO_DETAILS').'</a>';
      $jd_title_link = '<a href="'.$jd_titel_link.'">'.$p_Frow->file_title.'</a>';
  } elseif ($jlistConfigM['use.download.title.as.download.link']){
      $jd_titel_link = jd_SefRel('index.php?option=com_jdownloads&amp;Itemid='.$cat_itemid.'&amp;view=finish&catid='.$p_Frow->cat_id.'&cid='.$p_Frow->file_id);
      $jd_detail_link_text = '<a href="'.$jd_titel_link.'">'.JText::_('COM_JDOWNLOADS_FE_DETAILS_LINK_TEXT_TO_DETAILS').'</a>';
      $jd_title_link = '<a href="'.$jd_titel_link.'">'.$p_Frow->file_title.'</a>';
  } else {
      $jd_title_link = $p_Frow->file_title;
       $jd_detail_link_text = '';
  }    

  //Calculer mirror1
  $jd_mirror1_link = '';
  if ($p_Frow->mirror_1) {
    if ($p_DownloadType == 'durl') {
      $jd_mirror1_link = '<a href="'.$p_Frow->mirror_1.'">'.JText::_('COM_JDOWNLOADS_FRONTEND_MIRROR_URL_TITLE_1').'</a>';
    } else {
      $jd_mirror1 = $jd_url_download_file.'&m=1';
      $jd_mirror1_link = '<a href="'.$jd_mirror1.'">'.JText::_('COM_JDOWNLOADS_FRONTEND_MIRROR_URL_TITLE_1').'</a>';
    }
    if ($p_dallowed == 0){
    	$jd_mirror1_link = JText::_('COM_JDOWNLOADS_FRONTEND_MIRROR_URL_TITLE_1');
    }
  }

  //Calculer mirror2
  $jd_mirror2_link = '';
  if ($p_Frow->mirror_2) {
    if ($p_DownloadType == 'durl') {
      $jd_mirror2_link = '<a href="'.$p_Frow->mirror_2.'">'.JText::_('COM_JDOWNLOADS_FRONTEND_MIRROR_URL_TITLE_2').'</a>';
    } else {
      $jd_mirror2 = $jd_url_download_file.'&m=2';
      $jd_mirror2_link = '<a href="'.$jd_mirror2.'">'.JText::_('COM_JDOWNLOADS_FRONTEND_MIRROR_URL_TITLE_2').'</a>';
    }
    if ($p_dallowed == 0){
    	$jd_mirror2_link = JText::_('COM_JDOWNLOADS_FRONTEND_MIRROR_URL_TITLE_2');
    }
  }

  //Calculer symbole HOT
  if ($jlistConfigM['loads.is.file.hot'] > 0 && $p_Frow->downloads >= $jlistConfigM['loads.is.file.hot']){
     $jd_hotpic = '<img src="'.JURI::base().$jDFPsfolders['hot'].$jlistConfigM['picname.is.file.hot'].'" alt="" />';
  } else {
     $jd_hotpic = "";
  }

  //Calculer symbole NEW
  $jours_diff = DatumsDifferenz_JDm(date('Y-m-d H:m:s'), $p_Frow->date_added);
  if ($jlistConfigM['days.is.file.new'] > 0 && $jours_diff <= $jlistConfigM['days.is.file.new']){
     $jd_newpic = '<img src="'.JURI::base().$jDFPsfolders['new'].$jlistConfigM['picname.is.file.new'].'" alt="" />';

  } else {
     $jd_newpic = "";
  }

  //Calculer la date de modification du fichier
  if ($p_Frow->modified_date != '0000-00-00 00:00:00') {
     $jdmodified_data = $jdpic_date.substr(jd_DateForm($p_Frow->modified_date, $jlistConfigM['global.datetime'], $offset = NULL),0,10);
  } else {
     $jdmodified_data = '';
  }

  //Calculer la date du fichier
   $jd_showdate = $jdpic_date.substr(jd_DateForm($p_Frow->date_added),0,10);

  //Calculer la taille du fichier
  $jd_showsize = '';
  if (trim($p_Frow->size) != '') {
    $jd_showsize = $jdpic_size.$p_Frow->size;
  }

  //Calculer le prix
  $jd_showprice = '';
  if (trim($p_Frow->price) != '') {
    $jd_showprice = $jdpic_price.$p_Frow->price;
  }

  //Calculer la release
  $jd_showrelease = '';
  if (trim($p_Frow->release) != '') {
    $jd_showrelease = JText::_('COM_JDOWNLOADS_FRONTEND_VERSION_TITLE').$p_Frow->release;
  }

  //Calculer la release
  $jd_showfile_date = '';
  $jd_showfile_date_title = '';
  if ($p_Frow->file_date != '') {
      $jd_showfile_date = $p_Frow->file_date;
      $jd_showfile_date_title = JText::_('COM_JDOWNLOADS_EDIT_FILE_FILE_DATE_TITLE');
  }
  
  //Formater output
  $l_Template = str_replace("{file_pic}",$jd_file_pic,$l_Template);
  $l_Template = str_replace("{file_title_only}",$p_Frow->file_title,$l_Template);
  $l_Template = str_replace("{file_title}",$jd_title_link,$l_Template);
  
  // cut description text?
  $first_reg_msg = '<div class="jdpf_not_logged_in">'.JText::_('COM_JDOWNLOADS_FRONTEND_CAT_ACCESS_REGGED').'</div>';  
  if ($jlistConfigM['plugin.auto.file.short.description'] && $jlistConfigM['plugin.auto.file.short.description.value'] > 0){
      if (strlen($p_Frow->description) > $jlistConfigM['plugin.auto.file.short.description.value']){ 
          $shorted_text=preg_replace("/[^ ]*$/", '..', substr($p_Frow->description, 0, $jlistConfigM['plugin.auto.file.short.description.value']));
          if (!$p_dallowed){
              $l_Template = str_replace('{description}', $shorted_text.$first_reg_msg, $l_Template);
          } else {
              $l_Template = str_replace('{description}', $shorted_text, $l_Template);
          }    
      } else {
          if (!$p_dallowed){
               $l_Template = str_replace('{description}', $p_Frow->description.$first_reg_msg, $l_Template);
          } else {     
               $l_Template = str_replace('{description}', $p_Frow->description, $l_Template);
          }     
      }    
  } else {
      if (!$p_dallowed){
           $l_Template = str_replace("{description}",$p_Frow->description.$first_reg_msg, $l_Template);
      } else {
           $l_Template = str_replace("{description}",$p_Frow->description,$l_Template);     
      }     
  } 
  
  
  $l_Template = str_replace("{release}",$jd_showrelease,$l_Template);
  $l_Template = str_replace("{size}",$jd_showsize,$l_Template);
  $l_Template = str_replace("{filesize_value}",$jd_showsize,$l_Template);
  $l_Template = str_replace("{downloads}",$p_Frow->downloads,$l_Template);
  //$l_Template = str_replace("{hits_title}",$jdpic_hits,$l_Template);
  $l_Template = str_replace("{hits_value}",$jd_showhits,$l_Template);
  $l_Template = str_replace("{pic_is_new}",$jd_newpic,$l_Template);
  $l_Template = str_replace("{pic_is_hot}",$jd_hotpic,$l_Template);
  $l_Template = str_replace("{license}",$jd_filelicense,$l_Template);
  $l_Template = str_replace("{license_text}",$jd_filelicense,$l_Template);
  $l_Template = str_replace("{author}",$jdlink_author_text,$l_Template);
  $l_Template = str_replace("{author_text}",$jdlink_author_text,$l_Template);
  $l_Template = str_replace("{url_author}",'',$l_Template);
  $l_Template = str_replace("{url_home}",$jd_urlhome,$l_Template);
  $l_Template = str_replace("{author_url_text}",$jd_urlhome,$l_Template);
  $l_Template = str_replace("{date_added}",$jd_showdate,$l_Template);
  $l_Template = str_replace("{created_date_value}",$jd_showdate,$l_Template);
  $l_Template = str_replace("{language}",$jd_showlanguage,$l_Template);
  $l_Template = str_replace("{language_text}",$jd_showlanguage,$l_Template);
  $l_Template = str_replace("{system}",$jd_showsystem,$l_Template);
  $l_Template = str_replace("{system_text}",$jd_showsystem,$l_Template);
  $l_Template = str_replace("{checkbox_list}",$jd_download,$l_Template);
  $l_Template = str_replace("{url_download}",$jd_download,$l_Template);
  $l_Template = str_replace("{file_id}",$p_Frow->file_id,$l_Template);
  $l_Template = str_replace("{ordering}",$p_Frow->ordering,$l_Template);
  $l_Template = str_replace("{published}",$p_Frow->published,$l_Template);
  $l_Template = str_replace("{cat_id}",$p_Frow->cat_id,$l_Template);
  $l_Template = str_replace("{price_value}",$jd_showprice,$l_Template);
  $l_Template = str_replace("{link_to_details}",$jd_detail_link_text,$l_Template);
  $l_Template = str_replace("{mirror_1}",$jd_mirror1_link,$l_Template);
  $l_Template = str_replace("{mirror_2}",$jd_mirror2_link,$l_Template);
  $l_Template = str_replace("{rank}",$jDFPrank,$l_Template);
  $l_Template = str_replace("{mp3_player}",'',$l_Template); 
  $l_Template = str_replace("{mp3_id3_tag}",'',$l_Template);
  $l_Template = str_replace("{google_adsense}",'',$l_Template);
  $l_Template = str_replace("{report_link}",'',$l_Template);
  $l_Template = str_replace("{sum_jcomments}",'',$l_Template);
  $l_Template = str_replace("{rating}",'',$l_Template);
    // new for V 1.6.1 
  $l_Template = str_replace("{file_date}", $jd_showfile_date, $l_Template); 
  $l_Template = str_replace("{file_date_title}", $jd_showfile_date_title, $l_Template); 
  // new for jD 1.8.x
  // use tabs?
  
  // tabs or sliders when the placeholders are used
    if ((int)$jlistConfigM['use.tabs.type'] > 0){
        jimport ('joomla.html.html.bootstrap');
       if ((int)$jlistConfigM['use.tabs.type'] == 1){
            // use slides
           $l_Template = str_replace('{tabs begin}', JHtml::_('bootstrap.startAccordion', 'jdpane', 'panel1'), $l_Template);
           $l_Template = str_replace('{tab description}', JHtml::_('bootstrap.addSlide', 'jdpane', JText::_('COM_JDOWNLOADS_FE_TAB_DESCRIPTION_TITLE'), 'panel1'), $l_Template); 
           $l_Template = str_replace('{tab description end}', JHtml::_('bootstrap.endSlide'), $l_Template);
           $l_Template = str_replace('{tab pics}', JHtml::_('bootstrap.addSlide', 'jdpane', JText::_('COM_JDOWNLOADS_FE_TAB_PICS_TITLE'), 'panel2'), $l_Template); 
           $l_Template = str_replace('{tab pics end}', JHtml::_('bootstrap.endSlide'), $l_Template);
           $l_Template = str_replace('{tab mp3}', JHtml::_('bootstrap.addSlide', 'jdpane', JText::_('COM_JDOWNLOADS_FE_TAB_AUDIO_TITLE'), 'panel3'), $l_Template);
           $l_Template = str_replace('{tab mp3 end}', JHtml::_('bootstrap.endSlide'), $l_Template);
           $l_Template = str_replace('{tab data}', JHtml::_('bootstrap.addSlide', 'jdpane', JText::_('COM_JDOWNLOADS_FE_TAB_DATA_TITLE'), 'panel4'), $l_Template);
           $l_Template = str_replace('{tab data end}', JHtml::_('bootstrap.endSlide'), $l_Template);
           $l_Template = str_replace('{tab download}', JHtml::_('bootstrap.addSlide', 'jdpane', JText::_('COM_JDOWNLOADS_FE_TAB_DOWNLOAD_TITLE'), 'panel5'), $l_Template); 
           $l_Template = str_replace('{tab download end}',JHtml::_('bootstrap.endSlide'), $l_Template);
           $l_Template = str_replace('{tab custom1}', JHtml::_('bootstrap.addSlide', 'jdpane', $jlistConfigM['additional.tab.title.1'], 'panel6'), $l_Template); 
           $l_Template = str_replace('{tab custom1 end}', JHtml::_('bootstrap.endSlide'), $l_Template);
           $l_Template = str_replace('{tab custom2}', JHtml::_('bootstrap.addSlide', 'jdpane', $jlistConfigM['additional.tab.title.2'], 'panel7'), $l_Template); 
           $l_Template = str_replace('{tab custom2 end}', JHtml::_('bootstrap.endSlide'), $l_Template);
           $l_Template = str_replace('{tab custom3}', JHtml::_('bootstrap.addSlide', 'jdpane', $jlistConfigM['additional.tab.title.3'], 'panel8'), $l_Template); 
           $l_Template = str_replace('{tab custom3 end}',JHtml::_('bootstrap.endSlide'), $l_Template);
           $l_Template = str_replace('{tabs end}', JHtml::_('bootstrap.endAccordion'), $l_Template);            
       } else {
           // use tabs
           $l_Template = str_replace('{tabs begin}', JHtml::_('bootstrap.startTabSet', 'jdpane', array('active' => 'panel1')), $l_Template);
           $l_Template = str_replace('{tab description}', JHtml::_('bootstrap.addTab', 'jdpane', 'panel1', JText::_('COM_JDOWNLOADS_FE_TAB_DESCRIPTION_TITLE', true)), $l_Template); 
           $l_Template = str_replace('{tab description end}', JHtml::_('bootstrap.endTab'), $l_Template);
           $l_Template = str_replace('{tab pics}', JHtml::_('bootstrap.addTab', 'jdpane', 'panel2', JText::_('COM_JDOWNLOADS_FE_TAB_PICS_TITLE', true)), $l_Template); 
           $l_Template = str_replace('{tab pics end}', JHtml::_('bootstrap.endTab'), $l_Template);
           $l_Template = str_replace('{tab mp3}', JHtml::_('bootstrap.addTab', 'jdpane', 'panel3', JText::_('COM_JDOWNLOADS_FE_TAB_AUDIO_TITLE', true)), $l_Template); 
           $l_Template = str_replace('{tab mp3 end}', JHtml::_('bootstrap.endTab'), $l_Template);
           $l_Template = str_replace('{tab data}', JHtml::_('bootstrap.addTab', 'jdpane', 'panel4', JText::_('COM_JDOWNLOADS_FE_TAB_DATA_TITLE', true)), $l_Template); 
           $l_Template = str_replace('{tab data end}', JHtml::_('bootstrap.endTab'), $l_Template);
           $l_Template = str_replace('{tab download}', JHtml::_('bootstrap.addTab', 'jdpane', 'panel5', JText::_('COM_JDOWNLOADS_FE_TAB_DOWNLOAD_TITLE', true)), $l_Template); 
           $l_Template = str_replace('{tab download end}', JHtml::_('bootstrap.endTab'), $l_Template);
           $l_Template = str_replace('{tab custom1}', JHtml::_('bootstrap.addTab', 'jdpane', 'panel6', $jlistConfig['additional.tab.title.1'], true), $l_Template); 
           $l_Template = str_replace('{tab custom1 end}', JHtml::_('bootstrap.endTab'), $l_Template);
           $l_Template = str_replace('{tab custom2}', JHtml::_('bootstrap.addTab', 'jdpane', 'panel7', $jlistConfig['additional.tab.title.2'], true), $l_Template); 
           $l_Template = str_replace('{tab custom2 end}', JHtml::_('bootstrap.endTab'), $l_Template);
           $l_Template = str_replace('{tab custom3}', JHtml::_('bootstrap.addTab', 'jdpane', 'panel8', $jlistConfig['additional.tab.title.3'], true), $l_Template); 
           $l_Template = str_replace('{tab custom3 end}', JHtml::_('bootstrap.endTab'), $l_Template);
           $l_Template = str_replace('{tabs end}', JHtml::_('bootstrap.endTabSet'), $l_Template);      
       }       

    } else {
       // delete the placeholder 
       $l_Template = str_replace('{tabs begin}', '', $l_Template);
       $l_Template = str_replace('{tab description}', '', $l_Template);
       $l_Template = str_replace('{tab description end}', '', $l_Template);
       $l_Template = str_replace('{tab pics}', '', $l_Template);
       $l_Template = str_replace('{tab pics end}', '', $l_Template);
       $l_Template = str_replace('{tab mp3}', '', $l_Template);
       $l_Template = str_replace('{tab mp3 end}', '', $l_Template);
       $l_Template = str_replace('{tab data}', '', $l_Template);
       $l_Template = str_replace('{tab data end}', '', $l_Template);
       $l_Template = str_replace('{tab download}', '', $l_Template);
       $l_Template = str_replace('{tab download end}', '', $l_Template);
       $l_Template = str_replace('{tab custom1}', '', $l_Template);
       $l_Template = str_replace('{tab custom1 end}', '', $l_Template);      
       $l_Template = str_replace('{tab custom2}', '', $l_Template);
       $l_Template = str_replace('{tab custom2 end}', '', $l_Template);
       $l_Template = str_replace('{tab custom3}', '', $l_Template);
       $l_Template = str_replace('{tab custom3 end}', '', $l_Template);
       $l_Template = str_replace('{tabs end}', '', $l_Template);      
    } 
  
    // custom fields
    $custom_fields_arr = existsCustomFieldsTitlesPlg();
    $row_custom_values = array('dummy',$p_Frow->custom_field_1, $p_Frow->custom_field_2, $p_Frow->custom_field_3, $p_Frow->custom_field_4, $p_Frow->custom_field_5,
                               $p_Frow->custom_field_6, $p_Frow->custom_field_7, $p_Frow->custom_field_8, $p_Frow->custom_field_9, $p_Frow->custom_field_10, $p_Frow->custom_field_11, $p_Frow->custom_field_12, $p_Frow->custom_field_13, $p_Frow->custom_field_14);
    for ($x=1; $x<15; $x++){
        // replace placeholder with title and value
        if (in_array($x,$custom_fields_arr[0]) && $row_custom_values[$x] && $row_custom_values[$x] != '0000-00-00'){
            $l_Template = str_replace("{custom_title_$x}", $custom_fields_arr[1][$x-1], $l_Template);
            if ($x > 5){
                $l_Template = str_replace("{custom_value_$x}", stripslashes($row_custom_values[$x]), $l_Template);
            } else {
                $l_Template = str_replace("{custom_value_$x}", $custom_fields_arr[2][$x-1][$row_custom_values[$x]], $l_Template);
            }    
        } else {
            // remove placeholder
            if ($jlistConfigM['remove.field.title.when.empty']){
                $l_Template = str_replace("{custom_title_$x}", '', $l_Template);
            } else {
                $l_Template = str_replace("{custom_title_$x}", $custom_fields_arr[1][$x-1], $l_Template);
            }    
            $l_Template = str_replace("{custom_value_$x}", '', $l_Template);
        }    
    }
     
  
  // insert files title area
  if (!$jDLayoutTitleExists){
        $l_Template = str_replace('{files_title_begin}', '', $l_Template);
        $l_Template = str_replace('{files_title_end}', '', $l_Template);  
        $l_Template = str_replace('{files_title_text}', JText::_('COM_JDOWNLOADS_FE_FILELIST_TITLE_OVER_FILES_LIST'), $l_Template);
        $jDLayoutTitleExists = true;
  } else {
        if (strpos($l_Template, "{files_title_end}") > 0){
            $pos_end = strpos($l_Template, '{files_title_end}');
            $pos_beg = strpos($l_Template, '{files_title_begin}');
            $l_Template = substr_replace($l_Template, '', $pos_beg, ($pos_end - $pos_beg) + 17);
        }
  }      
  
  
  if ($jDFPv14 > 0){

    $jd_thumbnail = '';
    $jd_screenshot = '';
    $jd_updatedpic = '';

    // Calculer Thumbnail and Screenshot
    $jd_thumbnail = $jDFPsfolders['thumb']."no_pic.gif";
    $jd_screenshot = $jDFPsfolders['screenshot']."no_pic.gif";
    $jd_thumbfile = $jDFPabsolute_path.$jDFPsfolders['thumb'].$p_Frow->thumbnail;
    $jd_screenfile = $jDFPabsolute_path.$jDFPsfolders['screenshot'].$p_Frow->thumbnail;
    if (file_exists($jd_thumbfile) && file_exists($jd_screenfile) && $p_Frow->thumbnail != '') {
      $jd_thumbnail = JURI::base().$jDFPsfolders['thumb'].$p_Frow->thumbnail;
      $jd_screenshot = JURI::base().$jDFPsfolders['screenshot'].$p_Frow->thumbnail;
      $l_Template = str_replace("{thumbnail}",$jd_thumbnail,$l_Template);
      $l_Template = str_replace("{screenshot}",$jd_screenshot,$l_Template);
      $l_Template = str_replace('{screenshot_end}', '', $l_Template);
      $l_Template = str_replace('{screenshot_begin}', '', $l_Template);
      $jd_lightbox = '<a rel="lightbox" href="'.$jd_screenshot.'"><img src="'.$jd_thumbnail.'" /></a>'; 
      $l_Template = str_replace('{thumbnail_lightbox}', $jd_lightbox, $l_Template);
      $jd_lightgallery = '<a title="'.$p_Frow->file_title.'" rel="lightbox[thegallery]" "href="'.$jd_screenshot.'"><img src="'.$jd_thumbnail.'" alt="'.$p_Frow->file_title.'" /></a>';
      $l_Template = str_replace('{thumbnail_gallery}', $jd_lightgallery, $l_Template);

    } else {
      if ($jlistConfigM["thumbnail.view.placeholder.in.lists"] == 1){
        $l_Template = str_replace("{thumbnail}",$jd_thumbnail,$l_Template);
        $l_Template = str_replace("{screenshot}",$jd_screenshot,$l_Template);
        $l_Template = str_replace('{screenshot_end}', '', $l_Template);
        $l_Template = str_replace('{screenshot_begin}', '', $l_Template);
        $jd_lightbox = '<a rel="lightbox" href="'.$jd_screenshot.'"><img src="'.$jd_thumbnail.'" /></a>';
        $l_Template = str_replace('{thumbnail_lightbox}', $jd_lightbox, $l_Template);
        $jd_lightgallery = '<a title="'.$p_Frow->file_title.'" rel="lightbox[thegallery]" "href="'.$jd_screenshot.'"><img src="'.$jd_thumbnail.'" alt="'.$p_Frow->file_title.'" /></a>';
        $l_Template = str_replace('{thumbnail_gallery}', $jd_lightgallery, $l_Template);
      } else {
        $l_Template = str_replace('{thumbnail_lightbox}', '', $l_Template);
        $l_Template = str_replace('{thumbnail_gallery}', '', $l_Template);
        if (strpos($l_Template, "{screenshot_end}") > 0) {
          $pos_end = strpos($l_Template, '{screenshot_end}');
          $pos_beg = strpos($l_Template, '{screenshot_begin}');
          $l_Template = substr_replace($l_Template, '', $pos_beg, ($pos_end - $pos_beg) + 16);
        }
      }
    }
    // pic 2
    $jd_thumbfile = $jDFPabsolute_path.$jDFPsfolders['thumb'].$p_Frow->thumbnail2;
    $jd_screenfile = $jDFPabsolute_path.$jDFPsfolders['screenshot'].$p_Frow->thumbnail2;
    if (file_exists($jd_thumbfile) && file_exists($jd_screenfile) && $p_Frow->thumbnail2 != '') {
      $jd_thumbnail = JURI::base().$jDFPsfolders['thumb'].$p_Frow->thumbnail2;
      $jd_screenshot = JURI::base().$jDFPsfolders['screenshot'].$p_Frow->thumbnail2;
      $l_Template = str_replace("{thumbnail2}",$jd_thumbnail,$l_Template);
      $l_Template = str_replace("{screenshot2}",$jd_screenshot,$l_Template);
      $l_Template = str_replace('{screenshot_end2}', '', $l_Template);
      $l_Template = str_replace('{screenshot_begin2}', '', $l_Template);
      $jd_lightbox = '<a rel="lightbox" href="'.$jd_screenshot.'"><img src="'.$jd_thumbnail.'" /></a>'; 
      $l_Template = str_replace('{thumbnail_lightbox}', $jd_lightbox, $l_Template);
      $jd_lightgallery = '<a title="'.$p_Frow->file_title.'" rel="lightbox[thegallery]" "href="'.$jd_screenshot.'"><img src="'.$jd_thumbnail.'" alt="'.$p_Frow->file_title.'" /></a>';
      $l_Template = str_replace('{thumbnail_gallery}', $jd_lightgallery, $l_Template);

    } else {
      if ($jlistConfigM["thumbnail.view.placeholder.in.lists"] == 1){
        $l_Template = str_replace("{thumbnail2}",$jd_thumbnail,$l_Template);
        $l_Template = str_replace("{screenshot2}",$jd_screenshot,$l_Template);
        $l_Template = str_replace('{screenshot_end2}', '', $l_Template);
        $l_Template = str_replace('{screenshot_begin2}', '', $l_Template);
        $jd_lightbox = '<a rel="lightbox" href="'.$jd_screenshot.'"><img src="'.$jd_thumbnail.'" /></a>';
        $l_Template = str_replace('{thumbnail_lightbox}', $jd_lightbox, $l_Template);
        $jd_lightgallery = '<a title="'.$p_Frow->file_title.'" rel="lightbox[thegallery]" "href="'.$jd_screenshot.'"><img src="'.$jd_thumbnail.'" alt="'.$p_Frow->file_title.'" /></a>';
        $l_Template = str_replace('{thumbnail_gallery}', $jd_lightgallery, $l_Template);
      } else {
        $l_Template = str_replace('{thumbnail_lightbox}', '', $l_Template);
        $l_Template = str_replace('{thumbnail_gallery}', '', $l_Template);
        if (strpos($l_Template, "{screenshot_end2}") > 0) {
          $pos_end = strpos($l_Template, '{screenshot_end2}');
          $pos_beg = strpos($l_Template, '{screenshot_begin2}');
          $l_Template = substr_replace($l_Template, '', $pos_beg, ($pos_end - $pos_beg) + 17);
        }
      }
    }
    // pic 3
    $jd_thumbfile = $jDFPabsolute_path.$jDFPsfolders['thumb'].$p_Frow->thumbnail3;
    $jd_screenfile = $jDFPabsolute_path.$jDFPsfolders['screenshot'].$p_Frow->thumbnail3;
    if (file_exists($jd_thumbfile) && file_exists($jd_screenfile) && $p_Frow->thumbnail3 != '') {
      $jd_thumbnail = JURI::base().$jDFPsfolders['thumb'].$p_Frow->thumbnail3;
      $jd_screenshot = JURI::base().$jDFPsfolders['screenshot'].$p_Frow->thumbnail3;
      $l_Template = str_replace("{thumbnail3}",$jd_thumbnail,$l_Template);
      $l_Template = str_replace("{screenshot3}",$jd_screenshot,$l_Template);
      $l_Template = str_replace('{screenshot_end3}', '', $l_Template);
      $l_Template = str_replace('{screenshot_begin3}', '', $l_Template);
      $jd_lightbox = '<a rel="lightbox" href="'.$jd_screenshot.'"><img src="'.$jd_thumbnail.'" /></a>'; 
      $l_Template = str_replace('{thumbnail_lightbox}', $jd_lightbox, $l_Template);
      $jd_lightgallery = '<a title="'.$p_Frow->file_title.'" rel="lightbox[thegallery]" "href="'.$jd_screenshot.'"><img src="'.$jd_thumbnail.'" alt="'.$p_Frow->file_title.'" /></a>';
      $l_Template = str_replace('{thumbnail_gallery}', $jd_lightgallery, $l_Template);

    } else {
      if ($jlistConfigM["thumbnail.view.placeholder.in.lists"] == 1){
        $l_Template = str_replace("{thumbnail3}",$jd_thumbnail,$l_Template);
        $l_Template = str_replace("{screenshot3}",$jd_screenshot,$l_Template);
        $l_Template = str_replace('{screenshot_end3}', '', $l_Template);
        $l_Template = str_replace('{screenshot_begin3}', '', $l_Template);
        $jd_lightbox = '<a rel="lightbox" href="'.$jd_screenshot.'"><img src="'.$jd_thumbnail.'" /></a>';
        $l_Template = str_replace('{thumbnail_lightbox}', $jd_lightbox, $l_Template);
        $jd_lightgallery = '<a title="'.$p_Frow->file_title.'" rel="lightbox[thegallery]" "href="'.$jd_screenshot.'"><img src="'.$jd_thumbnail.'" alt="'.$p_Frow->file_title.'" /></a>';
        $l_Template = str_replace('{thumbnail_gallery}', $jd_lightgallery, $l_Template);
      } else {
        $l_Template = str_replace('{thumbnail_lightbox}', '', $l_Template);
        $l_Template = str_replace('{thumbnail_gallery}', '', $l_Template);
        if (strpos($l_Template, "{screenshot_end3}") > 0) {
          $pos_end = strpos($l_Template, '{screenshot_end3}');
          $pos_beg = strpos($l_Template, '{screenshot_begin3}');
          $l_Template = substr_replace($l_Template, '', $pos_beg, ($pos_end - $pos_beg) + 17);
        }
      }
    }
    
    

   //Calculate symbol UPDATED
    $jours_diff = DatumsDifferenz_JDm(date('Y-m-d H:m:s'), $p_Frow->modified_date);
    if ($p_Frow->update_active && $jlistConfigM['days.is.file.updated'] > 0 && $jours_diff <= $jlistConfigM['days.is.file.updated']){
      $jd_updatedpic = '<img src="'.JURI::base().$jDFPsfolders['upd'].$jlistConfigM['picname.is.file.updated'].'" alt=""/>';
    } else {
      $jd_updatedpic = "";
    }
    $l_Template = str_replace("{pic_is_updated}",$jd_updatedpic,$l_Template);

    if ($p_Frow->created_id) { 
            $database->setQuery("SELECT username FROM #__users WHERE id = '$p_Frow->created_id'");
            $createdbyname = $database->loadResult();
    }
    if ($p_Frow->modified_id) { 
            $database->setQuery("SELECT username FROM #__users WHERE id = '$p_Frow->modified_id'");
            $modifiedbyname = $database->loadResult();
    }
    if ($createdbyname){
        $l_Template = str_replace('{created_by_value}',$createdbyname, $l_Template);    
    } else {
        $l_Template = str_replace('{created_by_value}',$p_Frow->created_by, $l_Template);
    }    
    if ($modifiedbyname){
        $l_Template = str_replace('{modified_by_value}',$modifiedbyname, $l_Template);
    } else {
        $l_Template = str_replace('{modified_by_value}',$p_Frow->modified_by, $l_Template);
    }
    
    $l_Template = str_replace("{modified_date_value}",$jdmodified_data,$l_Template);
    $l_Template = str_replace('{license_title}', JText::_('COM_JDOWNLOADS_FE_FILELIST_LICENSE_TITLE'), $l_Template);
    $l_Template = str_replace('{price_title}', JText::_('COM_JDOWNLOADS_FE_FILELIST_PRICE_TITLE'), $l_Template);
    $l_Template = str_replace('{language_title}', JText::_('COM_JDOWNLOADS_FE_FILELIST_LANGUAGE_TITLE'), $l_Template);
    $l_Template = str_replace('{filesize_title}', JText::_('COM_JDOWNLOADS_FE_FILELIST_FILESIZE_TITLE'), $l_Template);
    $l_Template = str_replace('{system_title}', JText::_('COM_JDOWNLOADS_FE_FILELIST_SYSTEM_TITLE'), $l_Template);
    $l_Template = str_replace('{author_title}', JText::_('COM_JDOWNLOADS_FE_FILELIST_AUTHOR_TITLE'), $l_Template);
    $l_Template = str_replace('{author_url_title}', JText::_('COM_JDOWNLOADS_FE_FILELIST_AUTHOR_URL_TITLE'), $l_Template);
    $l_Template = str_replace('{created_date_title}', JText::_('COM_JDOWNLOADS_FE_FILELIST_CREATED_DATE_TITLE'), $l_Template);
    $l_Template = str_replace('{hits_title}', JText::_('COM_JDOWNLOADS_FE_FILELIST_HITS_TITLE'), $l_Template);
    $l_Template = str_replace('{created_by_title}', JText::_('COM_JDOWNLOADS_FE_FILELIST_CREATED_BY_TITLE'), $l_Template);
    $l_Template = str_replace('{modified_by_title}', JText::_('COM_JDOWNLOADS_FE_FILELIST_MODIFIED_BY_TITLE'), $l_Template);
    $l_Template = str_replace('{modified_date_title}', JText::_('COM_JDOWNLOADS_FE_FILELIST_MODIFIED_DATE_TITLE'), $l_Template);
    
    // support for content plugins
    // $l_Template = JHTML::_('content.prepare', $l_Template);
  }
  return str_replace("[[[","{",$l_Template);
}


// added by Arno Betz 05.06.2010 - for jD v. 1.7.x
// new placeholder [jd_file_category==catid}
//
function jd_file_createcategory($matches){
   $database = JFactory::getDBO();
   $user = JFactory::getUser();

   $count = '';
   $cat_result = array();
   $sum = strrchr($matches[2] , ' count==');
   $matches[2] = str_replace($sum, '', $matches[2]);
   $sum = (int)str_replace(' count==', '', $sum);
   if ($sum > 0) $count = 'LIMIT '.$sum; 
   $access = jd_checkAccess();

    if ($user->id > 0){
        $user_is_in_groups = getUserGroupsPlg();
    } else {
        $user_is_in_groups = 0;
    }    
    $can_view = false;
    $user_groups = '';                                                  
    if ($user_is_in_groups) $user_groups = "AND cat_group_access IN ($user_is_in_groups)"; 
    
    // cat laden
    if ($user_is_in_groups){
        $database->setQuery("SELECT count(*) FROM #__jdownloads_cats WHERE published = 1 AND cat_id = '".$database->escape($matches[2])."' AND (cat_access <= '$access' OR cat_group_access IN ($user_is_in_groups))");
    } else {
        $database->setQuery("SELECT count(*) FROM #__jdownloads_cats WHERE published = 1 AND cat_id = '".$database->escape($matches[2])."' AND cat_access <= '$access'");
    }
   $cat = $database->loadResult();
   if ($cat){
       $database->setQuery("SELECT * FROM #__jdownloads_files WHERE published = 1 AND cat_id = '".$database->escape($matches[2])."' ORDER BY ordering ".$count);
       $catrows = $database->loadObjectList();
       if ($catrows){
           foreach ($catrows as $catrow){
                $matches[1] = 'file';
                $matches[2] = $catrow->file_id;
                $cat_result .= jd_file_createdownload($matches);   
           }    
       }    
       if (strpos($cat_result, 'Array') < 10){
           $cat_result = str_replace('Array', '', $cat_result);
       }    
       if ($cat_result) {
           return $cat_result; 
       } else {
         return NULL;
       }
   } else {
      return '';
   }     
} 

function getUserGroupsPlg(){
    $database = JFactory::getDBO();
    $user = JFactory::getUser();
    $group_list = array();
    $user_in_groups = array();
    $database->setQuery("SELECT id, groups_members FROM #__jdownloads_groups");
    $all_groups = $database->loadObjectList();
    if (count($all_groups > 0)){
        foreach ($all_groups as $group){
                 $group_list = explode(',', $group->groups_members);
                 if (in_array($user->id, $group_list)){
                     $user_in_groups[] = $group->id;
                 }    
        }    
    }    

    if (count($user_in_groups) > 1){
       $user_in_groups = implode(',', $user_in_groups);
    } else {
        if (isset($user_in_groups[0])){
            $user_in_groups = $user_in_groups[0];
        } else {
            $user_in_groups = 0;
        }    
    }     
    return $user_in_groups;
}

function existsCustomFieldsTitlesPlg(){
    global $jlistConfigM;
    // check that any field is activated (has title)
    $custom_arr = array();
    $custom_array = array();
    $custom_titles = array();
    $custom_values = array();
    for ($i=1; $i<15; $i++){
        if ($jlistConfigM["custom.field.$i.title"] != ''){
           $custom_array[] = $i;
           $custom_titles[] = $jlistConfigM["custom.field.$i.title"];
           $custom_values[] = explode(',', $jlistConfigM["custom.field.$i.values"]);
           array_unshift($custom_values[$i-1],"select");
        } else {
           $custom_array[] = 0;
           $custom_titles[] = '';
           $custom_values[] = '';
        }   
    }    
    $custom_arr[]=$custom_array;
    $custom_arr[]=$custom_titles;
    $custom_arr[]=$custom_values;
    return $custom_arr;
} 

// Calculate root ItemID of jDownloads-component
function jd_CalcItemid(){
        $database = JFactory::getDBO();
        $database->setQuery("SELECT id from #__menu WHERE link = 'index.php?option=com_jdownloads&view=viewcategories' and published = 1");
        $l_Itemid = $database->loadResult();
        if (!$l_Itemid) $l_Itemid = 0;
        return $l_Itemid;
}
?>