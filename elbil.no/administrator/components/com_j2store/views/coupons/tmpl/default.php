<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_COMPONENT_ADMINISTRATOR.'/library/prices.php' );
$action = JRoute::_('index.php?option=com_j2store&view=coupons');
$listOrder	= $this->lists['order'];
$listDirn	= $this->lists['order_Dir'];
$saveOrder	= $listOrder == 'a.coupon_id';
?>
<div class="j2store">
<h3><?php echo JText::_('J2STORE_COUPONS');?></h3>
<form action="<?php echo $action;?>" name="adminForm" class="adminForm" id="adminForm" method="post">
		<table class="table">
		<tr>
				<td align="left" width="100%">
				<?php echo JText::_( 'J2STORE_FILTER_SEARCH' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" />
				<button class="btn btn-success" onclick="this.form.submit();"><?php echo JText::_( 'J2STORE_FILTER_GO' ); ?></button>
				<button class="btn btn-inverse" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'J2STORE_FILTER_RESET' ); ?></button>
				</td>
			</tr>
		   </table>

		  <table id="couponsList" class="adminlist table table-striped">

			<thead>
			<tr>
			<th>#</th>
				<th width="1px">
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="name">
					<?php echo JHtml::_('grid.sort',  'J2STORE_COUPON_NAME', 'a.coupon_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="name">
					<?php echo JHtml::_('grid.sort',  'J2STORE_COUPON_CODE', 'a.coupon_code', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="name">
					<?php echo JHtml::_('grid.sort',  'J2STORE_COUPON_VALUE', 'a.value', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th class="name">
					<?php echo JHtml::_('grid.sort',  'J2STORE_COUPON_VALID_FROM', 'a.valid_from', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th class="name">
					<?php echo JHtml::_('grid.sort',  'J2STORE_COUPON_VALID_TO', 'a.valid_to', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th><?php echo JText::_('J2STORE_COUPON_EXPIRY'); ?></th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort',  'JPUBLISHED', 'a.state', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>

			<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php if($this->items) : ?>
				<?php foreach ($this->items as $i => $item): ?>
				<tr class="row<?php echo $i%2; ?>">
			 	<td><?php echo $i+1; ?></td>
				 <td> <?php echo JHtml::_('grid.id',$i,$item->coupon_id); ?> </td>
				 <td>
				  <a href="index.php?option=com_j2store&view=coupons&task=edit&cid[]=<?php echo $item->coupon_id; ?>">
				  <?php echo $item->coupon_name;?>
				  </a>
				  </td>
				  <td> <strong><?php echo $this->escape($item->coupon_code);?></strong>  </td>
				  <td> <?php
				  			if(strtoupper($item->value_type) == 'F') {
				  				$text = J2StorePrices::number($item->value);
				  			} else {
				  				$text = $item->value.'%';
				  			}
				  			echo $text;?>
				  	</td>
				  	<td>
				  	<?php
				  	echo JHTML::_('date', $item->valid_from, $this->params->get('date_format', JText::_('DATE_FORMAT_LC1')))
				  	?>
				  	 </td>
				  	<td>
				  	<?php
				  	echo JHTML::_('date', $item->valid_to, $this->params->get('date_format', JText::_('DATE_FORMAT_LC1')))
				  	?>
				  	 </td>
				  	<td>	<?php
							$now=JFactory::getDate();
							$end= strtotime($item->valid_to);
							$n=strtotime($now);
							$diff_in_days = round(abs($end-$n)/60/60/24);
							$diff = ($end-$n);
							if($diff<0)
								echo JText::_('J2STORE_COUPON_EXPIRED');
							else
								echo $diff_in_days.' '.JText::_('J2STORE_COUPON_DAYS');
				?>
				</td>

				    <td class="center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, '', 1, 'cb'); ?>
					</td>
			</tr>
			<?php endforeach;?>
			<?php else: ?>
			<tr><td colspan="9">
				<?php echo JText::_('J2STORE_NO_ITEMS_FOUND'); ?>
				</td>
				</tr>
			<?php endif;?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_j2store" />
		<input type="hidden" name="view" value="coupons" />
		 <input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
</form>
</div>