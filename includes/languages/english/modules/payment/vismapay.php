<?php
define('MODULE_PAYMENT_VISMAPAY_TEXT_TITLE', 'Online Payment');
define('MODULE_PAYMENT_VISMAPAY_TEXT_DESCRIPTION', 'Visma Pay - SETTING <br>Order Visma Pay with the attached form. Orders processing within two business days. Activation of credit card payments requires company information and that the delivery, return and payment terms of the online store are in order
       <a href="https://www.visma.fi/vismapay" target="_blank">Get more information </a><br><br>
       <a href="https://www.vismapay.com/authenticate" target="_blank">Log in to the Visma Pay merchant portal </a><br><br>
	   Yksityinen rajapinta-avain : API key / Interface key <br>
	   Yksityinen salausavain : Private key / Private encryption key <br>
	   Tilausnumeron etuliite : The prefix of the order number can only contain the letters a-z, numbers , 	   
	   ');
define('MODULE_PAYMENT_PAYMENT_DESCRIPTION','Pay safely with Finnish internet banking, payment cards, wallet services or credit invoices.');
define('MODULE_PAYMENT_VISMAPAY_TEXT_API_ERROR', 'Merchant payment methods are not available. Verify that the private interface key and private encryption key are correct ');
define('MODULE_PAYMENT_VISMAPAY_CURRENCY',' currency ');
define('MODULE_PAYMENT_VISMAPAY_EXCEPTION',' exception: ');
define('MODULE_PAYMENT_VISMAPAY_ONLYEUR','<strong>Only allow payments in EUR</strong> is enabled and currency was not EUR for order:');
define('MODULE_PAYMENT_VISMAPAY_ALERT_TEST', 'Attention: Test mode ');
define('MODULE_PAYMENT_VISMAPAY_ERROR', 'Payment canceled / failed. ');
define('MODULE_PAYMENT_VISMAPAY_MAC_ERROR','Visma Pay error in MAC calculation.');
define('MODULE_PAYMENT_VISMAPAY_REWARD_POINT_TEXT', 'Rewardpoint');
define('MODULE_PAYMENT_VISMAPAY_COUPON_TEXT', 'Discount coupons');
define('MODULE_PAYMENT_VISMAPAY_GIFT_TEXT', 'Gift cards ');
define('MODULE_PAYMENT_VISMAPAY_LOWORDER_TEXT', 'Small order surcharge');
define('MODULE_PAYMENT_VISMAPAY_FREE_SHPING', 'Free delivery');
define('MODULE_PAYMENT_VISMAPAY_GROUP_TEXT', 'Group discount ');
define('MODULE_PAYMENT_VISMAPAY_SUM_ROUND', 'Sum round');

// Order more information
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_METHOD', 'Payment method : ');
define('MODULE_PAYMENT_VISMAPAY_ORDER_NUMBER', 'Order ID ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_ERROR', 'Error! Call customer service ');

// Payment information
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_0', '[Payment completed] ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_1', '[Invalid identifier] ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_2', '[Payment failed ] ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_3', '[Payment completed, requires approval for Visma Pay extra online! ] ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_4', '[Payment not yet made ] ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_10', '[Service outage ] ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_AUTHRORIZED', 'Payment verified .');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_SETTLED', 'Payment charged .');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_BANKS', 'Banks');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_CREDITCARDS', 'Card payments');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_CREDITINVOICE', 'Credit invoices ');
define('MODULE_PAYMENT_VISMAPAY_PAYMENT_WALLETS', 'Wallets');
define('MODULE_PAYMENT_VISMAPAY_TEXT_CONDITIONS_DESCRIPTION', '<span class="termsdescription">Please check the following box to accept the order terms. You can read the terms  <a href="' . zen_href_link(FILENAME_SHIPPING, '', 'SSL') . '"><span class="pseudolink">from here</span></span></a>.');
define('MODULE_PAYMENT_VISMAPAY_TEXT_CONDITIONS_CONFIRM', '<span class="termsiagree">I have read and accepted the terms of the order. </span>');
define('MODULE_PAYMENT_VISMAPAY_NOTAVAILABLE','Visma Pay no payment methods available for order: ');
define('MODULE_PAYMENT_VISMAPAY_UNABLE_CREATE_PAYMENT','Unable to create a payment ');
define('','Visma Pay system is currently in maintenance. Please try again in a few minutes');
define('MODULE_PAYMENT_VISMAPAY_MAINTENANCE','Visma Pay system maintenance in progress.');
define('MODULE_PAYMENT_VISMAPAY_MERCHANT_API','Unable to get the payment methods for the merchant. Please check that api key and private key are correct.');
define('MODULE_PAYMENT_VISMAPAY_3D_USED','3-D Secure was used.');
define('MODULE_PAYMENT_VISMAPAY_3D_NOT_USED','3-D Secure was not used');
define('MODULE_PAYMENT_VISMAPAY_3D_SUPPORTED', '3-D Secure was attempted but not supported by the card issuer or the card holder is not participating.');
define('MODULE_PAYMENT_VISMAPAY_3D_NO_CONNECTION','3-D Secure: No connection to acquirer.');
define('MODULE_PAYMENT_VISMAPAY_CARD_LOST','The card is reported lost or stolen.');
define('MODULE_PAYMENT_VISMAPAY_CARD_DECLINE','General decline. The card holder should contact the issuer to find out why the payment failed.');
define('MODULE_PAYMENT_VISMAPAY_CARD_INSUFFICENT_FUND','Insufficient funds. The card holder should verify that there is balance on the account and the online payments are actived.');
define('MODULE_PAYMENT_VISMAPAY_CARD_EXPIRED','Expired card');
define('MODULE_PAYMENT_VISMAPAY_CARD_WITHDRAWAL','Withdrawal amount limit exceeded.');
define('MODULE_PAYMENT_VISMAPAY_CARD_RESTRICTED','Restricted card. The card holder should verify that the online payments are actived.');
define('MODULE_PAYMENT_VISMAPAY_CARD_TIMOUT','Timeout communicating with the acquirer. The payment should be tried again later.');
define('MODULE_PAYMENT_VISMAPAY_CARD_NO_ERROR','No error for code');
define('MODULE_PAYMENT_VISMAPAY_SELECT','You have to select at least one payment method.');
?>