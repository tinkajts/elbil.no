<?php
/**
* @version $Id: mod_jdownloads_top.php v2.0
* @package mod_jdownloads_top
* @copyright (C) 2011 Arno Betz
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author Arno Betz http://www.jDownloads.com
*
* This modul shows you the most recent downloads from the jDownloads component. 
* It is only for jDownloads 1.9 and later (Support: www.jDownloads.com)
*/

// this is a default layout and used tables - you can also select a alternate tableless layout in the module configuration

defined('_JEXEC') or die;

    $html = '';
    $html = '<table width="100%" class="moduletable'.$moduleclass_sfx.'">';
    
    if ($files) {
        if ($text_before <> ''){
            $html .= '<tr><td>'.$text_before.'</td></tr>';   
        }
        for ($i=0; $i<count($files); $i++) {
            $version = $short_version;
            if ($sum_char > 0){
                $gesamt = strlen($files[$i]->file_title) + strlen($files[$i]->release) + strlen($short_version) +1;
                if ($gesamt > $sum_char){
                   $files[$i]->file_title = JString::substr($files[$i]->file_title, 0, $sum_char).$short_char;
                   $files[$i]->release = '';
                }    
            }
            
            $database->setQuery("SELECT id from #__menu WHERE link = 'index.php?option=com_jdownloads&view=viewcategory&catid=".$files[$i]->cat_id."' and published = 1");
            $Itemid = $database->loadResult();
            if (!$Itemid){
                $Itemid = $root_itemid;
            }  
                
			if ($cat_show) {
				if ($cat_show_type == 'containing') {
					$database->setQuery('SELECT cat_title FROM #__jdownloads_cats WHERE cat_id = '.$files[$i]->cat_id);
					$cattitle = $database->loadResult();
					$cat_show_text2 = $cat_show_text.$cattitle;
				} else {
					$database->setQuery('SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = '.$files[$i]->cat_id);
					$catdir = $database->loadResult();
					$cat_show_text2 = $cat_show_text.$catdir;
				}
			} else {
                $cat_show_text2 = '';
            }    

            if ($detail_view == '1'){
                $link = 'index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=viewdownload&catid='.$files[$i]->cat_id.'&cid='.$files[$i]->file_id;
            } else {    
                $link = 'index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=viewcategory&catid='.$files[$i]->cat_id;
            }    
            if ($sef==1){
                $link = JRoute::_($link);
            }
            if (!$files[$i]->release) $version = '';
            
            // build icon
            $size = 0;
            $files_pic = '';
            $number = '';
            if ($view_pics){
                $size = (int)$view_pics_size;
                $files_pic = '<img src="'.JURI::base().'images/jdownloads/fileimages/'.$files[$i]->file_pic.'" align="top" width="'.$size.'" height="'.$size.'" border="0" alt="" /> '; 
            }
            // build number list
            if ($view_numerical_list){
                $num = $i+1;
                $number = "$num. ";
            }
            
            if ($view_tooltip){
                $link_text = '<a href="'.$link.'">'.JHTML::tooltip(strip_tags(substr($files[$i]->description,0,$view_tooltip_length)).$short_char,JText::_('MOD_JDOWNLOADS_LAST_UPDATED_DESCRIPTION_TITLE'),$files[$i]->file_title.' '.$version.$files[$i]->release,$files[$i]->file_title.' '.$version.$files[$i]->release).'</a>';                
            } else {    
                $link_text = '<a href="'.$link.'">'.$files[$i]->file_title.' '.$version.$files[$i]->release.'</a>';
            }    
            $html .= '<tr valign="top"><td align="'.$alignment.'">'.$number.$files_pic.$link_text.'</td>';
            
            if ($view_date) {
                if ($files[$i]->modified_date){
                    if ($view_date_same_line){
                        $html .= '<td align="'.$date_alignment.'"><small>'.$view_date_text.'&nbsp;'.substr(JHTML::Date($files[$i]->modified_date,$date_format),0,10).'</small></td>';
                    } else {
                        $html .= '</tr><tr><td align="'.$date_alignment.'"><small>'.$view_date_text.'&nbsp;'.substr(JHTML::Date($files[$i]->modified_date,$date_format),0,10).'</small></td>';
                    }    
                }
            }
            $html .= '</tr>';
            
            // add the first download screenshot when exists and activated in options
            if ($view_thumbnails){
                if ($files[$i]->thumbnail){
                    $thumbnail = '<img class="img" src="'.$thumbfolder.$files[$i]->thumbnail.'" align="top" style="padding:5px;" width="'.$view_thumbnails_size.'" height="'.$view_thumbnails_size.'" border="'.$border.'" alt="'.$files[$i]->file_title.'" />';
                } else {
                    // use placeholder
                    if ($view_thumbnails_dummy){
                        $thumbnail = '<img class="img" src="'.$thumbfolder.'no_pic.gif" align="top" style="padding:5px;" width="'.$view_thumbnails_size.'" height="'.$view_thumbnails_size.'" border="'.$border.'" alt="" />';    
                    }
                }
                if ($thumbnail) $html .= '<tr valign="top"><td align="'.$alignment.'">'.$thumbnail.'</td></tr>';
            }
            
             
            if ($cat_show_text2) {
                if ($cat_show_as_link){
                    $html .= '<tr valign="top"><td align="'.$alignment.'" style="font-size:'.$cat_show_text_size.'; color:'.$cat_show_text_color.';"><a href="index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=viewcategory&catid='.$files[$i]->cat_id.'">'.$cat_show_text2.'</a></td></tr>';
                } else {    
                    $html .= '<tr valign="top"><td align="'.$alignment.'" style="font-size:'.$cat_show_text_size.'; color:'.$cat_show_text_color.';">'.$cat_show_text2.'</td></tr>';
                }
            }
        
        }
        if ($text_after <> ''){
            $html .= '<tr><td>'.$text_after.'</td></tr>';
        }
    }
    
    echo $html.'</table>'; 
?>