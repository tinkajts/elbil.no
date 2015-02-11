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
<!-- Youretro Slider is Powered by YJ Module Engine find out more at www.youjoomla.com -->
<div class="youretro_cont_border" style="width:<?php echo $slider_width ?>px;">
  <div id="Youretro_container<?php echo $is_copy ?>" class="Youretro_container<?php echo $container_poz ?>" style="height:<?php echo $slider_height -1 ?>px; width:<?php echo $slider_width ?>px;"> 
    <!-- navigator -->
    <div id="navigator_outer<?php echo $is_copy ?>" class="navigator_outer" style="height:<?php echo $slider_height ?>px; width:<?php echo  ($thumb_width) * 2 ?>px;">
      <ul id="navigator<?php echo $is_copy ?>" class="navigator">
        <?php foreach ($main_yj_arr as $key => $yj_get_items):?>
        <li class="element" style="height:<?php echo $slider_height / $visibleItems  ?>px;width:<?php echo $thumb_width?>;">
          <div class="inner" style="width:<?php echo $thumb_width?>;height:<?php echo $slider_height / $visibleItems   ?>px">
            <div class="inner_over" style="width:<?php echo $thumb_width?>;height:<?php echo $slider_height / $visibleItems?>px">
              <div class="overimg" style="width:<?php echo $thumb_width ?>;height:<?php echo $slider_height / $visibleItems?>px"></div>
              <div class="img_holder" style="width:<?php echo $thumb_width ?>;height:<?php echo $slider_height / $visibleItems?>px"> <img class="retroimg" src="<?php echo $yj_get_items['img_url']; ?>" height="<?php echo $slider_height / $visibleItems?>" alt="<?php echo $yj_get_items['item_title']?>" /> </div>
            </div>
          </div>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
    <!-- end of navigator, start slides -->
    <div id="slides<?php echo $is_copy ?>" class="slideshold" style="height:<?php echo $slider_height ?>px; width:<?php echo $slider_width - ($thumb_width * 2)    ?>px;">
      <?php foreach ($main_yj_arr as $yj_get_items):?>
      <div class="slide" style="height:<?php echo $slider_height ?>px; width:<?php echo $slider_width - ($thumb_width * 2) ?>px;"> <a href="<?php echo $yj_get_items['item_url'] ?>" title=""> <img src="<?php echo $yj_get_items['img_url'] ?>" alt="<?php echo $yj_get_items['item_title']?>" width="<?php echo $slider_width - ($thumb_width * 2) ?>" /> </a>
        <div class="long_desc" style="width:<?php echo $intro_desc_width ?>;height:<?php echo $intro_desc_height ?>;<?php echo $intro_desc_pozi ?>">
          <h1 class="yr_into_title"><a href="<?php echo $yj_get_items['item_url'] ?>" title=""><?php echo  $yj_get_items['item_title'] ?></a></h1>
          <?php echo $yj_get_items['item_intro'] ?> 
          <?php if($show_read == 1) : ?>
          <a href="<?php echo $yj_get_items['item_url'] ?>" title="" class="youretro_readon">
		  	<?php echo JText::_('READ_MORE_TEXT') ?>
          </a>
          <?php endif; ?>
       </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>