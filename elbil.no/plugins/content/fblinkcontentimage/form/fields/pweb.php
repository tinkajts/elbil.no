<?php
/**
* @version 2.0.16
* @package PWebFBLinkArticleImages
* @copyright © 2014 Perfect Web sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

defined('_JEXEC') or die( 'Restricted access' );

JFormHelper::loadFieldClass('Radio');

/**
 * Perfect-Web
 *
 * @subpackage	plg_fblinkcontentimage
 * @since		2.5
 */
class JFormFieldPweb extends JFormFieldRadio
{
	protected $extension = 'plg_fblinkcontentimage_core';
	protected $documentation = 'http://www.perfect-web.co/joomla/link-with-article-images-facebook/documentation';
	protected $home = 'http://www.perfect-web.co/joomla/link-with-article-images-facebook?utm_source=backend&utm_medium=button&utm_campaign=upgrade_to_pro';
	
	protected function getInput()
	{		
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		
		// add documentation and upgrade toolbar button
		if (version_compare(JVERSION, '3.0.0') == -1) {
			$button = '<a href="'.$this->documentation.'" style="font-weight:bold;border-color:#025A8D;background-color:#DBE4E9;" target="_blank"><span class="icon-32-help"> </span> '.JText::_('PLG_PWEB_FBARTICLEIMAGES_DOCUMENTATION').'</a>';
			
			$button_upgrade = '<a href="'.$this->home.'" style="font-weight:bold;border-color:#025A8D;background-color:#DBE4E9;" target="_blank"><span class="icon-32-upload"> </span> '.JText::_('PLG_PWEB_FBARTICLEIMAGES_UPGRADE_PRO').'</a>';
		} else {
			$button = '<a href="'.$this->documentation.'" class="btn btn-small btn-info" target="_blank"><i class="icon-support"> </i> '.JText::_('PLG_PWEB_FBARTICLEIMAGES_DOCUMENTATION').'</a>';
			
			$button_upgrade = '<a href="'.$this->home.'" class="btn btn-small btn-info" target="_blank"><i class="icon-upload "> </i> '.JText::_('PLG_PWEB_FBARTICLEIMAGES_UPGRADE_PRO').'</a>';
		}
		$bar = JToolBar::getInstance();
		$bar->appendButton('Custom', $button, $this->extension.'-docs');
		$bar->appendButton('Custom', $button_upgrade, $this->extension.'-upgrade');
		
		// Check if SEF is enabled
		$cfg = JFactory::getConfig();
		if (!$cfg->get('sef')) 
		{
			if (version_compare(JVERSION, '3.0.0') == -1) {
				$onclick = 'Cookie.write(\'configuration\',\'site\')';
			}
			elseif (version_compare(JVERSION, '3.2.0') >= 0) {
				$onclick = 'window.localStorage.setItem(\'tab-href\', \'#page-site\')';
			}
			
			$app->enqueueMessage('Enable <a href="index.php?option=com_config"'.(isset($onclick) ? ' onclick="'.$onclick.'"' : '').' target="_blank">Search Engine Friendly URLs</a> in SEO Settings on Site tab for better experience on Facebook', 'warning');
		}

		// Core version disabled fields
		if (version_compare(JVERSION, '3.0.0') == -1) {
			JHtml::_('behavior.framework');
			$doc->addScriptDeclaration(
				'window.addEvent("domready",function(){'.
					'$$("form[name=adminForm] input:disabled").each(function(el){'.
						'$$("label[for="+el.get("id")+"]").addClass("disabled").removeEvents("click");'.
					'});'.
				'});'
			);
			$doc->addStyleDeclaration(
				'label.disabled{color:#aaa}'
			);
		} else {
			$doc->addScriptDeclaration(
				'jQuery(document).ready(function($){'.
					'$("form[name=adminForm] input:disabled").each(function(){'.
						'$("label[for="+$(this).attr("id")+"]").addClass("disabled").unbind("click");'.
					'});'.
				'});'
			);
		}
		
		// add feed script
		if ($this->value)
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('manifest_cache');
			$query->from('#__extensions');
			$query->where('type = "plugin"');
			$query->where('folder = "content"');
			$query->where('element = "fblinkcontentimage"');
			$db->setQuery($query);
			
			try {
				$manifest_str = $db->loadResult();
			} catch (RuntimeException $e) {
				$manifest_str = null;
			}
			$manifest = new JRegistry($manifest_str);
			
			$doc->addScriptDeclaration(
				'(function(){'.
				'var pw=document.createElement("script");pw.type="text/javascript";pw.async=true;'.
				'pw.src="https://www.perfect-web.co/index.php?option=com_pwebshop&view=updates&format=raw&extension='.$this->extension.'&version='.$manifest->get('version', '2.0.0').'&jversion='.JVERSION.'&host='.urlencode(JUri::root()).'";'.
				'var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(pw,s);'.
				'})();'
			);
		}
		
		return parent::getInput();
	}
}