<?php 
/*------------------------------------------------------------------------
# universalajaxlivesearch - Universal AJAX Live Search
# ------------------------------------------------------------------------
# author    Janos Biro
# copyright Copyright (C) 2013 Offlajn.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.offlajn.com
-------------------------------------------------------------------------*/
?>
<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.installer.helper');
jimport('joomla.filesystem.folder');
defined('DS') or define( 'DS', DIRECTORY_SEPARATOR );

function com_install(){
	$installer = new Installer();	
//	echo "<H3>Installing Universal AJAX Live Search component and module Success</h3>"; 
	$installer->install();
	return true;

}
function com_uninstall(){
	$installer = new Installer();	
	$installer->uninstall();
	return true;
}

class Installer extends JObject {

	function install() {
    $installer = new JInstaller();
    $installer->setOverwrite(true);

    $pkg_path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_universal_ajax_live_search'.DS.'extensions'.DS;
    $pkgs = array( 
      'mod_universal_ajaxlivesearch'=>'Ajax Live Search',
      'mod_ajaxlivesearchresult'=>'AJAX Live Search results',
      'offlajnjoomla3compat' => 'joomla3compat'
    );
    
    $v3 = version_compare(JVERSION,'3.0.0','ge');
    if ($v3) {
      foreach($pkgs as $pkg => $pkgname) {
        $f = $pkg_path.DS.$pkg;
        $xmlfiles = JFolder::files($f, '.xml$', 1, true);
        foreach($xmlfiles AS $xmlf){
          $file = file_get_contents($xmlf);
          file_put_contents($xmlf, preg_replace("/<\/install/","</extension",preg_replace("/<install/","<extension",$file)));
        }
      }
    }    

    foreach( $pkgs as $pkg => $pkgname ):
      if( $installer->install( $pkg_path.DS.$pkg ) )
      {
        $msgcolor = "#E0FFE0";
        $msgtext  = "$pkgname successfully installed.";
      }
      else
      {
        $msgcolor = "#FFD0D0";
        $msgtext  = "ERROR: Could not install the $pkgname. Please contact us on our support page: http://offlajn.com/support.html";
      }

      ?>
      <table bgcolor="<?php echo $msgcolor; ?>" width ="100%">
        <tr style="height:30px">
          <td width="50px"><img src="/administrator/images/tick.png" height="20px" width="20px"></td>
          <td><font size="2"><b><?php echo $msgtext; ?></b></font></td>
        </tr>
      </table>
    <?php
    endforeach;
    $db = JFactory::getDBO();
    if (version_compare(JVERSION,'1.6.0','lt')) {
      $db->setQuery("UPDATE #__plugins SET published=1 WHERE name LIKE '%offlajn%' OR name LIKE 'Nextend Joomla 3.0 compatibility' OR name LIKE 'Nextend Dojo Loader'");
		} else {
      $db->setQuery("UPDATE #__extensions SET enabled=1 WHERE (name LIKE '%offlajn%' OR name LIKE 'Nextend Joomla 3.0 compatibility' OR name LIKE 'Nextend Dojo Loader') AND type='plugin'");
    }
    $db->query();    
	}

	function uninstall() {
  }

}

class com_Universal_AJAX_Live_SearchInstallerScript
{
  function install($parent) {
		com_install();
	}
  
  function uninstall($parent) {
		com_uninstall();
	}
 
	function update($parent) {
		com_install();
	}
}
