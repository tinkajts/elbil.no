<?php
/*------------------------------------------------------------------------
# com_j2store - J2Store
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://j2store.org
# Technical Support:  Forum - http://j2store.org/forum/index.html
-------------------------------------------------------------------------*/


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
 $action = JRoute::_('index.php?option=com_j2store&view=options');
 $document = JFactory::getDocument();
 $document->addScriptDeclaration("
 Joomla.submitbutton = function(pressbutton){
 		if (pressbutton == 'cancel') {
 		submitform( pressbutton );
 		return;
 		}
 		if (J2Store.trim(J2Store('#option_unique_name').val()) == '') {
 			alert( '".JText::_('J2STORE_OPTION_UNIQUE_NAME_MUST_HAVE_A_TITLE', true)."' );
 		} else if(J2Store.trim(J2Store('#option_name').val()) == '') {
 			alert( '".JText::_('J2STORE_OPTION_NAME_MUST_HAVE_A_TITLE', true)."' );
 		}else {
 		submitform( pressbutton );

 }
 }
 ");

?>
<div class="j2store">
<form action="index.php" method="post" name="adminForm" id="adminForm">
<fieldset>
	<legend><?php echo JText::_('J2STORE_OPTION_DETAILS'); ?> </legend>

	<table class="admintable">

	<tr>
			<td width="100" align="right" class="key">
				<label for="option_unique_name">
					<?php echo JText::_( 'J2STORE_OPTION_UNIQUE_NAME' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="option_unique_name" id="option_unique_name" class="required" value="<?php echo $this->data->option_unique_name;?>" />
			</td>
		</tr>

		<tr>
			<td width="100" align="right" class="key">
				<label for="option_name">
					<?php echo JText::_( 'J2STORE_OPTION_DISPLAY_NAME' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="option_name" id="option_name" class="required" value="<?php echo $this->data->option_name;?>" />
			</td>
		</tr>

		<tr>
			<td width="100" align="right" class="key">
				<label for="type">
					<?php echo JText::_( 'J2STORE_OPTION_TYPE' ); ?>:
				</label>
			</td>
			<td>
				<select name="type">
                <optgroup label="<?php echo JText::_( 'J2STORE_OPTION_OPTGROUP_LABEL_CHOOSE' ); ?>">
                                <option <?php echo ($this->data->type=='select')? 'selected="selected"':''?> value="select"><?php echo JText::_( 'J2STORE_SELECT' ); ?></option>
                               <option  <?php echo ($this->data->type=='radio')? 'selected="selected"':''?> value="radio"><?php echo JText::_( 'J2STORE_RADIO' ); ?></option>
                               <option  <?php echo ($this->data->type=='checkbox')? 'selected="selected"':''?> value="checkbox"><?php echo JText::_( 'J2STORE_CHECKBOX' ); ?></option>
                   </optgroup>
                		<optgroup label="<?php echo JText::_( 'J2STORE_OPTION_OPTGROUP_LABEL_INPUT' ); ?>">
                                <option  <?php echo ($this->data->type=='text')? 'selected="selected"':''?> value="text"><?php echo JText::_( 'J2STORE_TEXT' ); ?></option>
                   				<option  <?php echo ($this->data->type=='textarea')? 'selected="selected"':''?> value="textarea"><?php echo JText::_( 'J2STORE_TEXTAREA' ); ?></option>
                     	</optgroup>

                <optgroup label="<?php echo JText::_( 'J2STORE_OPTION_OPTGROUP_LABEL_DATE' ); ?>">
                                <option  <?php echo ($this->data->type=='date')? 'selected="selected"':''?> value="date"><?php echo JText::_( 'J2STORE_DATE' ); ?></option>
                				<option  <?php echo ($this->data->type=='time')? 'selected="selected"':''?> value="time"><?php echo JText::_( 'J2STORE_TIME' ); ?></option>
                                <option <?php echo ($this->data->type=='datetime')? 'selected="selected"':''?> value="datetime"><?php echo JText::_( 'J2STORE_DATETIME' ); ?></option>
				  </optgroup>
              </select>
			</td>
		</tr>

		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'J2STORE_OPTION_STATE' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
	</table>

</fieldset>
	<input type="hidden" name="option" value="com_j2store" />
	<input type="hidden" name="view" value="options" />
	<input type="hidden" name="option_id" value="<?php echo $this->data->option_id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>