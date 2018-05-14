<?php

// Path to your jcart files

$config['jcartPath']              = 'jcart/';



// Path to your checkout page

$config['checkoutPath']           = 'checkout.php';



// The HTML name attributes used in your item forms

$config['item']['id']             = 'my-item-id';   

$config['item']['name']           = 'my-item-name';    

$config['item']['price']          = 'my-item-price';    

$config['item']['qty']            = 'my-item-qty';  

$config['item']['url']            = 'my-item-url';    

$config['item']['description']    = 'my-item-description';   

$config['item']['add']            = 'my-add-button';  

$config['item']['qty_detail']     = 'my-item-qty-detail';   

$config['item']['id_prod']    	  = 'my-item-idprod';    

$config['item']['nama_paket']     = 'my-item-namapaket';  

$config['item']['item_price']     = 'my-item-itemprice';  

$config['item']['skuprod']     	  = 'my-item-skuproduct';   

$config['item']['statusprod']     = 'my-item-statusprod';   

 



// Your PayPal secure merchant ID

// Found here: https://www.paypal.com/webapps/customerprofile/summary.view

$config['paypal']['id']           = 'hans@nukegraphic.com';



////////////////////////////////////////////////////////////////////////////////

// OPTIONAL SETTINGS



// Three-letter currency code, defaults to USD if empty

// See available options here: http://j.mp/agNsTx

$config['currencyCode']           = $_GLOBALS['symbols'];



// Add a unique token to form posts to prevent CSRF exploits

// Learn more: http://conceptlogic.com/jcart/security.php

$config['csrfToken']              = false;



// Override default cart text

$config['text']['cartTitle']      = '';    // Shopping Cart

$config['text']['singleItem']     = '';    // Item

$config['text']['multipleItems']  = '';    // Items

$config['text']['subtotal']       = '';    // Subtotal

$config['text']['update']         = '';    // update

$config['text']['checkout']       = '';    // checkout

$config['text']['checkoutPaypal'] = '';    // Checkout with PayPal

$config['text']['removeLink']     = '';    // remove

$config['text']['emptyButton']    = '';    // empty

$config['text']['emptyMessage']   = '';    // Your cart is empty!

$config['text']['itemAdded']      = '';    // Item added!

$config['text']['priceError']     = '';    // Invalid price format!

$config['text']['quantityError']  = '';    // Item quantities must be whole numbers!

$config['text']['checkoutError']  = '';    // Your order could not be processed!



// Override the default buttons by entering paths to your button images

$config['button']['checkout']     = '';

$config['button']['paypal']       = '';

$config['button']['update']       = '';

$config['button']['empty']        = '';





////////////////////////////////////////////////////////////////////////////////

// ADVANCED SETTINGS



// Display tooltip after the visitor adds an item to their cart?

$config['tooltip']                = true;



// Allow decimals in item quantities?

$config['decimalQtys']            = false;



// How many decimal places are allowed?

$config['decimalPlaces']          = 1;



// Number format for prices, see: http://php.net/manual/en/function.number-format.php

$config['priceFormat']            = array('decimals' => 2, 'dec_point' => '.', 'thousands_sep' => ',');



// Send visitor to PayPal via HTTPS?

$config['paypal']['https']        = true;



// Use PayPal sandbox?

$config['paypal']['sandbox']      = false;



// The URL a visitor is returned to after completing their PayPal transaction

$config['paypal']['returnUrl']    = '';



// The URL of your PayPal IPN script

$config['paypal']['notifyUrl']    = '';



?>