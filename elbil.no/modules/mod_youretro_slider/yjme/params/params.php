<?php
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
/*Those are changable module params.They will not affect the news engines.These params are dinamic. You can add more or remove the ones that are here. Do not forget to edit/remove the xml param tags for the params changed/added. Also remove the conditions for the param in module template default.php file*/
defined('_JEXEC') or die('Restricted access');
	$show_read    			 	= $params->get   ('show_read');				// Disable/enable read more link
	
	$is_copy    			 	= $params->get   ('is_copy');	
	$thumb_width           		= $params->get ('thumb_width');	
	$intro_desc_width      		= $params->get ('intro_desc_width');	
	$intro_desc_height     		= $params->get ('intro_desc_height');	
	$intro_desc_pozi		 	= $params->get ('intro_desc_pozi');	

	$slider_height      		= $params->get ('slider_height',300);
	$slider_width       		= $params->get ('slider_width',640);
	$playlist_align    			= $params->get ('playlist_align','left');
	$autoSlide         			= $params->get ('autoSlide',5000);
	$startElem         			= $params->get ('startElem',2);
	$visibleItems      			= $params->get ('visibleItems',6);
	$con_border_radius			= $params->get ('con_border_radius');
	$fadeDurr					= $params->get ('fadeDurr');
	
	if($playlist_align == 'right'){
		$infoContainerPosition = 'left';
	}else{
		$infoContainerPosition = 'right';
	}
	if($playlist_align == 'left'){
		$container_poz = ' left';
	}else{
		$container_poz = '';
	}

/*the headfile.php is moved here in case you need to do some calulations before output or you have params created for your inline JS. This way the headfiles.php sees the params before the load.*/
	require('modules/'.$yj_mod_name.'/yjme/headfiles.php');
?>