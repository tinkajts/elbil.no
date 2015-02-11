<?php
/*
 * --------------------------------------------------------------------------------
   Weblogicx India  - J2 Store v 2.0
 * --------------------------------------------------------------------------------
 * @package		Joomla! 1.5x
 * @subpackage	J2 Store
 * @author    	Weblogicx India http://www.weblogicxindia.com
 * @copyright	Copyright (c) 2010 - 2015 Weblogicx India Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link		http://weblogicxindia.com
 * --------------------------------------------------------------------------------
*/

defined('_JEXEC') or die('Restricted access'); ?>

<?php echo JText::_( 'J2STORE_PAYPAL_PAYMENT_STANDARD_PREPARATION_MESSAGE' ); ?>

<form action='<?php echo $vars->post_url; ?>' method='post'>

<!--USER INFO-->
    <input type='hidden' name='first_name' value='<?php echo $vars->first_name; ?>'>
    <input type='hidden' name='last_name' value='<?php echo $vars->last_name; ?>'>
    <input type='hidden' name='email' value='<?php echo $vars->email; ?>'>

<!--SHIPPING ADDRESS PROVIDED-->
    <input type='hidden' name='address1' value='<?php echo $vars->address_1; ?>'>
    <input type='hidden' name='address2' value='<?php echo $vars->address_2; ?>'>
    <input type='hidden' name='city' value='<?php echo $vars->city; ?>'>
    <input type='hidden' name='country' value='<?php echo $vars->country; ?>'>
    <input type='hidden' name='state' value='<?php echo $vars->region; ?>'>
    <input type='hidden' name='zip' value='<?php echo $vars->postal_code; ?>'>

<!--CART INFO AGGREGATED-->
    <input type='hidden' name='custom' value='<?php echo $vars->order_id.'|'.$vars->cart_session_id; ?>'>
    <!-- IPN-PDT  ONLY -->
    <input type='hidden' name='invoice' value='<?php echo $vars->orderpayment_id; ?>'>

<!--CART INFO ITEMISED-->
    <?php
 	$i =1;
    foreach ($vars->orderitems as $item):
    ?>
 	   <input type='hidden' name='amount_<?php echo $i;?>' value='<?php echo J2StoreUtilities::number( $item->orderitem_final_price / @$item->orderitem_quantity, array( 'thousands' =>'', 'decimal'=> '.' ) ); ?>'>
    	<input type='hidden' name='item_name_<?php echo $i;?>' value='<?php echo $item->_description;?>'>
        <input type='hidden' name='item_number_<?php echo $i;?>' value='<?php echo $item->product_id; ?>'>
        <input type='hidden' name='quantity_<?php echo $i;?>' value='<?php echo $item->orderitem_quantity; ?>'>

    <?php
     $i++;
    endforeach;
    ?>

     <input type='hidden' name='tax_cart' value='<?php echo $vars->order->order_tax; ?>'>
     <input type='hidden' name='handling_cart' value='<?php echo $vars->order->order_shipping + $vars->order->order_shipping_tax; ?>'>
     <input type='hidden' name='discount_amount_cart' value='<?php echo $vars->order->order_discount;?>'>

<!--PAYPAL VARIABLES-->
	<input type='hidden' name='cmd' value='_cart'>
	<input type='hidden' name='rm' value='2'>
	<input type="hidden" name="business" value="<?php echo $vars->merchant_email; ?>" />
	<input type='hidden' name='return' value='<?php echo JRoute::_( $vars->return_url ); ?>'>
	<input type='hidden' name='cancel_return' value='<?php echo JRoute::_( $vars->cancel_url ); ?>'>
	<input type="hidden" name="notify_url" value="<?php echo JRoute::_( $vars->notify_url ); ?>" />
	<input type='hidden' name='currency_code' value='<?php echo $vars->currency_code; ?>'>
	<input type='hidden' name='no_note' value='1'>
	<input type="hidden" name="upload" value="1">
	<input type="hidden" name=”charset” value=”utf-8”>

<!-- payment screen style variables -->
	<?php if($cbt = $this->_getParam('cbt','')): ?>
	<input type="hidden" name="cbt" value="<?php echo $cbt ?>" />
	<?php endif; ?>
	<?php if($cpp_header_image = $this->_getParam('cpp_header_image','')): ?>
	<input type="hidden" name="cpp_header_image" value="<?php echo $cpp_header_image?>" />
	<?php endif; ?>
	<?php if($image_url = $this->_getParam('image_url','')): ?>
	<input type="hidden" name="image_url" value="<?php echo $image_url?>" />
	<?php endif; ?>
	<?php if($cpp_headerback_color = $this->_getParam('cpp_headerback_color','')): ?>
	<input type="hidden" name="cpp_headerback_color" value="<?php echo $cpp_headerback_color?>" />
	<?php endif; ?>
	<?php if($cpp_headerborder_color = $this->_getParam('cpp_headerborder_color','')): ?>
	<input type="hidden" name="cpp_headerborder_color" value="<?php echo $cpp_headerborder_color?>" />
	<?php endif; ?>

	<input class="btn btn-primary" type="submit" value="<?php echo JText::_('J2STORE_PAYPAL_PAY_NOW');?>" />
</form>
