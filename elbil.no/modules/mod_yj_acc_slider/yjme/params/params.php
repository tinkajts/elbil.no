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
		$instance					= $params->get   ('instance');
		$closedWidth      			= $params->get   ('closedWidth',65);
		$openedWidth      			= $params->get   ('openedWidth',640);
		$sliderWidth      			= $params->get   ('sliderWidth',"900px");
		$sliderHeight      			= $params->get   ('sliderHeight',"330px");
		$infoPosition      			= $params->get   ('infoPosition',"-50");
		$autoSlide        			= $params->get   ('autoSlide',"5000");	

/*the headfile.php is moved here in case you need to do some calulations before output or you have params created for your inline JS. This way the headfiles.php sees the params before the load.*/
	require('modules/'.$yj_mod_name.'/yjme/headfiles.php');
?>