<?php
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
//Title: 			$yj_get_items['item_title']
//Author: 			$yj_get_items['item_author'] = username || $yj_get_items['item_author_rn'] = real name
//Image:			$yj_get_items['img_url'] = use isset to check before output
//Intro text:		$yj_get_items['item_intro']
//Create date:		$yj_get_items['item_date']
//Category:			$yj_get_items['cat_title']
//Item url:			$yj_get_items['item_url']
//Author url: 		$yj_get_items['author_url']
//Cat url:			$yj_get_items['cat_url']
//Foreach to be used =  foreach ($main_yj_arr as $yj_get_items){ echo each part here }

/*Image sizing: The images are inside div that is resizing when you enter the values in module parameters. this way there is no image disortion. For those who dont like that , you can add this
style="width:<?php echo $img_width ?>;height:<?php echo $img_height ?>;"
within image tag after alt="" (space it please) and have the images resized */

  
defined('_JEXEC') or die('Restricted access'); ?>
<!-- http://www.Youjoomla.com  Youjoomla Accordion Slider Module V 3.0 for Joomla 1.7 and UP starts here -->
<div id="accslide_holder<?php echo $instance ?>" class="accslide_holder_instance" style="height:<?php echo $sliderHeight ?>;width:<?php echo $sliderWidth ?>;">
    <ul id="accslider<?php echo $instance ?>" class="accslider_instance">
        <?php foreach ($main_yj_arr as $key=> $yj_get_items):?>
        <li class="slide<?php if($key==0):?> opened<?php endif ?>" style="height:<?php echo $sliderHeight ?>;">
 <?php  if (isset($yj_get_items['img_url']) && $yj_get_items['img_url'] != "") :?>
 <img src="<?php echo $yj_get_items['img_url'] ?>" alt="<?php echo $yj_get_items['item_title']?>" />
      <?php endif;?>
            <div class="info" style="width:<?php echo $openedWidth -40 ?>px;bottom:<?php echo $infoPosition ?>px;"> <a href="<?php  echo  $yj_get_items['item_url'] ?>"><span class="title">
                <?php  echo  $yj_get_items['item_title'] ?>
                </span></a>
                <?php  echo  $yj_get_items['item_intro'] ?>
            </div>
        </li>
        <?php  endforeach;?>
    </ul>
</div>