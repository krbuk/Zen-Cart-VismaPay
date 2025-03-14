<?php
/**
 * www.visma.fi (Visma Oy)
 *
 * REQUIRES PHP 7.4
 * 
 * @package VismaPay
 * @copyright Copyright 2003-2019 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Nida Verkkopalvelu (www.nida.fi) / krbuk 2024 Dec 1 Modified in module 2.7.0
 */
require DIR_FS_CATALOG .DIR_WS_MODULES . 'payment/vismapay/lib/visma_pay_loader.php';

  class vismapay { 
    /**
     * $_check is used to check the configuration key set up
     * @var int
     */
    protected $_check;
    /**
     * $code determines the internal 'code' name used to designate "this" payment module
     * @var string
     */
    public $code;
    /**
     * $description is a soft name for this payment method
     * @var string 
     */
    public $description;
    /**
     * $enabled determines whether this module shows or not... during checkout.
     * @var boolean
     */
    public $enabled;
    /**
     * $order_status is the order status to set after processing the payment
     * @var int
     */
    public $order_status;
    /**
     * $title is the displayed name for this order total method
     * @var string
     */
    public $title;
    /**
     * $sort_order is the order priority of this payment module when displayed
     * @var int
     */
    public $sort_order;
    /**
     * $api_key is the merchant api key
     * @var int
     */	  
	public $api_key;
    /**
     * $private_key is the merchant private key
     * @var int
     */	 	  
	public $private_key;
    /**
     * $return_address 
     * @var int
     */	  
	public $return_address;
    /**
     * $return_address 
     * @var int
     */		  
	public $cancel_address; 
    /**
     * $currency is the valid VismaPay currency to use default EUR
     * @var string
     */	  
    public $currency;	
	public $language;
	public $banks;
	public $wallets;
	public $ccards;
	public $cinvoices;
	public $laskuyritykselle;
	public $send_receipt;
	public $send_items;
	public $ordernumber_prefix;
	public $embed;
	public $payment_description; 
	public $order_number;
    /**
    * $form_action_url is the URL to process the payment or not set for local processing
    * @var string
    */  
	public $form_action_url ;
    /**
     * $moduleVersion is the version of this module
     * @var string
     */	  
	public $moduleVersion = '2.7.0';
    /**
     * $allowed_currencies is allowed only EUR
     * @var string
     */	  
	private $allowed_currencies = array('EUR'); 
    /**
     * Platform name for the API.
     * @var string
     */
    protected $VismaPayApiVersion = 'w3.2';	  

// class constructor
	function __construct()	
	{
        global $order;	
		$this->code = 'vismapay';
		$this->title = defined('MODULE_PAYMENT_VISMAPAY_TEXT_TITLE') ? MODULE_PAYMENT_VISMAPAY_TEXT_TITLE : null;	
		$this->description = '<strong>Visma Pay Payment API -v ' .$this->VismaPayApiVersion  .' integration for <br> Zen-Cart Module -v '.$this->moduleVersion .'</strong><br><br>' .MODULE_PAYMENT_VISMAPAY_TEXT_DESCRIPTION;
		$this->enabled  = (defined('MODULE_PAYMENT_VISMAPAY_STATUS') && MODULE_PAYMENT_VISMAPAY_STATUS == 'Kyllä') ? true : false;
		$this->sort_order = defined('MODULE_PAYMENT_VISMAPAY_SORT_ORDER') ? MODULE_PAYMENT_VISMAPAY_SORT_ORDER : null;
		$this->api_key = defined('MODULE_PAYMENT_VISMAPAY_VP_API_KEY') ? MODULE_PAYMENT_VISMAPAY_VP_API_KEY : null;
		$this->private_key = defined('MODULE_PAYMENT_VISMAPAY_VP_PRIVATE_KEY') ? MODULE_PAYMENT_VISMAPAY_VP_PRIVATE_KEY : null;
		$this->return_address = zen_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
		$this->cancel_address = zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL');
		$this->language = ($_SESSION['languages_code'] == 'fi') ? 'fi' : 'en';
		$this->order_status = defined('MODULE_PAYMENT_VISMAPAY_ORDER_STATUS_ID_SETTLED') ? MODULE_PAYMENT_VISMAPAY_ORDER_STATUS_ID_SETTLED : null;	
		$this->banks = defined('MODULE_PAYMENT_VISMAPAY_BANKS') ? MODULE_PAYMENT_VISMAPAY_BANKS : null;
		$this->wallets = defined('MODULE_PAYMENT_VISMAPAY_WALLETS') ? MODULE_PAYMENT_VISMAPAY_WALLETS : null;
		$this->ccards = defined('MODULE_PAYMENT_VISMAPAY_CCARDS') ? MODULE_PAYMENT_VISMAPAY_CCARDS : null;
		$this->cinvoices = defined('MODULE_PAYMENT_VISMAPAY_CINVOICES') ? MODULE_PAYMENT_VISMAPAY_CINVOICES : null;
		$this->laskuyritykselle = defined('MODULE_PAYMENT_VISMAPAY_LASKUYRITYKSELLE') ? MODULE_PAYMENT_VISMAPAY_LASKUYRITYKSELLE : null;		
		$this->send_receipt = defined('MODULE_PAYMENT_VISMAPAY_SEND_CONFIRMATION') ? MODULE_PAYMENT_VISMAPAY_SEND_CONFIRMATION : null;
		$this->send_items = defined('MODULE_PAYMENT_VISMAPAY_SEND_ITEMS') ? MODULE_PAYMENT_VISMAPAY_SEND_ITEMS : null;
		$this->ordernumber_prefix = defined('MODULE_PAYMENT_VISMAPAY_ORDERNUMBER_PREFIX') ? MODULE_PAYMENT_VISMAPAY_ORDERNUMBER_PREFIX : null;
		$this->embed = defined('MODULE_PAYMENT_VISMAPAY_EMBEDDED') ? MODULE_PAYMENT_VISMAPAY_EMBEDDED : null;
		$this->payment_description = MODULE_PAYMENT_PAYMENT_DESCRIPTION;
		$this->allowed_currencies = array('EUR');		
		
		if (null === $this->sort_order) return false;	
		if (IS_ADMIN_FLAG === true && (MODULE_PAYMENT_VISMAPAY_VP_API_KEY == 'TESTAPIKEY')) $this->title .= '<span class="alert">' .MODULE_PAYMENT_VISMAPAY_ALERT_TEST .'</span>';
			
		 // determine order-status for transactions
		if ((int)MODULE_PAYMENT_VISMAPAY_ORDER_STATUS_ID_SETTLED > 0)
		{
			$this->order_status = MODULE_PAYMENT_VISMAPAY_ORDER_STATUS_ID_SETTLED;
		}
        // check for zone compliance and any other conditionals
		if(is_object($order)) $this->update_status();		
 
	}// end function __construct
	
    function update_status() {
      global $order, $db;
	  // disable the module if the order only contains euro
      if ($this->enabled == true) 
      {
        //Only EUR orders accepted
		$this->currency = $order->info['currency'];  
        if(!(in_array($this->currency, $this->allowed_currencies))) $this->enabled = false;
      }

      // other status checks?
      if ($this->enabled) {
        // other checks here
      }
    }
	
	function javascript_validation()
	{
		
	}// end	function javascript_validation

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

	function pre_confirmation_check()
	{
		return false;
	}// end function pre_confirmation_check

	function confirmation()
	{
		return false;
	}// end unction confirmation
	
	function check()
	{
		global $db;
		if (!isset($this->_check))
		{
			$check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_VISMAPAY_STATUS'");
			$this->_check = $check_query->RecordCount();
		}
		return $this->_check;
	}// end function check
	
	function process_button()
	{
		global $order, $currencies, $db, $order_totals;
		// Order amount
		$amount = number_format($order->info['total'], 2, '.', '')*100;
		
		//Create a randomized order number and order stamp
		$number_rand = time().rand(1,9999);
		$generate_order_number  = str_pad($number_rand, 15, "7", STR_PAD_LEFT);
		$prefix = $this->ordernumber_prefix;
		if(!empty($prefix)){
			$order_number = $this->ordernumber_prefix.'_'.$generate_order_number;
		}	
		else
			{
				$order_number = $generate_order_number;		
			}	
		$this->order_number = $order_number;	
		
		$finn_langs 	= array('fi-FI', 'fi', 'fi_FI');
		$sv_langs 		= array('sv-SE', 'sv', 'sv_SE');
		$current_locale = $this->language;
		
		if(in_array($current_locale, $finn_langs))
		{
			$lang = 'fi';
		}	
			else if (in_array($current_locale, $sv_langs))
			{
				$lang = 'sv';
			}
		else
			{	
				$lang = 'en';
			}
		// ********************************
		// Visma Pay Gatway
		// ********************************			
		$authcode = strtoupper(hash_hmac('sha256', $order_number, $this->private_key));
		$client = new VismaPay\VismaPay($this->api_key, $this->private_key);
		
		if($this->send_receipt == 'Enabled') 
		{
			$email = htmlspecialchars($order->customer['email_address']);
		}	
		else 
			{
				$email = null;
			}
		
		$client->addCharge(array(
			'order_number' => $order_number,
			'amount' => $amount, 
			'currency' => $this->currency,
			'email' => $email
			)
		);
		
		if (!isset($order->delivery['country']['iso_code_2'])) 
		{
			$order->delivery['street_address'] = $order->billing['street_address'];
			$order->delivery['postcode'] = $order->billing['postcode'];
			$order->delivery['city'] = $order->delivery['city'];
			$order->delivery['state'] = $order->delivery['state'];
			$order->delivery['country']['iso_code_2'] = $order->billing['country']['iso_code_2'];
		}
		
		$client->addCustomer(array(
			'firstname' => htmlspecialchars($order->customer['firstname']), 
			'lastname' => htmlspecialchars($order->customer['lastname']), 
			'email' => htmlspecialchars($order->customer['email_address']), 
			'address_street' => htmlspecialchars(substr($order->billing['street_address'],0,50)),
			'address_city' => htmlspecialchars(substr($order->billing['city'],0,18)),
			'address_zip' => htmlspecialchars(substr($order->billing['postcode'],0,5)),
			'address_country' => htmlspecialchars($order->billing['country']['iso_code_2']),
			'shipping_firstname' =>  htmlspecialchars($order->customer['firstname']),
			'shipping_lastname' => htmlspecialchars($order->customer['lastname']),
			'shipping_email' => htmlspecialchars($order->customer['email_address']),
			'shipping_address_street' => htmlspecialchars(substr($order->delivery['street_address'],0,50)),
			'shipping_address_city' => htmlspecialchars(substr($order->delivery['city'],0,18)),
			'shipping_address_zip' => htmlspecialchars(substr($order->delivery['postcode'],0,5)),
			'shipping_address_country' => htmlspecialchars($order->delivery['country']['iso_code_2']),
			)
		);	
		
		//Add product breakdown
		$decimals = $currencies->get_decimal_places($_SESSION['currency']);
		$order_subtotal  = zen_round($order->info['subtotal'], 2);
		
		//Variable to compare product calculation to total amount of the order		
		$total_check	= 0;
		$itemqyt		= 0;

		// Array order items, tax  and price
        $products = array();
		
		//Add products to product breakdown
		$order_items = $order->products;
		
        foreach ($order_items as $key => $item) 
		{
			$item_final_price = number_format($item['final_price'], 2, '.', '')*100;	
			//$item_final_price = $item['final_price'] *100 ;
			$item_tax = $item['tax'];
			$item_price = round($item_final_price * ($item_tax/100+1));		
			$itemqyt   += $item['qty'];

            if ($order_subtotal == 0) {
                array_push($products, array(
                    'title' 		=> $item['name'],
                    'id' 			=> $item['id'],
                    'count' 		=> floatval($item['qty']),
                    'pretax_price'	=> 0,
                    'tax' 			=> 0,
					'price' 		=> 0,
                    'type' 			=> 1,
                ));
				$total_check  +=  $item_price * $item['qty'];
            } 
			else 
				{
					array_push($products, array(
						'title' 		=> $item['name'],
						'id' 			=> $item['id'],
						'count'			=> floatval($item['qty']),
						'pretax_price'	=> intval($item_final_price),
						'tax' 			=> $item_tax,
						'price' 		=> intval($item_price),
						'type' 			=> 1,
					));
					$total_check  +=  $item_price * $item['qty'];
            	}
        }
		
	  //Add shipping to product breakdown
		$shipping_price 		= number_format($order->info['shipping_cost'], 2, '.', '')*100;
		$shipping_tax_total 	= number_format($order->info['shipping_tax'], 2, '.', '')*100;
		$shipping_pretax_price	= $shipping_price - $shipping_tax_total;	

	  if (($shipping_price - $shipping_tax_total) != 0) 
	  {
		  $shipping_tax	  = ($shipping_tax_total / ($shipping_price - $shipping_tax_total)) * 100;
		  $shipping_qty   = 1; 
	  } 
		else {
				// Handle the case where the denominator is zero
				$shipping_tax = 0; // or another appropriate value or error handling
		  		$shipping_qty = 1;
	  		 }			
		
	  if (DISPLAY_PRICE_WITH_TAX == 'true') 
	  {
		$shipping_price = $shipping_price;
	  } 
	  else 
		  {
			$shipping_price = $shipping_price + $shipping_tax_total;
		  }
		  $shipping_tax = number_format($shipping_tax, 1, '.', ''); 
           array_push($products, array(
                'title' 		=> $order->info['shipping_method'], 
                'id' 			=> $order->info['shipping_module_code'],
                'count' 		=> $shipping_qty,
                'price' 		=> intval($shipping_price),
                'tax' 			=> $shipping_tax,
                'pretax_price'	=> intval($shipping_pretax_price),
                'type' 			=> 2,
            ));	
		$total_check += $shipping_price; 		
		
		// Add loworderfee breakdown
		// Check if there is a group discount enabled
		foreach ($order_totals as $o_total)
		{
			if(isset($o_total['code']) && $o_total['code'] == 'ot_loworderfee')
			{
				if(isset($o_total['value']) && $o_total['value'] > 0)
				{
					$query = "select * from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS'";
					$loworder_tax = $db->Execute($query);
					$loworder_tax_rate = zen_get_tax_rate($loworder_tax->fields['configuration_value'], $order->billing['country']['id'], $order->billing['zone_id']);
					$loworder_price_format = number_format($o_total['value'], 2, '.', '') * 100;
					if (DISPLAY_PRICE_WITH_TAX == 'true')
					{
						$loworderpretax_price = $loworder_price_format;
					}
					else {
						$loworderpretax_price =  ($loworder_price_format / ($loworder_tax_rate/100+1)) * 100;
					}
					array_push($products, array(
						'title' 		=> MODULE_PAYMENT_VISMAPAY_LOWORDER_TEXT,
						'id' 			=> '',
						'count' 		=> 1,
						'price' 		=> $loworderpretax_price,
						'tax' 			=> $loworder_tax_rate,
						'pretax_price'	=> $loworderpretax_price,
						'type' 			=> 1,
					));
					$total_check += $loworderpretax_price;

				}
			}
		if(isset($o_total['code']) && $o_total['code'] == 'ot_subtotal')
			{
				if(isset($o_total['value']) && $o_total['value'] > 0)
				{
					$order_total_sub_total = $o_total['value'];
				}
			}			
			
		//Add group discount pricing breakdown
		if($o_total['code'] == 'ot_group_pricing')
			{
				if($o_total['value'] > 0)
				{
					$group_amount_format = number_format($o_total['value'], 2, '.', '') * 100;
					
   					if (DISPLAY_PRICE_WITH_TAX == 'true')
					{
						$group_amount = round(floatval($group_amount_format));
					}
					else 
						{
							$group_amount = round(floatval($group_amount_format + $order_total_sub_total));
						}				
					array_push($products, array(
						'title' 		=> MODULE_PAYMENT_VISMAPAY_GROUP_TEXT,
						'id' 			=> '',
						'count' 		=> 1,
						'price' 		=> -intval($group_amount),
						'tax' 			=> 0,
						'pretax_price'	=> intval($group_amount),
						'type' 			=> 4,
					));			
					$total_check -= $group_amount;
				}
			}				
			
			else if(isset($o_total['code']) && $o_total['code'] == 'ot_shipping')
			{
				if(isset($o_total['value']) && $o_total['value'] > 0)
				{
					$discount_amount_shipping = $o_total['value'];
				}
			}			
			else if(isset($o_total['code']) && $o_total['code'] == 'ot_group_pricing')
			{
				if(isset($o_total['value']) && $o_total['value'] > 0)
				{
					$group_discount_amount = $o_total['value'];
				}
			}				
			else if(isset($o_total['code']) && $o_total['code'] == 'ot_coupon')
			{
				if(isset($o_total['value']) && $o_total['value'] > 0)
				{
					$coupon_amount = $o_total['value'];
				}
			}
			else if(isset($o_total['code']) && $o_total['code'] == 'ot_tax')
			{
				if(isset($o_total['value']) && $o_total['value'] > 0)
				{
					$shiping_ot_tax = $o_total['value'];
				}
			}

			else if(isset($o_total['code']) && $o_total['code'] == 'ot_total')
			{
				if(isset($o_total['value']) && $o_total['value'] > 0)
				{
					$total_amount = number_format($o_total['value'], 2, '.', '') * 100;
				}
			}	
		}
			
		//Add coupon breakdown
		if (abs(isset($_SESSION['cc_id']))){
			$sql = "select * 
				from " . TABLE_COUPONS . " c,
				     " . TABLE_COUPONS_DESCRIPTION . " cd,
					 " . TABLE_TAX_RATES . " tr 
				where c.coupon_id=:couponID: and coupon_active='Y' 
				and c.coupon_id = cd.coupon_id	";
			$sql = $db->bindVars($sql, ':couponID:', $_SESSION['cc_id'], 'integer');
			
			$coupon = $db->Execute($sql);
			$coupon_product_count    = $coupon->fields['coupon_product_count'];
			$coupon_tax_rate = $coupon->fields['tax_rate'];
			$coupon_code     = $coupon->fields['coupon_code'];
			$coupon_amount_formatted = number_format($coupon_amount, 2, '.', '');
			$coupon_shipping_tax = zen_round($shipping_tax, $decimals) * 100 ;
			if (isset($discount_amount_shipping)) {
				$discount_amount_shipping = $o_total['value'];
			} else {
				$discount_amount_shipping = 0;
			}		
			$coupon_amount_shipping = $discount_amount_shipping * 100;
			
			if (DISPLAY_PRICE_WITH_TAX == 'true') {
				$coupon_amount = $coupon_amount_formatted * 100;
			} 
			else {
				$coupon_amount = $coupon->fields['coupon_amount'];
			}			
			
			//Variable to compare product discount calculation to total amount of the order
			$coupon_result = 0;	
			switch ($coupon->fields['coupon_type']){
//				case 'S': // shipping
//					$coupon_result = $coupon_tax_amount ;		
//				break;
				case 'F': // unit amount
				// One by one  unit amount total
					if ($coupon_product_count == 1) {
						$coupon_result = $coupon_amount * $itemqyt * 100;
					} 
					else {
						$coupon_result = $coupon_amount;
					}
				break;
				// amount off and free shipping	
				case 'O': 
					$coupon_amount_shipping = $discount_amount_shipping * 100;
					$O_shippingprice = $coupon_amount_shipping +  $coupon_shipping_tax;
					// One by one  unit amount total					
					if ($coupon_product_count == 1) {
						$coupon_amount = $coupon_amount * 100 * $itemqyt;
						$coupon_amount_shipping = $discount_amount_shipping * 100;

						$coupon_result = $coupon_amount + $coupon_amount_shipping;
					} else {
						$coupon_result = ($coupon_amount_formatted + $discount_amount_shipping) * 100;
					}
				array_push($products, array(
					'title' 		=> MODULE_PAYMENT_VISMAPAY_FREE_SHPING,
					'id' 			=> '',
					'count' 		=> 1,
					'price' 		=> $O_shippingprice,
					'tax' 			=> 0,
					'pretax_price'	=> $O_shippingprice,
					'type' 			=> 2,
				));	
				$total_check += $O_shippingprice;
				break;	
				// percentage	
				case 'P': 
					 if($shippingcost > 0 )
					 {
						// Coupon cost
						$coupon_cost = ($order_subtotal/100)*($coupon_amount);
						// add shiping cost and shping tax
						$coupon_shiping_tax = ($shippingcost/100)*($coupon_amount);
						$coupon_result =  ($coupon_cost + $coupon_shiping_tax) ;
						$coupon_result = zen_round($coupon_result, $decimals) * 100;
					}	
					else 
						{
							$coupon_result = ($order_subtotal/100)*($coupon_amount_formatted);
							$coupon_result = zen_round($coupon_result, $decimals) * 100;
						}
				break;
				// percentage and Free Shipping
				case 'E': 
						$E_shipping_tax_cost =  zen_round($shiping_ot_tax, $decimals) * 100;
						$E_shipping_price = $coupon_amount_shipping + $E_shipping_tax_cost ;
						$E_shipping_tax =($E_shipping_tax_cost/$coupon_amount_shipping)* 100 ;

						$coupon_cost = (($order_subtotal + $discount_amount_shipping)/100) * ($coupon_amount_formatted);
						$coupon_result = ($coupon_cost + $discount_amount_shipping);
						$coupon_result = zen_round($coupon_result, $decimals) * 100;
					
				array_push($products, array(
					'title' 		=> MODULE_PAYMENT_VISMAPAY_FREE_SHPING,
					'id' 			=> '',
					'count' 		=> 1,
					'price' 		=> $E_shipping_price,
					'tax' 			=> $E_shipping_tax,
					'pretax_price'	=> $E_shipping_price,
					'type' 			=> 2,
				));	
				$total_check += $E_shipping_price;
				break;				
			}// end switch
			
            array_push($products, array(
                'title' 		=> MODULE_PAYMENT_VISMAPAY_COUPON_TEXT,
                'id' 			=> $coupon_code,
                'count' 		=> 1,
                'price'			=> -$coupon_result,
				'tax' 			=> 0,
                'pretax_price'	=> $coupon_result,
                'type' 			=> 4,
            ));
			$total_check -= $coupon_result;
        }
		
        // Add Gift Voucher breakdown
		if ($_SESSION['cot_gv'] > 0) {
			$gv_query = "select * 
				from " . TABLE_COUPON_GV_CUSTOMER . " 
				where customer_id = '" . $_SESSION['customer_id'] . "'";
			$gv_order = $db->Execute($gv_query);
			
			// Gift amonut total	
			$gv_order_amount = number_format($gv_order->fields['amount'], 2, '.', '') .'€';
			$gv_amount = $_SESSION['cot_gv'] * 100;
			
			// if tax is to be calculated on purchased GVs, calculate it
            array_push($products, array(
                'title' 		=> MODULE_PAYMENT_VISMAPAY_GIFT_TEXT,
                'id' 			=> '',
                'count' 		=> 1,
                'price' 		=> -$gv_amount,
 				'tax' 			=> 0,
                'pretax_price'	=> $gv_amount,
                'type' 			=> 4,
            ));			
			$total_check -= $gv_amount;

        }
		
	    // Add reward points breakdown
		
	    if (array_key_exists('redeem_points', $_SESSION)) {
		    $redeemPoints = $_SESSION['redeem_points'];
	    } else {
		    $redeemPoints = 0; // Reward Point not aktif
	    }	
		
	    if ($redeemPoints > 0) 
	    {	
			$redem_value = number_format($redeemPoints, 2, '.', '') * 100;
			// if tax is to be calculated on purchased GVs, calculate it
			array_push($products, array(
				'title' 		=> MODULE_PAYMENT_VISMAPAY_REWARD_POINT_TEXT,
				'id' 			=> '',
				'count' 		=> 1,
				'price' 		=> -$redem_value,
				'tax' 			=> 0,
				'pretax_price'	=> $redem_value,
				'type' 			=> 4,
			));			
			$total_check -= $redem_value;			
		}			

		// Add sumround breakdown
		if ($amount <> $total_check)  
		{
			if ($amount > $total_check)  {
				$sum_round_count = $amount - $total_check;
				$plsmin = '+';
		    }
            else if ($total_check > $amount)  {
				$sum_round_count = $total_check - $amount;
				$plsmin = '-';
		    }
			$sum_round = round(floatval($sum_round_count));
			array_push($products, array(
                'title' 		=> MODULE_PAYMENT_VISMAPAY_SUM_ROUND,
                'id' 			=> '',
                'count' 		=> 1,
                'price' 		=> $plsmin .$sum_round,
				'tax' 			=> 0,
                'pretax_price'	=> $plsmin .$sum_round,
                'type' 			=> 1,
            ));
		}

		if($this->send_items == 'Enabled' || $this->send_items == 'Disabled')
		{
			foreach($products as $product)
			{
				$client->addProduct(
					array('id' 			=> $product['id'],
						  'title' 		=> htmlspecialchars($product['title']),
						  'count' 		=> $product['count'],
						  'pretax_price'=> $product['pretax_price'],
						  'tax' 		=> $product['tax'],
						  'price' 		=> $product['price'],
						  'type' 		=> $product['type']
					)
				);
			}
		}
		/**
		*** Check amount and total sum ***
		*   echo 'amount: '.$amount .'<br>' .'total_check: ' .$total_check  .'<br>' .'sum round: ' .$sum_round  .'<br>';
		*/
		
		$vp_selected = '';
		if($this->embed == '1' || $this->embed == '2')
			{
			$vp_selected = array($visma_pay_selected);
			}
			else
			{
				$vp_selected = array();
				if($this->currency)
					{
						if($this->banks 			== 'Enabled') $vp_selected[] = 'banks';
						if($this->wallets 			== 'Enabled') $vp_selected[] = 'wallets';
						if($this->ccards 			== 'Enabled') $vp_selected[] = 'creditcards';
						if($this->cinvoices 		== 'Enabled') $vp_selected[] = 'creditinvoices';
						if($this->laskuyritykselle	== 'Enabled') $vp_selected[] = 'laskuyritykselle';
					}
				else if($this->currency == 'null') 
				{
					$creditcards = $banks = $creditinvoices = $wallets = '';
					$payment_methods = new VismaPay\VismaPay($this->api_key, $this->private_key);
				try
				{
				  $response = $payment_methods->getMerchantPaymentMethods($this->currency);
					if($response->result == 0)
					{ 
					  if(count($response->payment_methods) > 0)
					  {
						foreach ($response->payment_methods as $method)
						{
							$key = $method->selected_value;
								if($method->group == 'creditcards')
									$key = strtolower($method->name);
								if($method->group == 'creditcards'  && $this->ccards == 'Enabled')
								{
									$vp_selected[] = $method->group;
								}									
								else if($method->group == 'wallets' && $this->wallets == 'Enabled')
								{
									$vp_selected[] = $method->selected_value; 
								}
								else if($method->group == 'banks' && $this->banks == 'Enabled')
								{
									$vp_selected[] = $method->selected_value;
								}
								else if($method->group == 'creditinvoices')
								{
									if($method->selected_value == 'laskuyritykselle')
									{
										if($this->laskuyritykselle == 'Enabled')
										{
											$vp_selected[] = $method->selected_value;
										}
									} 
									else if($this->cinvoices == 'Enabled' && ((!isset($order) && $cart_total >= $method->min_amount && $cart_total <= $method->max_amount) || ($total >= $method->min_amount && $total <= $method->max_amount)))
									{
										$vp_selected[] = $method->selected_value;
									}
								}
							
							if(empty($vp_selected))
							{
								echo MODULE_PAYMENT_VISMAPAY_NOTAVAILABLE . $order_number . ', ' .MODULE_PAYMENT_VISMAPAY_CURRENCY .$this->language;
								return;
							}							
						}
					  }

					if(empty($creditcards) && empty($banks) && empty($creditinvoices) && empty($wallets))
					{
						echo MODULE_PAYMENT_VISMAPAY_NOTAVAILABLE . $order_number . ', ' .MODULE_PAYMENT_VISMAPAY_CURRENCY . $this->currency .'<br>';
						return;
					}
					else
					{
						if (!empty($this->payment_description)) echo $this->payment_description;
					}						
				}
			}
			catch (VismaPay\VismaPayException $e) 
			{
				echo MODULE_PAYMENT_VISMAPAY_MAC_ERROR .'&nbsp;' . MODULE_PAYMENT_VISMAPAY_ORDER_NUMBER . $order_number . ', ' .MODULE_PAYMENT_VISMAPAY_EXCEPTION . $e->getCode().' '.$e->getMessage();
			}
		}
		else
		{
			echo MODULE_PAYMENT_VISMAPAY_ONLYEUR;
			return;
		}
	}
	
	$client->addPaymentMethod(
		array(
			'type' 				=> 'e-payment', 
			'return_url' 		=> $this->return_address,
			'notify_url' 		=> $this->return_address,
			'lang' 				=> $lang,
			'selected' 			=> $vp_selected,
			'token_valid_until' => strtotime('+1 hour')
			));	
		
		try
		{
		  $response = $client->createCharge();
			if($response->result == 0)
			{
				$sql = ( "insert into vismapay_session (customer_id, vismapay_order_number, vismapay_amount, vismapay_cart, vismapay_settled, vismapay_status) values (:sessioncustomerid:, :ordernumber:, :amount:, :sessioncart:, :settled:, :status:)");
				$sql = $db->bindVars($sql, ':sessioncustomerid:', $_SESSION['customer_id'], 'integer');
				$sql = $db->bindVars($sql, ':ordernumber:', $order_number, 'integer');
				$sql = $db->bindVars($sql, ':amount:', $amount, 'integer');
				$sql = $db->bindVars($sql, ':sessioncart:', json_encode($_SESSION['cart']), 'string');
				$sql = $db->bindVars($sql, ':settled:', 1, 'integer');
				$sql = $db->bindVars($sql, ':status:', 0, 'integer');
				$db->Execute($sql);
					
				$this->form_action_url = VismaPay\VismaPay::API_URL."/token/".$response->token;
			}
			else if($response->result == 10)
			{
				$errors = '';
				echo MODULE_PAYMENT_VISMAPAY_UNABLE_CREATE_PAYMENT . $response->result .'<br>';
				echo MODULE_PAYMENT_VISMAPAY_TRYAGAIN .'<br>';
				echo 'Visma Pay REST::CreateCharge. ' .MODULE_PAYMENT_VISMAPAY_MAINTENANCE .'<br>';
				return;
			}
			else
			{
				$errors = '';
				if(isset($response->errors))
				{
					foreach ($response->errors as $error) 
					{
						$errors .= ' '.$error;
					}
				}
				echo 'Visma Pay REST::CreateCharge failed, response: ' . $response->result . ' - Errors: '.$errors.'<br>';
				return;
			}
		}
		
		catch(VismaPay\VismaPayException $e)
		{
			$result = $e->getCode();
			$message = $e->getMessage();
			if($result == 2)
				echo '<br> Visma Pay exception 2: ' . $message . ',' .MODULE_PAYMENT_VISMAPAY_ORDER_NUMBER .$order_number, 2, null, null, null, true;
			elseif($result == 3)
				echo '<br> Visma Pay exception 3: ' . $message . ',' .MODULE_PAYMENT_VISMAPAY_ORDER_NUMBER .$order_number, 3, null, null, null, true;
			elseif($result == 4)
				echo '<br> Visma Pay exception 4: ' . $message . ',' .MODULE_PAYMENT_VISMAPAY_ORDER_NUMBER .$order_number, 4, null, null, null, true;
			elseif($result == 5)
				echo '<br> Visma Pay exception 5: ' . $message . ',' .MODULE_PAYMENT_VISMAPAY_ORDER_NUMBER .$order_number, 5, null, null, null, true;
				echo "Unexpected HTTP status code: {$e->getCode()}\n\n";
                echo "<div style='color:red'>" .MODULE_PAYMENT_VISMAPAY_PAYMENT_ERROR;
                echo "<br><strong>" .MODULE_PAYMENT_VISMAPAY_SELECET_OTHER  ."</strong><br>";
                echo "<button type='button' class='btn btn-outline-danger'>" .zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . "</a></button></div>";			
			return;			
		}		
	
		/**   
		*  NIDA VERKKOPALVELU
		*  Error control.  " // " and check to sending request data.
		*/
		// echo 'AUTHCODE : ' .$authcode  .'<br>';
		// echo 'TOKEN : ' .$response->token  .'<br>';
		// echo(json_encode(json_decode($body), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
		// var_dump(json_decode($body, true));	
		// echo '<pre>'; print_r(json_decode($body,true)); exit;
		
		// Starting active payment icon
		$html  = "</form>\n";
		$html .='<style>
				/* Visma Pay */
				#btn_submit, 
				.buttonRow   { display: none; } /*Submit button hidden */
				.btn-success { display: none; } /*Submit button hidden */
				.button {
				    width: auto;
					height: 40px;
					display: inline-block;
					zoom: 1; /* zoom and *display = ie7 hack for display:inline-block */
					*display: inline;
					vertical-align: baseline;
					margin: 0 2px;
					outline: none;
					cursor: pointer;
					text-align: center;
					text-decoration: none;
					font: 16px/100% Arial, Helvetica, sans-serif;
					padding: .5em 2em .55em;
					text-shadow: 0 1px 1px rgba(0,0,0,.3);
					-webkit-border-radius: .5em; 
					-moz-border-radius: .5em;
					border-radius: .5em;
					-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.2);
					-moz-box-shadow: 0 1px 2px rgba(0,0,0,.2);
					box-shadow: 0 1px 2px rgba(0,0,0,.2);
				}
				.button:hover {
					text-decoration: none;
				}
				/* green */
				.green {
					color: #e8f0de;
					border: solid 1px #538312;
					background: #64991e;
					background: -webkit-gradient(linear, left top, left bottom, from(#7db72f), to(#4e7d0e));
					background: -moz-linear-gradient(top,  #7db72f,  #4e7d0e);
				}
				.green:hover {
					background: #538018;
					background: -webkit-gradient(linear, left top, left bottom, from(#6b9d28), to(#436b0c));
					background: -moz-linear-gradient(top,  #6b9d28,  #436b0c);
				}
				</style>';
		$html .= '<script>		</script>';			
		
		if ($this->embed == '0') 
		{
			$action_button = BUTTON_CONFIRM_ORDER_ALT;
			$html .= '<form action="' .$this->form_action_url .'" method="POST">';		
			$html .= '<div><button type="submit" class="button green">' .$action_button .'</button></div>';

//			// Vismapay submit button
//			$action_button = BUTTON_CONFIRM_ORDER_ALT;
//    		$instruction_title = TITLE_CONTINUE_CHECKOUT_PROCEDURE;
//    		$instruction_text = TEXT_CONTINUE_CHECKOUT_PROCEDURE;
//			$html .= '<div class="hstack gap-2">';
//			$html .= '  <div class="p-2 ms-auto"><br><strong>' . $instruction_title . '</strong>' . $instruction_text .'</div>';
//			$html .= '  <div class="vr"></div>';
//			$html .= '  <div class="p-2"><button type="submit" class="btn btn-success btn-sm">' .$action_button .'</button></div>';
//			$html .= '</div>';
			$html .= '</form>';
		}
		
		if ($this->embed == '1' || $this->embed == '2') 
		{
			$payment_return = '';
			try
			{
				$result = $client->checkStatusWithOrderNumber($order_number);
				if($result->RETURN_CODE == 0)
				{
					$payment_return = 'Payment succeeded';
				}
				else
				{
					$payment_return = 'Payment failed (RETURN_CODE: ' . $result->RETURN_CODE . ')';
				}
			}
			catch(VismaPay\VismaPayException $e)
			{
				echo 'Got the following exception: ' . $e->getMessage();
				return;	
			}			
			// getMerchantPaymentMethods
			try
			{
				$merchantPaymentMethods = $client->getMerchantPaymentMethods($this->currency);
				if($merchantPaymentMethods->result != 0)
				{
					echo MODULE_PAYMENT_VISMAPAY_MERCHANT_API;
					return;	
				}
			}
			catch(VismaPay\VismaPayException $e)
			{
				echo MODULE_PAYMENT_VISMAPAY_MAC_ERROR .'&nbsp;' . MODULE_PAYMENT_VISMAPAY_ORDER_NUMBER . $order_number . ', exception: ' . $e->getCode().' '.$e->getMessage();
				return;	
			}

		$html .= MODULE_PAYMENT_VISMAPAY_IMMERSION;
	} // end $this->embed == '1'			
	return $html;
} // end function process_button

	function before_process()
	{
		global $messageStack, $db;
		//if this is a notify checkout process, stop here
		if (isset($_SESSION['notify_process']) && $_SESSION['notify_process']) return;

		$return_code = isset($_GET['RETURN_CODE']) ? $_GET['RETURN_CODE'] : -999;
		$incident_id = isset($_GET['INCIDENT_ID']) ? $_GET['INCIDENT_ID'] : null;
		$settled = isset($_GET['SETTLED']) ? $_GET['SETTLED'] : null;
		$authcode = isset($_GET['AUTHCODE']) ? $_GET['AUTHCODE'] : null;
		$contact_id = isset($_GET['CONTACT_ID']) ? $_GET['CONTACT_ID'] : null;
		$order_number = isset($_GET['ORDER_NUMBER']) ? $_GET['ORDER_NUMBER'] : null;
		$authcode_confirm = $return_code .'|'. $order_number;

		if(isset($return_code) && $return_code == 0)
			{
				$authcode_confirm .= '|' . $settled;
				if(isset($contact_id) && !empty($contact_id))
					$authcode_confirm .= '|' . $contact_id;
			}
				else if(isset($incident_id) && !empty($incident_id))
					$authcode_confirm .= '|' . $incident_id;
		
		$authcode_confirm = strtoupper(hash_hmac('sha256', $authcode_confirm, $this->private_key));
		
		$success = false;
		$error_message = null;		
	
		if($authcode_confirm == $authcode)
		{
			$client = new VismaPay\VismaPay($this->api_key, $this->private_key);
			$threedsmsg 	= '';
			$error_message	= '';
			$card_country	= '';
			$client_country	= '';
			$card_message	= '';
			try
			{
				$message = '';
				$response = $client->checkStatusWithOrderNumber($order_number);
				if($response->source->object == "card")	{
					
					switch($response->source->card_verified) 
					{
						case 'Y':
							$threedsmsg = MODULE_PAYMENT_VISMAPAY_3D_USED;
							$error_message .= MODULE_PAYMENT_VISMAPAY_3D_USED . ' ';
							break;
						case 'N':
							$threedsmsg = MODULE_PAYMENT_VISMAPAY_3D_NOT_USED;
							$error_message .= MODULE_PAYMENT_VISMAPAY_3D_NOT_USED . ' ';
							break;
						case 'A':
							$threedsmsg = MODULE_PAYMENT_VISMAPAY_3D_SUPPORTED;
							$error_message .= MODULE_PAYMENT_VISMAPAY_3D_SUPPORTED . ' ';
							break;
						default:
							$threedsmsg = MODULE_PAYMENT_VISMAPAY_3D_NO_CONNECTION;
							$error_message .= MODULE_PAYMENT_VISMAPAY_3D_NO_CONNECTION . ' ';
							break;
					}

					if($response->source->error_code != '') {
						
						switch ($response->source->error_code) {
							case '04':
								$error_message = MODULE_PAYMENT_VISMAPAY_CARD_LOST;
								break;
							case '05':
								$error_message = MODULE_PAYMENT_VISMAPAY_CARD_DECLINE;
								break;
							case '51':
								$error_message = MODULE_PAYMENT_VISMAPAY_CARD_INSUFFICENT_FUND;
								break;
							case '54':
								$error_message = MODULE_PAYMENT_VISMAPAY_CARD_EXPIRED;
								break;
							case '61':
								$error_message = MODULE_PAYMENT_VISMAPAY_CARD_WITHDRAWAL;
								break;
							case '62':
								$error_message = MODULE_PAYMENT_VISMAPAY_CARD_RESTRICTED;
								break;
							case '1000':
								$error_message = MODULE_PAYMENT_VISMAPAY_CARD_TIMOUT;
								break;
							default:
								$error_message = MODULE_PAYMENT_VISMAPAY_CARD_NO_ERROR . ' \"' . $response->source->error_code . '\"' ;
								break;
						}
					}

					if($response->source->card_country != '') {
						$card_country = 'Card ISO 3166-1 country code: ' . ' ' . $response->source->card_country;
						$error_message .= 'Card ISO 3166-1 country code:' . $response->source->card_country;
					}

					if($response->source->client_ip_country != '') {
						$client_country = 'Client ISO 3166-1 country code: ' . ' ' . $response->source->client_ip_country;
						$error_message .= 'Client ISO 3166-1 country code:' . $response->source->client_ip_country;
					}

					$message .= PHP_EOL . $threedsmsg . PHP_EOL . ($error_message != '' ? $error_message . PHP_EOL : '') . ($card_country != '' ? $card_country . PHP_EOL : '') . ($client_country != '' ? $client_country . PHP_EOL : '');
				}

				if($response->source->brand != '')
					$message .= 'Payment method: ' . ' ' . $response->source->brand . '.' . PHP_EOL;
			}
			catch (VismaPay\VismaPayException $e) 
			{
				echo 'Visma Pay' .MODULE_PAYMENT_VISMAPAY_ORDER_NUMBER . $order_number . ' - Status check Exception: ' . print_r($e,true), 1, null, null, null, true;
			}
				
			switch ($return_code)
			{
				case 0:
					$success = true;
					if($settled == 0)
					{     
						define ('MODULE_PAYMENT_VISMAPAY_ORDER_STATUS_ID_AUTHORIZED','1');
						$comment = MODULE_PAYMENT_VISMAPAY_PAYMENT_AUTHRORIZED; 
						$status_id = MODULE_PAYMENT_VISMAPAY_ORDER_STATUS_ID_AUTHORIZED;
						$is_settled = false;
						$db->Execute("UPDATE vismapay_session SET vismapay_status = '$status_id' where vismapay_order_number = '$order_number'");
					}
					else
					{
						$comment = MODULE_PAYMENT_VISMAPAY_PAYMENT_SETTLED;
						$status_id = MODULE_PAYMENT_VISMAPAY_ORDER_STATUS_ID_SETTLED;
						$is_settled = true;
						$db->Execute("UPDATE vismapay_session SET vismapay_status = '$status_id' where vismapay_order_number = '$order_number'");
					}

					$comment .= " " .MODULE_PAYMENT_VISMAPAY_PAYMENT_METHOD .$response->source->brand . " , " . MODULE_PAYMENT_VISMAPAY_ORDER_NUMBER . ": " . $order_number . ".";

					$_SESSION['vismapaygatway_order_comment'] = $comment;
					$_SESSION['vismapaygatway_order_status_id'] = $status_id;
					$_SESSION['vismapaygatway_order_number'] = $order_number;
					$_SESSION['vismapaygatway_is_settled'] = $is_settled;					
					break;
				case 1:
					$success = false;
					$error_message .= MODULE_PAYMENT_VISMAPAY_PAYMENT_1;
					break;
				case 4:
					$success = false;
					$error_message = MODULE_PAYMENT_VISMAPAY_PAYMENT_4;
					break;
				case 10:
					$success = false;
					$error_message = MODULE_PAYMENT_VISMAPAY_PAYMENT_10;
					break;					
				default:
					$error_message = MODULE_PAYMENT_VISMAPAY_ERROR;
					break;
			}
		}
			
		else
		{
			$error_message = MODULE_PAYMENT_VISMAPAY_TEXT_API_ERROR;
		}

		if(!$success)
		{
			$messageStack->add_session('checkout_payment', $error_message, 'error');
			zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', true, false));
		}

	}// end before_process

	function after_process()
	{
		global  $messageStack, $insert_id, $db, $order;
		if(isset($_SESSION['vismapaygatway_order_comment']))
		{
			$comment = $_SESSION['vismapaygatway_order_comment'];
			zen_update_orders_history($insert_id, $comment, null, $order->info['order_status'], 3);			
			unset($_SESSION['vismapaygatway_order_comment']);
		}

		if(isset($_SESSION['vismapaygatway_order_status_id']))
		{
			if($_SESSION['vismapaygatway_order_status_id'] > 0)
			{
				$order_status_id = zen_db_input($_SESSION['vismapaygatway_order_status_id']);
				$db->Execute("update " . TABLE_ORDERS . " set orders_status = '" . $order_status_id . "' where orders_id = '" . $insert_id . "'");
				$db->Execute("update " . TABLE_ORDERS_STATUS_HISTORY . " set orders_status_id = $order_status_id  where orders_id = '" . $insert_id . "'");
			}
			unset($_SESSION['vismapaygatway_order_status_id']);
		}

		if(isset($_SESSION['vismapaygatway_order_number']))
		{
			$db->Execute("update " . TABLE_ORDERS . " set vismapay_order_number = '" . zen_db_input($_SESSION['vismapaygatway_order_number']) . "' where orders_id = '" . $insert_id . "'");
			unset($_SESSION['vismapaygatway_order_number']);
		}

		if(isset($_SESSION['vismapaygatway_is_settled']) && $_SESSION['vismapaygatway_is_settled'])
		{
			$db->Execute("update " . TABLE_ORDERS . " set vismapay_settled = '1' where orders_id = '" . $insert_id . "'");
			unset($_SESSION['vismapaygatway_is_settled']);
		}
		return true;
		
	}// end function after_process	

    function install() {
      global $db, $messageStack;
      if (defined('MODULE_PAYMENT_VISMAPAY_STATUS')) {
        $messageStack->add_session('MoneyOrder module already installed.', 'error');
        zen_redirect(zen_href_link(FILENAME_MODULES, 'set=payment&module=vismapay', 'NONSSL'));
        return 'failed';
      }
		global $db;
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Lajittelujärjestys', 'MODULE_PAYMENT_VISMAPAY_SORT_ORDER', '0', 'Maksutavan lajittelujärjestys. Pienimmän luvun omaava on ylimpänä.', '6', '1', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Visma Pay ota käyttöön ', 'MODULE_PAYMENT_VISMAPAY_STATUS', 'Kyllä', 'Otetaanko maksumoduuli käyttöön?', '6', '2', 'zen_cfg_select_option(array(\'Kyllä\', \'Ei\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Yksityinen salausavain', 'MODULE_PAYMENT_VISMAPAY_VP_PRIVATE_KEY', 'TESTPRIVATEKEY', 'Test Yksityinen salausavain: TESTPRIVATEKEY', '6', '3', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Yksityinen rajapinta-avain', 'MODULE_PAYMENT_VISMAPAY_VP_API_KEY', 'TESTAPIKEY', 'Test Yksityinen rajapinta-avain TESTAPIKEY', '6', '4', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Tilausnumeron etuliite', 'MODULE_PAYMENT_VISMAPAY_ORDERNUMBER_PREFIX', '', 'Etuliitteellä vältetään samanlaisten tilaustunnusten luonti, jos kauppiastunnus on käytössä useassa verkkokaupassa. (Valinnainen)', '6', '5', now())");			
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Visma Pay maksumoduulin voimassaoloalue', 'MODULE_PAYMENT_VISMAPAY_ZONE', '0', 'Jos alue on valittu, käytä tätä maksutapaa vain valitun alueen ostotapahtumille..', '6', '6', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Tilauksen tila suoritetun maksun jälkeen', 'MODULE_PAYMENT_VISMAPAY_ORDER_STATUS_ID_SETTLED', '2', 'Tilauksen tila maksun suorittamisen jälkeen:', '6', '7', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Upotus', 'MODULE_PAYMENT_VISMAPAY_EMBEDDED', '0', 'Ota upotetut maksupainikkeet käyttöön.<br>
		<strong>2 Eritelty:</strong> Kaikki Visma Pay kauppiastilille aktivoidut maksutavat näkyvät verkkokaupan kassasivulla erillisinä maksutapoina.<br>
		<strong>1 Upotus:</strong> Kun kassasivulla valitaan maksutavaksi Visma Pay, niin tulevat kauppiastilille aktivoidut maksutavat näkyviin logojensa kera.<br>
		<strong>0 Pois käytöstä:</strong> Kassasivulla voidaan valita maksutavaksi Visma Pay. Asiakas ohjataan Visma Payn maksusivulle valitsemaan maksutapa.', '6', '8', 'zen_cfg_select_option(array(\'2\', \'1\', \'0\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Lompakkopalvelut', 'MODULE_PAYMENT_VISMAPAY_WALLETS', 'Enabled', 'Ota lompakkopalvelut käyttöön maksusivulla. (MobilePay, Masterpass, Pivo ja Siirto)', '6', '9', 'zen_cfg_select_option(array(\'Enabled\', \'Disabled\'), ', now())");		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Pankit', 'MODULE_PAYMENT_VISMAPAY_BANKS', 'Enabled', 'Ota verkkopankkipainikkeet käyttöön maksusivulla.', '6', '10', 'zen_cfg_select_option(array(\'Enabled\', \'Disabled\'), ', now())");		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Korttimaksut', 'MODULE_PAYMENT_VISMAPAY_CCARDS', 'Enabled', 'Ota korttimaksupainikkeet käyttöön maksusivulla.', '6', '11', 'zen_cfg_select_option(array(\'Enabled\', \'Disabled\'), ', now())");		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Luottolaskut', 'MODULE_PAYMENT_VISMAPAY_CINVOICES', 'Enabled', 'Ota luottolasku- ja osamaksu-maksutavat käyttöön maksusivulla.', '6', '12', 'zen_cfg_select_option(array(\'Enabled\', \'Disabled\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enterpay-yrityslasku', 'MODULE_PAYMENT_VISMAPAY_LASKUYRITYKSELLE', 'Enabled', 'Ota Enterpay-yrityslasku käyttöön maksusivulla.', '6', '13', 'zen_cfg_select_option(array(\'Enabled\', \'Disabled\'), ', now())");			
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Lähetä tuote-erittely maksurajapintaan', 'MODULE_PAYMENT_VISMAPAY_SEND_ITEMS', 'Enabled', 'Ota käyttöön poista päältä tai pakota tuote-erittelyn lähetys. Yleensä arvon tulisi olla Enabled', '6', '14', 'zen_cfg_select_option(array(\'Enabled\', \'Disabled\'), ', now())");			
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Lähetä maksuvahvistus', 'MODULE_PAYMENT_VISMAPAY_SEND_CONFIRMATION', 'Enabled', 'Lähetä asiakkaan sähköpostiin Visma Payn maksuvahvistus.', '6', '15', 'zen_cfg_select_option(array(\'Enabled\', \'Disabled\'), ', now())");		
		
		$db->Execute("ALTER TABLE " . TABLE_ORDERS . " ADD vismapay_order_number varchar(255)");
		$db->Execute("ALTER TABLE " . TABLE_ORDERS . " ADD vismapay_settled int(1) DEFAULT 0");
		
		$db->Execute( "CREATE TABLE IF NOT EXISTS vismapay_session (
						`vismapay_id` INT(10) NOT NULL AUTO_INCREMENT,
						`customer_id` INT(11) NOT NULL DEFAULT '0',
						`vismapay_order_number` VARCHAR(50) NOT NULL, 
						`vismapay_amount` decimal(20,4) NOT NULL,						
						`vismapay_cart` TEXT NOT NULL, 
						`vismapay_settled` INT(5) DEFAULT '0',
						`vismapay_status` INT(5) NOT NULL DEFAULT '0', PRIMARY KEY (`vismapay_id`))");		
    } // end function install

    function remove() {
      global $db;
		$db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
		$db->Execute("ALTER TABLE " . TABLE_ORDERS . " DROP COLUMN vismapay_order_number");
		$db->Execute("ALTER TABLE " . TABLE_ORDERS . " DROP COLUMN vismapay_settled");
		
		$db->Execute("DROP TABLE IF EXISTS vismapay_session");	
    } // end function remove

    function keys() {
		return array('MODULE_PAYMENT_VISMAPAY_SORT_ORDER', 
					 'MODULE_PAYMENT_VISMAPAY_STATUS', 
					 'MODULE_PAYMENT_VISMAPAY_VP_API_KEY', 
					 'MODULE_PAYMENT_VISMAPAY_VP_PRIVATE_KEY',
					 'MODULE_PAYMENT_VISMAPAY_ORDERNUMBER_PREFIX',
					 'MODULE_PAYMENT_VISMAPAY_ZONE',
					 'MODULE_PAYMENT_VISMAPAY_ORDER_STATUS_ID_SETTLED',
					 'MODULE_PAYMENT_VISMAPAY_EMBEDDED',
					 'MODULE_PAYMENT_VISMAPAY_WALLETS',
					 'MODULE_PAYMENT_VISMAPAY_BANKS',
					 'MODULE_PAYMENT_VISMAPAY_CCARDS',
					 'MODULE_PAYMENT_VISMAPAY_CINVOICES',
					 'MODULE_PAYMENT_VISMAPAY_LASKUYRITYKSELLE',
					 'MODULE_PAYMENT_VISMAPAY_SEND_ITEMS',
					 'MODULE_PAYMENT_VISMAPAY_SEND_CONFIRMATION');				 
					 		
    } // end function keys 
// ********************************
// Visma Pay
// ********************************
	private function retrieveDynamicMethods($amount, &$vismapay_payment_methods, $display = false, $currency = 'EUR')
	{
		$privatekey = $this->private_key;
		$apikey = $this->api_key;
		$payment_methods = new VismaPay\VismaPay($apikey, $privatekey);
		try
		{
			$response = $payment_methods->getMerchantPaymentMethods($currency);

			if($response->result == 0 && count($response->payment_methods) > 0)
			{
				foreach ($response->payment_methods as $method)
				{
					$key = $method->selected_value;
					if($method->group == 'creditcards')
						$key = strtolower($method->name);
						$this->vismapay_save_img($key, $method->img, $method->img_timestamp);

					if($method->group == 'creditcards'  && $this->ccards == 'Enabled')
					{
						$vismapay_payment_methods['creditcards'][$key] = $method->name;
					}
					else if($method->group == 'wallets' && $this->wallets == 'Enabled')
					{
						$vismapay_payment_methods['wallets'][$key] = $method->name;
					}
					else if($method->group == 'banks' && $this->banks == 'Enabled')
					{
						$vismapay_payment_methods['banks'][$key] = $method->name;
					}
					else if($method->group == 'creditinvoices')
					{
						if($this->laskuyritykselle == 'Enabled' && $key == 'laskuyritykselle')
							$vismapay_payment_methods['creditinvoices'][$key] = $method->name;
						else if($key != 'laskuyritykselle' && $amount >= $method->min_amount && $amount <= $method->max_amount && $this->cinvoices == 'Enabled')
							$vismapay_payment_methods['creditinvoices'][$key] = $method->name;
					}
				}
			}
		}
		catch (VismaPay\VismaPayException $e) 
		{
			echo MODULE_PAYMENT_VISMAPAY_MAC_ERROR .'&nbsp;' . MODULE_PAYMENT_VISMAPAY_ORDER_NUMBER . $order_number . ', ' .MODULE_PAYMENT_VISMAPAY_EXCEPTION . $e->getCode().' '.$e->getMessage();
		}
		return true;
	}	
	
	private function vismapay_save_img($key, $img_url, $img_timestamp)
	{
		$img = require DIR_FS_CATALOG .DIR_WS_MODULES . 'payment/vismapay/assets/images/' .$key.'.png';
		$timestamp = file_exists($img) ? filemtime($img) : 0;
		if(!file_exists($img) || $img_timestamp > $timestamp)
		{
			if($file = @fopen($img_url, 'r'))
			{
				if(class_exists('finfo'))
				{
					$finfo = new finfo(FILEINFO_MIME_TYPE);
					if(strpos($finfo->buffer($file_content = stream_get_contents($file)), 'image') !== false)
					{
						@file_put_contents($img, $file_content);
						touch($img, $img_timestamp);
					}
				}
				else
				{
					@file_put_contents($img, $file);
					touch($img, $img_timestamp);
				}
				@fclose($file);
			}
		}
		return;
	}	
} // end class vismapay
?>