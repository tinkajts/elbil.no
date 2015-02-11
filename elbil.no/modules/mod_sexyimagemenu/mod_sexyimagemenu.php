<?php
/**
* @Copyright Copyright (C) 2010- ... Vijay Padsumbiya
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * mod_sexyimagemenu is Commercial software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
**/


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$jquery_load = $params->get( 'jquery_load', 0 );
$conflict_load = $params->get( 'conflict_load', 0 );

$menu_width = $params->get( 'menu_width', 0 );
$single_menu_expand_width = $params->get( 'single_menu_expand_width', 0 );
$menu_height = $params->get( 'menu_height', 0 );
$left_margin = $params->get( 'left_margin', 0 );
$top_margin = $params->get( 'top_margin', 0 );
$bottom_margin = $params->get( 'bottom_margin', 0 );
$image_number = $params->get( 'image_number', 0 );
$media_option = $params->get( 'media_option', 0 );

$auto_play = $params->get( 'auto_play', 0 );
$delay_autoplay = $params->get( 'delay_autoplay', 0 );
$sliding_speed = $params->get( 'sliding_speed', 0 );

$accordin_height = $params->get( 'accordin_height', 0 );
$accordin_background = $params->get( 'accordin_background', 0 );

$link_text = $params->get( 'link_text', 0 );
$link_open_window = $params->get( 'link_open_window', 0 );

$font_family = $params->get( 'font_family', 0 );
$main_title_size = $params->get( 'main_title_size', 0 );
$main_title_color = $params->get( 'main_title_color', 0 );
$main_title_backcolor = $params->get( 'main_title_backcolor', 0 );

$sub_title_size = $params->get( 'sub_title_size', 0 );
$sub_title_color = $params->get( 'sub_title_color', 0 );

$sub_desc_size = $params->get( 'sub_desc_size', 0 );
$sub_desc_color = $params->get( 'sub_desc_color', 0 );

$sub_link_size = $params->get( 'sub_link_size', 0 );
$sub_link_color = $params->get( 'sub_link_color', 0 );

$image_1 = $params->get( 'image_1', 0 );
$link_1_title = $params->get( 'link_1_title', 0 );
$link_1_desc = $params->get( 'link_1_desc', 0 );
$link_1 = $params->get( 'link_1', 0 );

$image_2 = $params->get( 'image_2', 0 );
$link_2_title = $params->get( 'link_2_title', 0 );
$link_2_desc = $params->get( 'link_2_desc', 0 );
$link_2 = $params->get( 'link_2', 0 );

$image_3 = $params->get( 'image_3', 0 );
$link_3_title = $params->get( 'link_3_title', 0 );
$link_3_desc = $params->get( 'link_3_desc', 0 );
$link_3 = $params->get( 'link_3', 0 );

$image_4 = $params->get( 'image_4', 0 );
$link_4_title = $params->get( 'link_4_title', 0 );
$link_4_desc = $params->get( 'link_4_desc', 0 );
$link_4 = $params->get( 'link_4', 0 );

$image_5 = $params->get( 'image_5', 0 );
$link_5_title = $params->get( 'link_5_title', 0 );
$link_5_desc = $params->get( 'link_5_desc', 0 );
$link_5 = $params->get( 'link_5', 0 );

$image_6 = $params->get( 'image_6', 0 );
$link_6_title = $params->get( 'link_6_title', 0 );
$link_6_desc = $params->get( 'link_6_desc', 0 );
$link_6 = $params->get( 'link_6', 0 );

$image_7 = $params->get( 'image_7', 0 );
$link_7_title = $params->get( 'link_7_title', 0 );
$link_7_desc = $params->get( 'link_7_desc', 0 );
$link_7 = $params->get( 'link_7', 0 );

$image_8 = $params->get( 'image_8', 0 );
$link_8_title = $params->get( 'link_8_title', 0 );
$link_8_desc = $params->get( 'link_8_desc', 0 );
$link_8 = $params->get( 'link_8', 0 );


$content = modSexyImageMenuHelper::getStart( $params );
require( JModuleHelper::getLayoutPath( 'mod_sexyimagemenu' ) );