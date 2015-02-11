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

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_ADMINISTRATOR.'/components/com_j2store/library/plugins/payment.php');
require_once (JPATH_SITE.'/components/com_j2store/helpers/utilities.php');
class plgJ2StorePayment_paypal extends J2StorePaymentPlugin
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
    var $_element    = 'payment_paypal';
    var $_isLog      = false;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgJ2StorePayment_paypal(& $subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
	}


    /**
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _prePayment( $data )
    {
		// get component params
		$params = JComponentHelper::getParams('com_j2store');

        // prepare the payment form

        $vars = new JObject();
        $vars->order_id = $data['order_id'];
        $vars->orderpayment_id = $data['orderpayment_id'];
        $vars->orderpayment_amount = J2StoreUtilities::number( $data['orderpayment_amount'], array( 'thousands'=>'', 'num_decimals'=>'2', 'decimal'=>'.') );

        $vars->orderpayment_type = $this->_element;

        $vars->cart_session_id = JFactory::getSession()->getId();

        //get the order
        $order = JTable::getInstance('Orders', 'Table');
        $order->load($data['orderpayment_id']);

        $items = $order->getItems();

        //get the j2store version
        $xmlfile = JPATH_ADMINISTRATOR.'/components/com_j2store/manifest.xml';
        $xml = JFactory::getXML($xmlfile);
        $version=(string)$xml->version;

        foreach ($items as $item)
        {
        	$desc = $item->orderitem_name;

        	//sku
        	if (!empty($item->orderitem_sku))
        	{
        		$desc .= ' | '.JText::_('J2STORE_SKU').': '.$item->orderitem_sku;
        	}

        	//productoptions
        	if (!empty($item->orderitem_attribute_names)) {
        		//first convert from JSON to array
        		if( version_compare( $version, '2.5.0', 'lt' ) ) {
        			$desc .=' | '.$item->orderitem_attribute_names;
        		} else {
	        		$registry = new JRegistry;
	        		$registry->loadString($item->orderitem_attribute_names, 'JSON');
	        		$product_options = $registry->toObject();
	        		if(count($product_options) >0 ) {
	        			foreach ($product_options as $option) {
	        				$desc .=' | '.$option->name.':'.$option->value;
	        			}
        			}
        		}
        	}

        	$item->_description = $desc;
        }

        $vars->order = $order;
        $vars->orderitems = $items;

        // set payment plugin variables
        $vars->merchant_email = trim($this->_getParam( 'merchant_email' ));
        $vars->post_url = $this->_getPostUrl();
        $vars->return_url = JURI::root()."index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=".$this->_element."&paction=display_message";
        $vars->cancel_url = JURI::root()."index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=".$this->_element."&paction=cancel";
        $vars->notify_url = JURI::root()."index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=".$this->_element."&paction=process&tmpl=component";
        //$vars->currency_code = $this->_getParam( 'currency', 'USD' );
        $vars->currency_code = $params->get('currency_code');

        // set variables for user info
        $vars->first_name   = $data['orderinfo']['billing_first_name'];
        $vars->last_name    = $data['orderinfo']['billing_last_name'];
        $vars->email        = $data['orderinfo']['user_email'];
        $vars->address_1    = $data['orderinfo']['billing_address_1'];
        $vars->address_2    = $data['orderinfo']['billing_address_2'];
        $vars->city         = $data['orderinfo']['billing_city'];
        $vars->country      = $data['orderinfo']['billing_country_name'];
        $vars->region       = $data['orderinfo']['billing_zone_name'];
        $vars->postal_code  = $data['orderinfo']['billing_zip'];

        $html = $this->_getLayout('prepayment', $vars);
        return $html;
    }

    /**
     * Processes the payment form
     * and returns HTML to be displayed to the user
     * generally with a success/failed message
     *
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _postPayment( $data )
    {
        // Process the payment
        $paction = JRequest::getVar('paction');

        $vars = new JObject();

        switch ($paction)
        {
            case "display_message":
            	$session = JFactory::getSession();
            	$session->set('j2store_cart', array());
                $vars->message = JText::_('J2STORE_PAYPAL_MESSAGE_PAYMENT_ACCEPTED_FOR_VALIDATION');
                $html = $this->_getLayout('message', $vars);
                $html .= $this->_displayArticle();

              break;
            case "process":
                $vars->message = $this->_process();
                $html = $this->_getLayout('message', $vars);
                echo $html; // TODO Remove this
                $app = JFactory::getApplication();
                $app->close();
              break;
            case "cancel":
                $vars->message = JText::_( 'J2STORE_PAYPAL_MESSAGE_CANCEL' );
                $html = $this->_getLayout('message', $vars);
              break;
            default:
                $vars->message = JText::_( 'J2STORE_PAYPAL_MESSAGE_INVALID_ACTION' );
                $html = $this->_getLayout('message', $vars);
              break;
        }

        return $html;
    }

    /**
     * Prepares variables for the payment form
     *
     * @return unknown_type
     */
    function _renderForm( $data )
    {
        $user = JFactory::getUser();
        $vars = new JObject();

        $html = $this->_getLayout('form', $vars);

        return $html;
    }

    /************************************
     * Note to 3pd:
     *
     * The methods between here
     * and the next comment block are
     * specific to this payment plugin
     *
     ************************************/

    /**
     * Gets the Paypal gateway URL
     *
     * @param boolean $full
     * @return string
     * @access protected
     */
    function _getPostUrl($full = true)
    {
        $url = $this->params->get('sandbox') ? 'www.sandbox.paypal.com' : 'www.paypal.com';

        if ($full)
        {
            $url = 'https://' . $url . '/cgi-bin/webscr';
        }

        return $url;
    }


    /**
     * Gets the value for the Paypal variable
     *
     * @param string $name
     * @return string
     * @access protected
     */
    function _getParam( $name, $default='' )
    {
    	$return = $this->params->get($name, $default);

    	$sandbox_param = "sandbox_$name";
    	$sb_value = $this->params->get($sandbox_param);
        if ($this->params->get('sandbox') && !empty($sb_value))
        {
            $return = $this->params->get($sandbox_param, $default);
        }

        return $return;
    }

    /**
     * Validates the IPN data
     *
     * @param array $data
     * @return string Empty string if data is valid and an error message otherwise
     * @access protected
     */
    function _validateIPN( $data )
    {
        $secure_post = $this->params->get( 'secure_post', '0' );
        $paypal_url = $this->_getPostUrl(false);

        $req = 'cmd=_notify-validate';
        foreach ($data as $key => $value) {
            if ($key != 'view' && $key != 'layout') {
                $value = urlencode($value);
                $req .= "&$key=$value";
            }
        }

        // post back to PayPal system to validate
        $header  = "POST /cgi-bin/webscr HTTP/1.1\r\n";
        //$header .= "Host: " . $this->_getPostURL(false) . ":443\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .="Host: www.paypal.com\r\n";
        $header .="Connection: close\r\n\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

        if ($secure_post) {
            // If possible, securely post back to paypal using HTTPS
            // Your PHP server will need to be SSL enabled
            $fp = fsockopen ('ssl://' . $paypal_url , 443, $errno, $errstr, 30);
        }
        else {
            $fp = fsockopen ($paypal_url, 80, $errno, $errstr, 30);
        }

        if ( ! $fp) {
            return JText::sprintf('J2STORE_PAYPAL_ERROR_POSTING_IPN_DATA_BACK', $errstr, $errno);
        }
        else {
            fputs ($fp, $header . $req);
            while ( ! feof($fp)) {
                $res = fgets ($fp, 1024); //echo $res;
                if (strcmp ($res, 'VERIFIED') == 0) {
                    return '';
                }
                elseif (strcmp ($res, 'INVALID') == 0) {
                    return JText::_('J2STORE_PAYPAL_ERROR_IPN_VALIDATION');
                }
            }
        }

        fclose($fp);
        return '';
    }

	/**
	 *
	 * @return HTML
	 */
	function _process()
	{
		$data = JRequest::get('post');

		$error = '';

        // prepare some data

		$custom = $data['custom'];
		$custom_array = explode('|', $custom);

        $order_id  = $custom_array[0];
        $orderpayment_id    = (int) @$data['invoice'];

        // load the orderpayment record and set some values
        JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_j2store/tables' );
        $orderpayment = JTable::getInstance('Orders', 'Table');

        $orderpayment->load( $data['invoice'] );
        if(($orderpayment->id == $data['invoice']) && ($orderpayment->order_id == $order_id) ) {
        	// validate the IPN info
        	$error = $this->_validateIPN($data);
        	if (!empty($error))
        	{
        		// ipn Validation failed
        		$data['ipn_validation_results'] = $error;
        	}

        }

        $data['transaction_details'] = $this->_getFormattedTransactionDetails( $data );

        // process the payment based on its type
        if ( !empty($data['txn_type']) )
        {
            $payment_error = '';

            if ($data['txn_type'] == 'cart') {
            	// Payment received for multiple items; source is Express Checkout or the PayPal Shopping Cart.
            	$payment_error = $this->_processSale( $data, $error );
            }
            else {
                // other methods not supported right now
                $payment_error = JText::_( "J2STORE_PAYPAL_ERROR_INVALID_TRANSACTION_TYPE" ).": ".$data['txn_type'];
            }

            if ($payment_error) {
                // it seems like an error has occurred during the payment process
                $error .= $error ? "\n" . $payment_error : $payment_error;
            }
        }

        if ($error) {
            // send an emails to site's administrators with error messages
            $this->_sendErrorEmails($error, $data['transaction_details']);
            return $error;
        }


        // if here, all went well
        $error = 'processed';
		return $error;
	}

    /**
     * Processes the sale payment
     *
     * @param array $data IPN data
     * @return boolean Did the IPN Validate?
     * @access protected
     */
    function _processSale( $data, $ipnValidationFailed='' )
    {
        /*
         * validate the payment data
         */
        $errors = array();

        if (!empty($ipnValidationFailed))
        {
        	$errors[] = $ipnValidationFailed;
        }

        // is the recipient correct?
        if (empty($data['receiver_email']) || JString::strtolower($data['receiver_email']) != JString::strtolower(trim($this->_getParam( 'merchant_email' )))) {
            $errors[] = JText::_('J2STORE_PAYPAL_MESSAGE_RECEIVER_INVALID');
        }

        if (empty($data['invoice']))
        {
            $errors[] = JText::_('J2STORE_PAYPAL_MESSAGE_INVALID_ORDERPAYMENTID');
            return count($errors) ? implode("\n", $errors) : '';
        }

        $custom = $data['custom'];
        $custom_array = explode('|', $custom);

        $order_id  = $custom_array[0];

        //array 1 has the cart session key
        if(isset($custom_array[1])) {
        	$cart_session_id = $custom_array[1];
        } else {
        	$cart_session_id = '';
        }


        // load the orderpayment record and set some values
        JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_j2store/tables' );
        $orderpayment = JTable::getInstance('Orders', 'Table');

        $orderpayment->load( $data['invoice'] );
      	if(($orderpayment->id == $data['invoice']) && ($orderpayment->order_id == $order_id ) ) {
	        $orderpayment->transaction_details  = $data['transaction_details'];
	        $orderpayment->transaction_id       = $data['txn_id'];
	        $orderpayment->transaction_status   = $data['payment_status'];

	        // check the stored amount against the payment amount

	        $stored_amount = J2StoreUtilities::number( $orderpayment->get('orderpayment_amount'), array( 'thousands'=>'', 'num_decimals'=>'2', 'decimal'=>'.' ) );
	        if ((float) $stored_amount !== (float) $data['mc_gross']) {
	          $errors[] = JText::_('J2STORE_PAYPAL_MESSAGE_AMOUNT_INVALID');
	        }

	        // check the payment status
	        if (empty($data['payment_status']) || ($data['payment_status'] != 'Completed' && $data['payment_status'] != 'Pending')) {
	            $errors[] = JText::sprintf('J2STORE_PAYPAL_MESSAGE_STATUS_INVALID', @$data['payment_status']);
	        }

	        //set a default status to it
	        $orderpayment->order_state = JText::_('J2STORE_PENDING'); // PENDING
	        $orderpayment->order_state_id = 4; // PENDING

	        // set the order's new status
	        if (count($errors))
	        {
	            // if an error occurred
	            $orderpayment->order_state = JText::_('J2STORE_FAILED'); // FAILED
	            $orderpayment->order_state_id = 3; //FAILED
	        }
	            elseif (@$data['payment_status'] == 'Pending')
	        {
	            // if the transaction has the "pending" status,
	            $orderpayment->order_state = JText::_('J2STORE_PENDING'); // PENDING
	            $orderpayment->order_state_id = 4; //PENDING

	        }
	            else
	        {
	            $orderpayment->order_state = trim(JText::_('J2STORE_CONFIRMED')); // CONFIRMED
	            $orderpayment->order_state_id = 1; //CONFIRMED
	            $orderpayment->paypal_status = @$data['payment_status']; // Paypal's original status
	            JLoader::register( 'J2StoreHelperCart', JPATH_SITE.'/components/com_j2store/helpers/cart.php');
	            // remove items from cart
	           // J2StoreHelperCart::removeOrderItems( $orderpayment->id );
	           if(!empty($cart_session_id)) {
	           		//load session with id
	           		$options = array('id'=>$cart_session_id);
	           		$session = JFactory::getSession($options);
	           		$session->set('j2store_cart', array());

	           }

	        }

	        // save the orderpayment
	        if($orderpayment->save())
	        {

			} else {
	        	$errors[] = $orderpayment->getError();
	        }

	        // let us inform the user that the payment is successful
	        require_once (JPATH_SITE.'/components/com_j2store/helpers/orders.php');
	        J2StoreOrdersHelper::sendUserEmail($orderpayment->user_id, $orderpayment->order_id, $data['payment_status'], $orderpayment->order_state, $orderpayment->order_state_id);
      	}

        return count($errors) ? implode("\n", $errors) : '';
    }

    /**
     * Formatts the payment data for storing
     *
     * @param array $data
     * @return string
     */
    function _getFormattedTransactionDetails( $data )
    {
        $separator = "\n";
        $formatted = array();

        foreach ($data as $key => $value)
        {
            if ($key != 'view' && $key != 'layout')
            {
                $formatted[] = $key . ' = ' . $value;
            }
        }

        return count($formatted) ? implode("\n", $formatted) : '';
    }

    /**
     * Simple logger
     *
     * @param string $text
     * @param string $type
     * @return void
     */
    function _log($text, $type = 'message')
    {
    	if ($this->_isLog) {
    		$file = JPATH_ROOT . "/cache/{$this->_element}.log";
    		$date = JFactory::getDate();

    		$f = fopen($file, 'a');
    		fwrite($f, "\n\n" . $date->format('Y-m-d H:i:s'));
    		fwrite($f, "\n" . $type . ': ' . $text);
    		fclose($f);
    	}
    }

    /**
     * Sends error messages to site administrators
     *
     * @param string $message
     * @param string $paymentData
     * @return boolean
     * @access protected
     */
    function _sendErrorEmails($message, $paymentData)
    {
        $mainframe = JFactory::getApplication();

        // grab config settings for sender name and email
        $config     = JComponentHelper::getParams('com_j2store');
        $mailfrom   = $config->get( 'emails_defaultemail', $mainframe->getCfg('mailfrom') );
        $fromname   = $config->get( 'emails_defaultname', $mainframe->getCfg('fromname') );
        $sitename   = $config->get( 'sitename', $mainframe->getCfg('sitename') );
        $siteurl    = $config->get( 'siteurl', JURI::root() );

        $recipients = $this->_getAdmins();
        $mailer = JFactory::getMailer();

        $subject = JText::sprintf('J2STORE_PAYPAL_EMAIL_PAYMENT_NOT_VALIDATED_SUBJECT', $sitename);

        foreach ($recipients as $recipient)
        {
            $mailer = JFactory::getMailer();
            $mailer->addRecipient( $recipient->email );

            $mailer->setSubject( $subject );
            $mailer->setBody( JText::sprintf('J2STORE_PAYPAL_EMAIL_PAYMENT_FAILED_BODY', $recipient->name, $sitename, $siteurl, $message, $paymentData) );
            $mailer->setSender(array( $mailfrom, $fromname ));
            $sent = $mailer->send();
        }

        return true;
    }

    /**
     * Gets admins data
     *
     * @return array|boolean
     * @access protected
     */
    function _getAdmins()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('u.name,u.email');
        $query->from('#__users AS u');
        $query->join('LEFT', '#__user_usergroup_map AS ug ON u.id=ug.user_id');
        $query->where('u.sendEmail = 1');
        $query->where('ug.group_id = 8');

        $db->setQuery($query);
        $admins = $db->loadObjectList();
        if ($error = $db->getErrorMsg()) {
            JError::raiseError(500, $error);
            return false;
        }

        return $admins;
    }


}

      /* TYPICAL RESPONSE FROM PAYPAL INCLUDES:
       * mc_gross=49.99
       * &protection_eligibility=Eligible
       * &address_status=confirmed
       * &payer_id=Q5HTJ93G8FQKC
       * &tax=0.00
       * &address_street=10101+Some+Street
       * &payment_date=12%3A13%3A19+Dec+05%2C+2008+PST
       * &payment_status=Completed
       * &charset=windows-1252
       * &address_zip=11259
       * &first_name=John
       * &mc_fee=1.75
       * &address_country_code=US
       * &address_name=John+Doe
       * &custom=some+custom+value
       * &payer_status=verified
       * &business=receiver%40domain.com
       * &address_country=United+States
       * &address_city=Some+City
       * &quantity=1
       * &payer_email=sender%40emaildomain.com
       * &txn_id=3JK16594EX581780W
       * &payment_type=instant
       * &payer_business_name=John+Doe
       * &last_name=Doe
       * &address_state=CA
       * &receiver_email=receiver%40domain.com
       * &payment_fee=1.75
       * &receiver_id=YG9UDRP6DE45G
       * &txn_type=web_accept
       * &item_name=Name+of+item
       * &mc_currency=USD
       * &item_number=Number+of+Item
       * &residence_country=US
       * &handling_amount=0.00
       * &transaction_subject=Subject+of+Transaction
       * &payment_gross=49.99
       * &shipping=0.00
       * &=
      */

    /**
     * VALID PAYMENT_STATUS VALUES returned from Paypal
     *
     * Canceled_Reversal: A reversal has been canceled. For example, you won a dispute with the customer, and the funds for the transaction that was reversed have been returned to you.
     * Completed: The payment has been completed, and the funds have been added successfully to your account balance.
     * Created: A German ELV payment is made using Express Checkout.
     * Denied: You denied the payment. This happens only if the payment was previously pending because of possible reasons described for the pending_reason variable or the Fraud_Management_Filters_x variable.
     * Expired: This authorization has expired and cannot be captured.
     * Failed: The payment has failed. This happens only if the payment was made from your customer�s bank account.
     * Pending: The payment is pending. See pending_reason for more information.
     * Refunded: You refunded the payment.
     * Reversed: A payment was reversed due to a chargeback or other type of reversal. The funds have been removed from your account balance and returned to the buyer. The reason for the reversal is specified in the ReasonCode element.
     * Processed: A payment has been accepted.
     * Voided: This authorization has been voided.
    */
