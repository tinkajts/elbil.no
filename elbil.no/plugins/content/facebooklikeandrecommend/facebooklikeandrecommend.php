<?php
/**
 * @version		1.0
 * @Project		Facebook Like And Recommend Button
 * @author 		Leon Wood, CMSVoteUp.com
 * @package		
 * @copyright	Copyright (C) 2011 CMSVoteUp.com. All rights reserved.
 * @license 	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgContentfacebooklikeandrecommend extends JPlugin {

	function plgContentfacebooklikeandrecommend( &$subject, $config )
	{
		parent::__construct( $subject, $config );
	}

	function onContentPrepare($context, &$article, &$params, $page=0)
	{	
		
		$document	= & JFactory::getDocument();
		$view		= JRequest::getCmd('view');
		$position          = $this->params->get( 'position',  '' );
		
		if ( $view != 'article' ) { 
			return;	
		} else {
			$title= htmlentities( $article->title, ENT_QUOTES, "UTF-8");
			$url = $this->getPageUrl($article);
			
			if ($position == '1'){
				$article->text =  $this->getPlugInHTML($params, $article, $url, $title) . $article->text;  
			}
			if ($position == '2'){
				$article->text = $article->text . $this->getPlugInHTML($params, $article, $url, $title);
			}
			if ($position == '3'){
				$article->text =  $this->getPlugInHTML($params, $article, $url, $title) . $article->text . $this->getPlugInHTML($params, $article, $url, $title);
			}			
		}
			
	}
	
	private function  getPlugInHTML($params, $article, $url, $title) { 
		$category_tobe_excluded   = $this->params->get('category_tobe_excluded', '' );
		$content_tobe_excluded           = $this->params->get('content_tobe_excluded', '' );
		$excludedCatList = @explode ( ",", $category_tobe_excluded );	
		$excludedContentList 	   = @explode ( ",", $content_tobe_excluded );		
		if ( in_array ( $article->id, $excludedContentList ) || in_array ( $article->catid, $excludedCatList ) ) return;
		$layout_style          	= $this->params->get( 'layout_style');
		$show_faces               = $this->params->get('show_faces');		
		$credit_to_Author           = $this->params->get( 'credit_to_Author', 1);
		$width           = $this->params->get( 'width');
		$verb_to_display  = $this->params->get( 'verb_to_display');
		if ($verb_to_display == 1) {
			$verb_to_display  = "like";
		} else {
			$verb_to_display = "recommend";
		}
		$font       = $this->params->get( 'font');		
		$color_scheme   = $this->params->get( 'color_scheme');
		
		$htmlCode ="<div>";
		$htmlCode .= "<script src=\"http://connect.facebook.net/en_US/all.js#xfbml=1\"></script><fb:like href=\"$url\" layout=\"$layout_style\" show_faces=\"$show_faces\" width=\"$width\" action=\"$verb_to_display\" font=\"$font\" colorscheme=\"$color_scheme\"></fb:like> \n";
		if ($credit_to_Author) {
			$htmlCode .= "<a href=\"http://cmsvoteup.com/\" title=\"Free Facebook Like Button Plugin for Joomla\" target=\"_blank\"><img src=\"http://cmsvoteup.com/images/power_by_2x2.gif\" border=\"0\"/></a>"; 
		}
		$htmlCode .="</div>";
	
     return $htmlCode; 
	}
	
	private function getPageUrl($obj)
	{
		if (!is_null($obj)) 
		{
			$url = JRoute::_(ContentHelperRoute::getArticleRoute($obj->slug, $obj->catslug, $obj->sectionid));
			$uri = JURI::getInstance();
      		$base  = $uri->toString( array('scheme', 'host', 'port'));
			$url = $base . $url;
			$url = JRoute::_($url, true, 0);
			return $url;
		}
	}
}
?>