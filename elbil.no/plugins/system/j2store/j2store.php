<?php
/*------------------------------------------------------------------------
# com_j2store - J2Store
# ------------------------------------------------------------------------
# author    Sasi varna kumar - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://j2store.org
# Technical Support:  Forum - http://j2store.org/forum/index.html
-------------------------------------------------------------------------*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport('joomla.html.parameter');

class plgSystemJ2Store extends JPlugin {

	function plgSystemJ2Store( &$subject, $config ){
		parent::__construct( $subject, $config );
		//load language
		$this->loadLanguage('com_j2store', JPATH_SITE);
		//if($this->_mainframe->isAdmin())return;

	}

	function onAfterRoute() {

		$mainframe = JFactory::getApplication();
		JHtml::_('behavior.framework');
		JHtml::_('behavior.modal');
		require_once (JPATH_ADMINISTRATOR.'/components/com_j2store/library/popup.php');
		require_once (JPATH_SITE.'/components/com_j2store/helpers/modules.php');
		$document =JFactory::getDocument();
		$baseURL = JURI::root();
		$script = "
		if(typeof(j2storeURL) == 'undefined') {
		var j2storeURL = '{$baseURL}';
		}
		";
		$document->addScriptDeclaration($script);

		if($mainframe->isSite()) {
			$this->_addCartJS();
		}

	}


	function _addCartJS() {

		require_once (JPATH_ADMINISTRATOR.'/components/com_j2store/helpers/strapper.php');
		J2StoreStrapper::addJS();
		$document =JFactory::getDocument();
		//initialise the date/time picker
		//localisation
		$currentText = JText::_('J2STORE_TIMEPICKER_JS_CURRENT_TEXT');
		$closeText = JText::_('J2STORE_TIMEPICKER_JS_CLOSE_TEXT');
		$timeOnlyText = JText::_('J2STORE_TIMEPICKER_JS_CHOOSE_TIME');
		$timeText = JText::_('J2STORE_TIMEPICKER_JS_TIME');
		$hourText = JText::_('J2STORE_TIMEPICKER_JS_HOUR');
		$minuteText = JText::_('J2STORE_TIMEPICKER_JS_MINUTE');
		$secondText = JText::_('J2STORE_TIMEPICKER_JS_SECOND');
		$millisecondText = JText::_('J2STORE_TIMEPICKER_JS_MILLISECOND');
		$timezoneText = JText::_('J2STORE_TIMEPICKER_JS_TIMEZONE');

		$localisation ="
		currentText: '$currentText',
		closeText: '$closeText',
		timeOnlyTitle: '$timeOnlyText',
		timeText: '$timeText',
		hourText: '$hourText',
		minuteText: '$minuteText',
		secondText: '$secondText',
		millisecText: '$millisecondText',
		timezoneText: '$timezoneText'
		";

		$timepicker_script ="
			if(typeof(j2store) == 'undefined') {
				var j2store = {};
			}

			if(typeof(j2store.jQuery) == 'undefined') {
				j2store.jQuery = jQuery.noConflict();
			}

		(function($) {
			$(document).ready(function(){
			//date, time, datetime
			if ($.browser.msie && $.browser.version == 6) {
				$('.j2store_date, .j2store_datetime, .j2store_time').bgIframe();
			}

			$('.j2store_date').datepicker({dateFormat: 'yy-mm-dd'});
			$('.j2store_datetime').datetimepicker({
				dateFormat: 'yy-mm-dd',
				timeFormat: 'HH:mm',
				$localisation
			});

			$('.j2store_time').timepicker({timeFormat: 'HH:mm', $localisation});

		});
		})(j2store.jQuery);
		";
		$document->addScriptDeclaration($timepicker_script);

	}
}
