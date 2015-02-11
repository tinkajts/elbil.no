<?php
/**
 * @version		3.0.8 plugins/content/setimages/setimages.php
 * 
 * @package		J2XML
 * @subpackage	plg_content_setimages
 * @since		2.5
 *
 * @author		Helios Ciancio <info@eshiol.it>
 * @link		http://www.eshiol.it
 * @copyright	Copyright (C) 2013 Helios Ciancio. All Rights Reserved
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL v3
 * J2XML is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License 
 * or other free or open source software licenses.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access.');

jimport('joomla.plugin.plugin');
jimport('joomla.application.component.helper');
jimport('joomla.filesystem.folder');
jimport('eshiol.j2xml.version');

class plgContentSetimages extends JPlugin
{
	private $_images_folder='images/j2xml/';
	private $_images_path;
	
	/**
	 * Constructor
	 *
	 * @param  object  $subject  The object to observe
	 * @param  array   $config   An array that holds the plugin configuration
	 *
	 * @since       2.5
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
		$this->_images_folder = 'images/'.$this->params->get('folder', 'j2xml').'/';
		$this->_images_path=JPATH_ROOT.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $this->_images_folder);

		JLog::addLogger(array('logger' => 'messagequeue'), JLOG::ALL, array('plg_content_setimages'));
	}
	
	/**
	 * Before save content method
	 * Article is passed by value.
	 * Method is called right before the content is saved
	 *
	 * @param	string		The context of the content passed to the plugin (added in 1.6)
	 * @param	object		A JTableContent object
	 * @param	bool		If the content is just about to be created
	 * @since   2.5
	 */
	public function onContentBeforeSave($context, $article, $isNew)
	{
		$plg_context = $this->params->get('context', -1);
		
		if (($plg_context == -1) && (!class_exists('J2XMLVersion') || (version_compare(J2XMLVersion::getShortVersion(), '13.8.3') == -1)))
		{
			JLog::add(new JLogEntry(JText::_('PLG_CONTENT_SETIMAGES').' '.JText::_('PLG_CONTENT_SETIMAGES_MSG_REQUIREMENTS_LIB')),JLOG::WARNING,'plg_content_setimages');
			return true;
		}

		if ((
			($plg_context == -1) &&
			($context != "lib_j2xml.article") && 
			($context != "cli_j2xml.article") && 
			($context != "com_j2xml.article")
			) || (
			($plg_context == 1) && ($context != "com_content.article")
		)) return true;
		
		if ($embedded = $this->params->get('embedded', 0))
		{
			$this->embeddedImages($article->introtext, $embedded == 1);
			$this->embeddedImages($article->fulltext, $embedded == 1);
		}

		if ($external = $this->params->get('external', 0))
		{
			$this->externalImages($article->introtext, $external == 1);
			$this->externalImages($article->fulltext, $external == 1);
		}
		
		$images = json_decode($article->images);
		$intro_ok = false;
		if (!$images->image_fulltext)
		{		
			if ($fulltext_mode = $this->params->get('image_fulltext', false))
			{
				if ($fulltext_mode == 3)
					self::getImage($article->fulltext, $src, $title, $alt);
				else if ($fulltext_mode == 2)
				{
					self::getImage($article->fulltext, $src, $title, $alt);
					if (!$src)
					{
						self::getImage($article->introtext, $src, $title, $alt);
						$intro_ok = true;
					}
				}
				else if ($fulltext_mode == 1)
				{
					self::getImage($article->introtext, $src, $title, $alt);
					$intro_ok = true;
				}
			
				$images->image_fulltext = $src;
				if ($float_fulltext = $this->params->get('float_fulltext', false))
					$images->float_fulltext = $float_fulltext;
				if ($this->params->get('image_fulltext_alt', false))
					$images->image_fulltext_alt = $alt;
				if ($this->params->get('image_fulltext_caption', false))
					$images->image_fulltext_caption = $title;
			}	
		}

		if (!$images->image_intro) 
		{
			if ($this->params->get('image_intro', false))
			{
				if ($intro_ok)
				{
					$images->image_intro = $images->image_fulltext;
					if ($float_intro = $this->params->get('float_intro', false))
						$images->float_intro = $float_intro;
					if ($this->params->get('image_intro_alt', false))
						$images->image_intro_alt = $images->image_fulltext_alt;
					if ($this->params->get('image_intro_caption', false))
						$images->image_intro_caption = $images->image_fulltext_caption;
				}
				else if ($this->params->get('image_intro', false))
				{
					self::getImage($article->introtext, $src, $title, $alt);
					$images->image_intro = $src;
					if ($float_intro = $this->params->get('float_intro', false))
						$images->float_intro = $float_intro;
					if ($this->params->get('image_intro_alt', false))
						$images->image_intro_alt = $alt;
					if ($this->params->get('image_intro_caption', false))
						$images->image_intro_caption = $title;	
				}
			}
		}
		$article->images = json_encode($images);
		return true;
	}
	
	private static function getImage(&$text, &$src, &$title, &$alt)
	{
		preg_match('/<img(.*)>/i', $text, $matches);
		if (is_array($matches) && !empty($matches))
		{
			$text = preg_replace("/<img[^>]+\>/i","",$text,1);
			preg_match_all('/(src|alt|title)=("[^"]*")/i',$matches[0], $attr);
			for ($i = 0; $i < count($attr[0]); $i++)
				$$attr[1][$i] = substr($attr[2][$i], 1, -1);
		}		
	}

	private function embeddedImages(&$text, $import = true)
	{
		$src = '';
		preg_match_all("/<img .*?(?=src)src=[\"']([^\"]+)[\"'][^>]*>/si", $text, $matches);		
		if (is_array($matches) && !empty($matches))
		{
			for ($i = 0; $i < count($matches[0]); $i++)
			{
				preg_match_all("/data:image\/(.+);base64,(.+)/si", $matches[1][$i], $data);
				if (is_array($data) && !empty($data))
				{
					if (count($data[0]))
					{
						if ($import)
						{
							$img = base64_decode($data[2][0]);
							$img_name = md5($img).'.'.$data[1][0];
							$src = $this->_images_folder.$img_name;
							$image_path=$this->_images_path.$img_name;
							if (file_exists($image_path))
							{		
								$text = str_replace($data[0][0], $src, $text);
								JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_IMAGE_IMPORTED', $img_name)),JLOG::INFO,'plg_content_setimages');
							}
							elseif (($img = @file_get_contents($data[0][0])) !== FALSE)
							{
								if (!JFolder::exists($this->_images_path)) 
								{
									if (JFolder::create($this->_images_path))
										JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_FOLDER_WAS_SUCCESSFULLY_CREATED', $this->_images_path)),JLOG::INFO,'plg_content_setimages');
									else
									{
										JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_ERROR_CREATING_FOLDER', $this->_images_path)),JLOG::WARNING,'plg_content_setimages');
										continue;
									}
								}
								if (JFile::write($image_path, $img))
								{
									JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_IMAGE_SAVED', $img_name)),JLOG::INFO,'plg_content_setimages');
									$text = str_replace($data[0][0], $src, $text);
									JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_IMAGE_IMPORTED', $img_name)),JLOG::INFO,'plg_content_setimages');
								}
								else
								{
									JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_IMAGE_NOT_IMPORTED', $img_name)),JLOG::WARNING,'plg_content_setimages');
								}
							}
							else
							{
								JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_IMAGE_NOT_IMPORTED', $img_name)),JLOG::WARNING,'plg_content_setimages');
							}
						}
						else
						{
							$text = str_replace($matches[0][$i], '', $text);
							JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_IMAGE_REMOVED', $img_name)),JLOG::INFO,'plg_content_setimages');
						}
					}
				}
			}
		}
	}

	private function externalImages(&$text, $import = true)
	{
		$src = '';
		preg_match_all("/<img .*?(?=src)src=[\"']([^\"]+)[\"'][^>]*>/si", $text, $matches);		
		if (is_array($matches) && !empty($matches))
		{
			for ($i = 0; $i < count($matches[0]); $i++)
			{
				preg_match_all("/http:\/\/(.+)/si", $matches[1][$i], $data);
				if (is_array($data) && !empty($data))
				{
					//for ($j = 0; $j < count($data[0]); $j++)
					if (count($data[0]))
					{
						if ($import)
						{
							$img_name = substr($data[0][0], 1 + strrpos($data[0][0], '/'));
							$src = $this->_images_folder.$img_name;
							$image_path=$this->_images_path.$img_name;
							if (file_exists($image_path))
							{		
								$text = str_replace($data[0][0], $src, $text);
								JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_IMAGE_IMPORTED', $data[0][0])),JLOG::INFO,'plg_content_setimages');
							}
							elseif (($img = @file_get_contents($data[0][0])) !== FALSE)
							{
								if (!JFolder::exists($this->_images_path)) 
								{
									if (JFolder::create($this->_images_path))
										JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_FOLDER_WAS_SUCCESSFULLY_CREATED', $this->_images_path)),JLOG::INFO,'plg_content_setimages');
									else
									{
										JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_ERROR_CREATING_FOLDER', $this->_images_path)),JLOG::WARNING,'plg_content_setimages');
										continue;
									}
								}
								if (JFile::write($image_path, $img))
								{
									JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_IMAGE_SAVED', $data[0][0])),JLOG::INFO,'plg_content_setimages');
									$text = str_replace($data[0][0], $src, $text);
									JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_IMAGE_IMPORTED', $data[0][0])),JLOG::INFO,'plg_content_setimages');
								}
								else
								{
									JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_IMAGE_NOT_IMPORTED', $data[0][0])),JLOG::WARNING,'plg_content_setimages');
								}
							}
							else
							{
								JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_IMAGE_NOT_IMPORTED', $data[0][0])),JLOG::WARNING,'plg_content_setimages');
							}
						}
						else
						{
							$text = str_replace($matches[0][$i], '', $text);
							JLog::add(new JLogEntry(JText::sprintf('PLG_CONTENT_SETIMAGES_MSG_IMAGE_REMOVED', $data[0][0])),JLOG::INFO,'plg_content_setimages');
						}
					}
				}
			}
		}
	}
}	