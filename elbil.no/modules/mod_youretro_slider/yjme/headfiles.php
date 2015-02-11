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
			
		$document->addScript(JURI::base() . 'modules/'.$yj_mod_name.'/src/youretro_slider.js');
		$fx_poz = $slider_width - ($thumb_width * 2);
		if($infoContainerPosition =='right'){
			$move_m ='';
		}else{
			$move_m ='-';
		}
		$document->addScriptDeclaration("
		window.addEvent('domready', function(){
				new YouretroSlider({
					navigation:{
						container: 'navigator".$is_copy."',
						elements:'.element',
						outer: 'navigator_outer".$is_copy."',
						selectedClass: 'selected',
						visibleItems: ".$visibleItems."
					},
					slides:{
						container:'slides".$is_copy."',
						elements:'.slide',
						infoContainer:'.long_desc',
						fadeDurr:".$fadeDurr.",
						infoContainerPosition: '".$infoContainerPosition."',
						startFx:{
							'opacity':[0,1],
							'marginLeft':0
						},
						endFx:{
							'opacity':0,
							'marginLeft':".$move_m.$fx_poz."
						}
					},
					startElem: ".$startElem.",
					autoSlide: ".$autoSlide."
				});

			});
		");
		
	if($con_border_radius > 0) {
		$document->addScriptDeclaration("
		window.addEvent('domready', function(){
			  $$('.youretro_cont_border').setStyles({
			  		borderRadius: '".$con_border_radius."px',
			  		WebkitBorderRadius:'".$con_border_radius."px',
			  		MozBorderRadius:'".$con_border_radius."px'
				});
			
		});
			");
	}



//Document type examples
//$document->addStyleSheet(JURI::base() . 'modules/'.$yj_mod_name.'/css/'.$module_css.'');
//$document->addScript('');
//$document->addScriptDeclaration("jQuery.noConflict();");
//$document->addCustomTag('<style type="text/css"></style>');
//$document->addScriptDeclaration("");

?>