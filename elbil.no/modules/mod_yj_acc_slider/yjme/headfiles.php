<?php
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
			JHtml::_('behavior.framework', true);
			$document = &JFactory::getDocument();
			$module_css			= $params->get   ('module_css','stylesheet.css');
			$document->addStyleSheet(JURI::base() . 'modules/'.$yj_mod_name.'/css/'.$module_css.'');
		$document->addScript(JURI::base() . 'modules/'.$yj_mod_name.'/src/accslider13.js');
		$document->addScriptDeclaration("
			window.addEvent('domready', function(){
				new FancySlider({
					container:'accslider".$instance."',
					elements:'.slide',
					closedWidth:".$closedWidth.",
					openedWidth:".$openedWidth.",
					autoSlide:".$autoSlide.",
					infoItems: '.info',
					hideTo: ".$infoPosition."
				});
			})
		");
		
		
		$document->addCustomTag('
		  <style type="text/css">
		#accslider li.opened{
		width:'.$openedWidth.'px;
		}
		</style>
		
		');	
		
//Document type examples
//$document->addStyleSheet(JURI::base() . 'modules/'.$yj_mod_name.'/css/'.$module_css.'');
//$document->addScript('');
//$document->addScriptDeclaration("jQuery.noConflict();");
//$document->addCustomTag('<style type="text/css"></style>');
//$document->addScriptDeclaration("");

?>