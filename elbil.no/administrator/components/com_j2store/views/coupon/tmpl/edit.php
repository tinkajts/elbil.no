<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$action=JRoute::_('index.php?option=com_j2store&view=coupons');
?>
<script>
Joomla.submitbutton = function(pressbutton){
		if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
		}
		if (J2Store.trim(J2Store('#coupon_name').val()) == '') {
			alert( '<?php echo JText::_('J2STORE_COUPON_NAME_REQUIRED', true); ?>' );
		} else if(J2Store.trim(J2Store('#coupon_code').val()) == '') {
			alert( '<?php echo JText::_('J2STORE_COUPON_CODE_REQUIRED', true); ?>' );
		}else {
			submitform( pressbutton );

	}
}
</script>
<div class="j2store j2store-coupons">
			<form name="adminForm" id="adminForm" method="post"
				class="form-validate" enctype="multipart/form-data"
				action="<?php echo $action; ?>" >
				<fieldset>
					<legend><?php echo JText::_('J2STORE_ADD_COUPON');?> </legend>

						<table class="table">
							<tr><td>
								<label for="coupon_name"><?php echo JText::_('J2STORE_COUPON_NAME');?>
								</label>
								</td><td>
								<input type="text" id="coupon_name" name="coupon_name" value="<?php echo $this->item->coupon_name;?>"/>
							</td></tr>

							<tr><td>
								<label for="coupon_code"><?php echo JText::_('J2STORE_COUPON_CODE');?>
								</label>
								</td><td>
								<input type="text" id="coupon_code"  name="coupon_code" value="<?php echo $this->item->coupon_code;?>"/>
								<small><?php echo JText::_('J2STORE_COUPON_CODE_HELP_TEXT'); ?></small>
							</td></tr>

								<tr><td>
									<label for="state"> <?php echo JText::_('J2STORE_PUBLISH');?>
									</label></td>
								<td>
								<?php echo $this->lists['published']; ?>
								</td></tr>

							<tr><td>
								<label for="value"><?php echo JText::_('J2STORE_COUPON_VALUE');?>
								</label>
								</td><td>
								<input type="text" name="value" value="<?php echo $this->item->value;?>"/>
							</td></tr>

							<tr><td>
									<label for="value_type"> <?php echo JText::_('J2STORE_COUPON_VALUE_TYPE');?>
									</label></td>
								<td>
									<?php echo $this->lists['value_type']; ?>
								</td></tr>
							<tr>
							<tr><td>
									<label for="logged"> <?php echo JText::_('J2STORE_COUPON_LOGGED');?>
									</label></td>
								<td>
									<?php echo $this->lists['logged']; ?>
									<small><?php echo JText::_('J2STORE_COUPON_LOGGED_HELP_TEXT');?></small>
								</td></tr>
							<tr>


							<td>
								<label for="valid_from"><?php echo JText::_('J2STORE_COUPON_VALID_FROM');?>
								</label>
							</td>
							<td>
								<?php echo JHTML::_('calendar', $this->item->valid_from, 'valid_from', 'valid_from',$format= '%Y-%m-%d %H:%M:%S'); ?>
								<small>(<?php echo '  Format   [ YYYY-MM-DD HH:MM:SS ]'; ?>)</small>
							</td>
							</tr>
							<tr>
							<td>
								<label for="valid_to"><?php echo JText::_('J2STORE_COUPON_VALID_TO');?>

								</label>
							</td><td>
								<?php echo JHTML::_('calendar', $this->item->valid_to, 'valid_to', 'valid_to',$format= '%Y-%m-%d %H:%M:%S'); ?>
								<small>(<?php echo '  Format   [ YYYY-MM-DD HH:MM:SS ]'; ?>)</small>
							<br/>
							</td>
							</tr>
							<tr><td>
								<label for="max_uses"><?php echo JText::_('J2STORE_COUPON_MAXIMUM_USES');?>
								</label>
								</td>
								<td>
								<input type="text" name="max_uses" value="<?php echo $this->item->max_uses;?>"/>
								<small><?php echo JText::_('J2STORE_COUPON_MAXIMUM_USES_HELP_TEXT');?></small>

							</td>
							</tr>

							<tr><td>
								<label for="max_customer_uses"><?php echo JText::_('J2STORE_COUPON_MAXIMUM_CUSTOMER_USES');?>
								</label>
								</td>
								<td>
								<input type="text" name="max_customer_uses" value="<?php echo $this->item->max_customer_uses;?>"/>
								<small><?php echo JText::_('J2STORE_COUPON_MAXIMUM_CUSTOMER_USES_HELP_TEXT');?></small>

							</td>
							</tr>

							<tr><td>
								<label for="product_category"><?php echo JText::_('J2STORE_COUPON_PRODUCT_CATEGORY');?>
								</label>
								</td>
								<td>
								<input type="text" name="product_category" value="<?php echo $this->item->product_category;?>"/>
								<small><?php echo JText::_('J2STORE_COUPON_PRODUCT_CATEGORY_HELP_TEXT');?></small>

							</td>
							</tr>
						</table>
					</fieldset>
				<input type="hidden" name="coupon_id" value="<?php echo $this->item->coupon_id;?>" />
				<input type="hidden" name="option" value="com_j2store" />
				<input type="hidden" name="view" value="coupons" />
				<input type="hidden" name="task" value="" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
		</div>