<?php
/*------------------------------------------------------------------------
 # com_j2store - J2Store
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2013 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://j2store.org
# Technical Support:  Forum - http://j2store.org/forum/index.html
-------------------------------------------------------------------------*/


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

//JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
$action = JRoute::_('index.php?option=com_j2store&view=emailtemplates');

?>

<div class="j2store">
<h3><?php echo JText::_('J2STORE_EMAILTEMPLATES');?></h3>
<p class="muted"><?php echo JText::_('J2STORE_EMAILTEMPLATES_GENERAL_HELP'); ?></p>
<form action="<?php echo $action;?>" name="adminForm" class="adminForm" id="adminForm" method="post">

	<table class="adminlist table table-stripped" >
		<tr>
			<!-- search filter -->
			<td>
				<!-- search filter -->
			  <label for="filter_search" >
		  		<?php echo JText::_('J2STORE_FILTER_SEARCH');?> </label>
				<input type="text" name="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" id="search"/>
				<button class="btn btn-success" onclick="this.form.submit();"><?php echo JText::_( 'J2STORE_FILTER_GO' ); ?></button>
				<button class="btn btn-inverse" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'J2STORE_FILTER_RESET' ); ?></button>
			</td>
			<td>
			<!-- select for state -->
			  <label for="filter_published" >
		  		<?php echo JText::_('JSTATUS');?> </label>
					   <select name="filter_published" class="inputbox" onchange="this.form.submit()">
							<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
							<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
						</select>
			 </td>
		</tr>
	</table>

		   <table class="adminlist table table-striped">
			<thead>
				<th>
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="name">
					<?php echo JText::_('JFIELD_LANGUAGE_LABEL'); ?>
				</th>
				<th class="">
					<?php echo JText::_('J2STORE_EMAILTEMPLATE_SUBJECT_LABEL'); ?>
				</th>
				<th>
					<?php echo JText::_('JPUBLISHED'); ?>
				</th>
			</thead>

			<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $this->pagination->getListFooter(); ?>

				</td>
			</tr>
		</tfoot>

			<tbody>
				<?php
				 foreach ($this->items as $i => $item)

				  {
				 	  ?>
				<tr class="row<?php echo $i%2; ?>" sortable-group-id="1">
					 <td><?php echo JHtml::_('grid.id',$i,$item->emailtemplate_id); ?> </td>
					 <td>
					 	<?php if ($item->language == '*'):?>
							<?php echo JText::alt('JALL', 'language'); ?>
						<?php else:?>
							<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
						<?php endif;?>
					 </td>
					 <td>
					 <a href="index.php?option=com_j2store&view=emailtemplate&task=emailtemplate.edit&emailtemplate_id=<?php echo $item->emailtemplate_id; ?>">
					 <?php echo $item->subject; ?>
					 </a>
					 </td>
					<td>
					<?php echo JHtml::_('jgrid.published', $item->state, $i, 'emailtemplates.', 1, 'cb'); ?>
					</td>

				 <?php
				  } ?>

			</tbody>
		  </table>
		  <input type="hidden" name="option" value="com_j2store" />
		  <input type="hidden" name="view" value="emailtemplates" />
		 <input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
		</form>
</div>
