<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Software (http://extstore.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die();

?>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo JText::_('COM_SL_ADVPOLL_DASHBOARD_POLL_NAME'); ?></th>
			<th><?php echo JText::_('COM_SL_ADVPOLL_DASHBOARD_POLL_DATE_VOTED'); ?></th>
		</tr>
	</thead>
<?php if (count($this->last_voted_items)) : ?>
	<tbody>
	<?php foreach ($this->last_voted_items as $i => $item) : ?>
		<tr>
			<th>
				<a href="<?php echo JRoute::_('index.php?option=com_sl_advpoll&view=poll&layout=edit&id=' . $item->id); ?>">
				<?php
					echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8');
				 ?>
				</a>
			</th>
			<td>
				<?php echo $item->date_voted ? JHtml::_('date',$item->date_voted, 'Y-m-d H:i:s') : JText::_('COM_SL_ADVPOLL_DASHBOARD_POLL_DATE_VOTED_NERVER'); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
<?php else : ?>
	<tbody>
		<tr>
			<td colspan="2">
				<p class="noresults"><?php echo JText::_('COM_SL_ADVPOLL_DASHBOARD_NO_POLL');?></p>
			</td>
		</tr>
	</tbody>
<?php endif; ?>
</table>