<?php 
/*------------------------------------------------------------------------
# mod_jo_accordion - Vertical Accordion Menu for Joomla 1.5 
# ------------------------------------------------------------------------
# author    Roland Soos 
# copyright Copyright (C) 2011 Offlajn.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.offlajn.com
-------------------------------------------------------------------------*/
?>
<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if(!defined('OfflajnThemeCache')) {
  define("OfflajnThemeCache", null);

  class OfflajnThemeCache{
  
    var $module;
    
    var $params;
    
    var $themesDir;
    
    var $themeCacheDir;
    
    var $themeCacheUrl;
    
    function OfflajnThemeCache(&$_module, &$_params, $_themesDir){
      $this->module = &$_module;
      $this->params = &$_params;
      $this->themesDir = &$_themesDir;
      
      $this->init();
    }
    
    function init(){
      $this->themeCacheDir = JPATH_CACHE.DS.$this->module->module.'_theme'.DS.$this->module->id;
      if(!is_dir($this->themeCacheDir)){
        mkdir ($this->themeCacheDir , 0777 , true);
      }
      $this->themeCacheUrl = JURI::root(true).'/cache/'.$this->module->module.'_theme/'.$this->module->id.'/';
    }
    
    function generateCss($c){
      if($this->params->get('themecache') != 2 || !is_file($this->themeCacheDir.DS.'style.css')){
        ob_start();
        include($this->themesDir.$this->params->get('theme', 'elegant').DS.'theme.css.php');
        $css = ob_get_contents();
        ob_end_clean();
        file_put_contents($this->themeCacheDir.DS.'style.css', $css);
      }
      
      if($this->params->get('themecache') == 1){ // change the themecache parameter to 'chached state'!
        $bind = array();
        $bind['id'] = $this->module->id;
        $this->params->set('themecache', '2');
        $bind['params'] = $this->params->toString();
        if(!defined('DEMO')){
          $row =& JTable::getInstance('module');
          $row->bind($bind);
          $row->checkin();
          $row->store();
        }
      }
      return $this->themeCacheUrl.'style.css';
    }
    
  }
}
?>