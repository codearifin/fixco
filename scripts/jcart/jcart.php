<?php	

class Jcart {



	public  $config      = array();

	private $items      = array();

	private $names      = array();

	private $prices     = array();

	private $qtys       = array();

	private $qty_detail = array();

	private $id_prod 	= array();

	private $nama_paket	= array();

	private $item_price	= array();

	private $urls       = array();

	private $description = array();

	private $skuprod	 = array();

	private $statusprod	 = array();

	private $subtotal    = 0;

	private $itemCount   = 0;



	function __construct() {



		// Get $config array

		include_once('config-loader.php');

		$this->config = $config;

	}



	/**

	* Get cart contents

	*

	* @return array

	*/

	public function get_contents() {

		$items = array();

		foreach($this->items as $tmpItem) {

			$item = null;

			$item['id']       	 = $tmpItem;

			$item['name']     	 = $this->names[$tmpItem];

			$item['price']    	 = $this->prices[$tmpItem];

			$item['qty']      	 = $this->qtys[$tmpItem];

			$item['qty_detail']  = $this->qty_detail[$tmpItem];

			$item['id_prod'] 	 = $this->id_prod[$tmpItem];

			$item['nama_paket']  = $this->nama_paket[$tmpItem];

			$item['item_price']  = $this->item_price[$tmpItem];

			$item['description'] = $this->description[$tmpItem];

			$item['skuprod'] 	 = $this->skuprod[$tmpItem];

			$item['statusprod']  = $this->statusprod[$tmpItem];

			$item['url']      	 = $this->urls[$tmpItem];

			$item['subtotal'] 	 = $item['price'] * $item['qty'];

			$items[]          	 = $item;

		}

		return $items;

	}



	/**

	* Add an item to the cart

	*

	* @param string $id

	* @param string $name

	* @param float $price

	* @param mixed $qty

	* @param string $url

	*

	* @return mixed

	*/

	

	public function delete_manual($id){	

		$tmpItems = array();

		unset($this->names[$id]);

		unset($this->prices[$id]);

		unset($this->qtys[$id]);

		unset($this->qty_detail[$id]);

		unset($this->id_prod[$id]);

		unset($this->nama_paket[$id]);

		unset($this->item_price[$id]);

		unset($this->skuprod[$id]);

		unset($this->statusprod[$id]);

		unset($this->urls[$id]);

		unset($this->description[$id]);



		// Rebuild the items array, excluding the id we just removed

		foreach($this->items as $item) {

			if($item != $id) {

				$tmpItems[] = $item;

			}

		}

		$this->items = $tmpItems;

		$this->update_subtotal();

					

	}	

		

	private function add_item($id, $name, $price, $qty = 1, $qty_detail = 1, $description, $url, $id_prod, $nama_paket, $item_price, $skuprod, $statusprod) {

			

		$validPrice = false;

		$validQty = false;



		// Verify the price is numeric

		if (is_numeric($price)) {

			$validPrice = true;

		}



		// If decimal quantities are enabled, verify the quantity is a positive float

		if ($this->config['decimalQtys'] === true && filter_var($qty, FILTER_VALIDATE_FLOAT) && $qty > 0) {

			$validQty = true;

		}

		// By default, verify the quantity is a positive integer

		elseif (filter_var($qty, FILTER_VALIDATE_INT) && $qty > 0) {

			$validQty = true;

		}



		// Add the item

		if ($validPrice !== false && $validQty !== false) {



			// If the item is already in the cart, increase its quantity

			if($this->qtys[$id] > 0) {

				$this->qtys[$id] += $qty;

				$this->update_subtotal();

			}

			// This is a new item

			else {

				$this->items[]     = $id;

				$this->names[$id]  = $name;

				$this->prices[$id] = $price;

				$this->qtys[$id]   = $qty;

				$this->qty_detail[$id] = $qty_detail;

				$this->description[$id] = $description;

				$this->id_prod[$id]	   = $id_prod;

				$this->nama_paket[$id] = $nama_paket;

				$this->item_price[$id] = $item_price;

				$this->urls[$id]   	   = $url;

				$this->skuprod[$id]    = $skuprod;

				$this->statusprod[$id] = $statusprod;

				

			}

			$this->update_subtotal();

			return true;

		}

		elseif ($validPrice !== true) {

			$errorType = 'price';

			return $errorType;

		}

		elseif ($validQty !== true) {

			$errorType = 'qty';

			return $errorType;

		}

	}



	

	public function additem_manual($id, $name, $price, $qty, $qty_detail, $description, $url, $id_prod, $nama_paket, $item_price, $skuprod, $statusprod){		

			// Sanitize values for output in the browser

			$id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);

			$id_prod = filter_var($id_prod, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);

			$nama_paket  = filter_var($nama_paket, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);

			$name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);

			$url = filter_var($url, FILTER_SANITIZE_URL);

			

			// Round the quantity if necessary

			if($config['decimalPlaces'] === 1) {

				$qty = round($qty, $config['decimalPlaces']);

			}				

		

			$itemAdded = $this->add_item($id, $name, $price, $qty, $qty_detail, $description, $url, $id_prod, $nama_paket, $item_price, $skuprod, $statusprod);

			

			if ($itemAdded !== true) {

				$errorType = $itemAdded;

				switch($errorType) {

					case 'qty':

						$errorMessage = $config['text']['quantityError'];

						break;

					case 'price':

						$errorMessage = $config['text']['priceError'];

						break;

				}

			}

							

	}

	

	/**

	* Update an item in the cart

	*

	* @param string $id

	* @param mixed $qty

	*

	* @return boolean

	*/

	private function update_item($id, $qty) {



		// If the quantity is zero, no futher validation is required

		if ((int) $qty === 0) {

			$validQty = true;

		}

		// If decimal quantities are enabled, verify it's a float

		elseif ($this->config['decimalQtys'] === true && filter_var($qty, FILTER_VALIDATE_FLOAT)) {

			$validQty = true;

		}

		// By default, verify the quantity is an integer

		elseif (filter_var($qty, FILTER_VALIDATE_INT))	{

			$validQty = true;

		}



		// If it's a valid quantity, remove or update as necessary

		if ($validQty === true) {

			if($qty < 1) {

				$this->remove_item($id);

			}

			else {

				$this->qtys[$id] = $qty;

			}

			$this->update_subtotal();

			return true;

		}

	}

	

	public function update_itemmanual($id, $qty) {



		// If the quantity is zero, no futher validation is required

		if ((int) $qty === 0) {

			$validQty = true;

		}

		// If decimal quantities are enabled, verify it's a float

		elseif ($this->config['decimalQtys'] === true && filter_var($qty, FILTER_VALIDATE_FLOAT)) {

			$validQty = true;

		}

		// By default, verify the quantity is an integer

		elseif (filter_var($qty, FILTER_VALIDATE_INT))	{

			$validQty = true;

		}



		// If it's a valid quantity, remove or update as necessary

		if ($validQty === true) {

			if($qty < 1) {

				$this->remove_item($id);

			}

			else {

				$this->qtys[$id] = $qty;

			}

			$this->update_subtotal();

			return true;

		}

	}



	/* Using post vars to remove items doesn't work because we have to pass the

	id of the item to be removed as the value of the button. If using an input

	with type submit, all browsers display the item id, instead of allowing for

	user-friendly text. If using an input with type image, IE does not submit

	the	value, only x and y coordinates where button was clicked. Can't use a

	hidden input either since the cart form has to encompass all items to

	recalculate	subtotal when a quantity is changed, which means there are

	multiple remove	buttons and no way to associate them with the correct

	hidden input. */



	/**

	* Reamove an item from the cart

	*

	* @param string $id	*

	*/

	private function remove_item($id) {

		$tmpItems = array();



		unset($this->names[$id]);

		unset($this->prices[$id]);

		unset($this->qtys[$id]);

		unset($this->qty_detail[$id]);

		unset($this->id_prod[$id]);

		unset($this->nama_paket[$id]);

		unset($this->item_price[$id]);

		unset($this->urls[$id]);

		unset($this->description[$id]);

		unset($this->skuprod[$id]);

		unset($this->statusprod[$id]);



		// Rebuild the items array, excluding the id we just removed

		foreach($this->items as $item) {

			if($item != $id) {

				$tmpItems[] = $item;

			}

		}

		$this->items = $tmpItems;

		$this->update_subtotal();

		

	}



	public function remove_itemAll($id) {

		$tmpItems = array();



		unset($this->names[$id]);

		unset($this->prices[$id]);

		unset($this->qtys[$id]);

		unset($this->qty_detail[$id]);

		unset($this->id_prod[$id]);

		unset($this->nama_paket[$id]);	

		unset($this->item_price[$id]);		

		unset($this->urls[$id]);

		unset($this->description[$id]);

		unset($this->skuprod[$id]);

		unset($this->statusprod[$id]);



		// Rebuild the items array, excluding the id we just removed

		foreach($this->items as $item) {

			if($item != $id) {

				$tmpItems[] = $item;

			}

		}

		$this->items = $tmpItems;

		$this->update_subtotal();

		

	}	



	/**

	* Empty the cart

	*/

	public function empty_cart() {

		$this->items     = array();

		$this->names     = array();

		$this->prices    = array();

		$this->qtys      = array();

		$this->qty_detail = array();

		$this->id_prod 	  = array();

		$this->nama_paket = array();

		$this->item_price = array();

		$this->urls       = array();

		$this->description	= array();

		$this->skuprod	 = array();

		$this->statusprod = array();

		$this->subtotal  = 0;

		$this->itemCount = 0;

	}



	/**

	* Update the entire cart

	*/

	public function update_cart() {



		// Post value is an array of all item quantities in the cart

		// Treat array as a string for validation

		if (is_array($_POST['jcartItemQty'])) {

			$qtys = implode($_POST['jcartItemQty']);

		}



		// If no item ids, the cart is empty

		if ($_POST['jcartItemId']) {



			$validQtys = false;



			// If decimal quantities are enabled, verify the combined string only contain digits and decimal points

			if ($this->config['decimalQtys'] === true && preg_match("/^[0-9.]+$/i", $qtys)) {

				$validQtys = true;

			}

			// By default, verify the string only contains integers

			elseif (filter_var($qtys, FILTER_VALIDATE_INT) || $qtys == '') {

				$validQtys = true;

			}



			if ($validQtys === true) {



				// The item index

				$count = 0;



				// For each item in the cart, remove or update as necessary

				foreach ($_POST['jcartItemId'] as $id) {



					$qty = $_POST['jcartItemQty'][$count];



					if($qty < 1) {

						$this->remove_item($id);

					}

					else {

						$this->update_item($id, $qty);

					}



					// Increment index for the next item

					$count++;

				}

				return true;

			}

		}

		// If no items in the cart, return true to prevent unnecssary error message

		elseif (!$_POST['jcartItemId']) {

			return true;

		}

	}



	/**

	* Recalculate subtotal

	*/

	private function update_subtotal() {

		$this->itemCount = 0;

		$this->subtotal  = 0;



		if(sizeof($this->items > 0)) {

			foreach($this->items as $item) {

				$this->subtotal += ($this->qtys[$item] * $this->prices[$item]);



				// Total number of items

				$this->itemCount += $this->qtys[$item];

			}

		}

	}

	

	//showing item count

	public function items_num(){

		$total = $this->itemCount;

		return $total;			

	}

	

	//showing cart subtotal

	public function cart_subtotal(){

		$subtotal = $this->subtotal;

		return $subtotal;			

	}	

	

	public function display_cart() {



		$config = $this->config; 

		$errorMessage = null;



		// Output the cart------------		

		// Return specified number of tabs to improve readability of HTML output

		function tab($n) {

			$tabs = null;

			while ($n > 0) {

				$tabs .= "\t";

				--$n;

			}

			return $tabs;

		}



		// If there's an error message wrap it in some HTML

		if ($errorMessage)	{

			$errorMessage = "<p id='jcart-error'>$errorMessage</p>";

		}

		

		//renew jcart rnt

		if(isset($_SESSION['jcartToken'])): $_SESSION['jcartToken'] = $_SESSION['jcartToken']; else: $_SESSION['jcartToken'] = ''; endif;

		echo tab(1)."".$errorMessage."\n";		

		echo tab(1)."<input type='hidden' name='jcartToken' value='{$_SESSION['jcartToken']}' />\n";		

		

			

		

		// If any items in the cart

		$totalqty = 0; $total = 0;  $grandTotal = 0; $satuanproduct = ''; $warnaprod = ''; $EURsize = ''; $UKsize = '';	

		if($this->itemCount > 0) {



			// Display line items SHOW-----

			foreach($this->get_contents() as $item)	{	

			$total = $item['qty']*$item['price'];

			$totalqty = $totalqty+$item['qty'];

			$grandTotal = $grandTotal+($item['qty']*$item['price']);

			$itemwarnalist = explode("#",$item['nama_paket']);

			$warnaprod = $itemwarnalist[0];

			$EURsize = $itemwarnalist[1];

			

			

	

			echo tab(2).'<div class="scb-child clearfix">';

                	echo tab(2).'<div class="scbc scb1 clearfix">';

                        echo tab(2).'<div class="img-wrap"><a href="'.$GLOBALS['SITE_URL'].''.$item['url'].'"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$item['description'].'" /></a></div>';

                       echo tab(2).'<div class="scb1-txt clearfix">';

                            echo tab(2).'<div class="scb1-1">';

							  echo tab(2).'<div class="scb11-1">';

								echo tab(2)."<input name='jcartItemPrice' type='hidden' value='".$item['price']."' id='priceForm-".$item['id']."' />";

								echo tab(2)."<input name='jcartItemTotPrice' type='hidden' value='".$total."' id='priceGrantot-".$item['id']."' />";

								echo tab(2)."<input name='jcartItemQty' type='hidden' value='".$item['qty']."' id='totalQtyitem-".$item['id']."' />";

                                

								echo tab(2).'<h3 class="f-pr"><a href="'.$GLOBALS['SITE_URL'].''.$item['url'].'">'.$item['name'].'</a>';

								echo tab(2).'<span class="jcartlistitem">';

									echo tab(2).'<br /><strong>'.$item['skuprod'].'</strong> - '.$warnaprod.'';

								echo tab(2).'</span>';

								

							    echo tab(2).'</h3></div><!-- .scb11-1 .-->';

							   echo tab(2).' <div class="scb11-2">';

                                	echo tab(2).'<p><span>Price. </span> '.number_format($item['price']).'</p>';

                               echo tab(2).'</div><!-- .scb11-2 -->';	

                            echo tab(2).'</div><!-- .scb1-1 -->';

							

                            echo tab(2).'<div class="scb23">';

                                echo tab(2).'<div class="scb1-2">';

                                    echo tab(2).'<span>Qty </span><input type="text" value="'.$item['qty'].'" id="qtyForm-'.$item['id'].'" maxlength="4" onblur="update_qtyJcart(this)" />';

									echo tab(2).'<div class="plusminus"><a href="#" class="pm-plus btntambah" id="btnplus-'.$item['id'].'">Tambah</a><a href="#" class="pm-minus btnkurang" id="btnminus-'.$item['id'].'">Kurangi</a></div><span></span>';	

                                echo tab(2).'</div><!-- .scb1-2 -->';

                                echo tab(2).'<div class="scb1-3">';

                                    echo tab(2).'<strong><span>Total </span><strong class="item_total'.$item['id'].'">'.number_format($total).'</strong></strong>';

                               echo tab(2).'</div><!-- .scb1-3 -->';

                            echo tab(2).'</div><!-- .scb23 -->';

                        echo tab(2).'</div><!-- .scb1-txt -->';

                    echo tab(2).'</div><!-- .scbc -->';

                    echo tab(2).'<div class="scbc scb2 clearfix">';

                        echo tab(2).'<a href="#" id="'.$item['id'].'-jcartdelete"  class="btn-cancel remove-item">Remove</a>';

                    echo tab(2).'</div><!-- .scb-child -->';

                echo tab(2).'</div><!-- .scb-child -->';

						

			}

			

			echo tab(3).'<div class="scb-total clearfix">';

            	echo tab(3).'<div class="scbt-left">GRAND TOTAL</div>';

                echo tab(3).'<div class="scbt-right"><span class="jumlah_totalbel">'.number_format($grandTotal).'</span></div>';

            echo tab(3).'</div><!-- .scb-total -->';

		

		}else{

			echo tab(3).'<span class="no_recordjcart">Your shopping cart is empty.</span>';

		}			

		

		echo tab(5).'<input type="hidden" value="'.$totalqty.'" id="jumlah_qty" />';

		echo tab(5).'<input type="hidden" value="'.$grandTotal.'" id="jumlah_price" />';	

	}

 }





// Start a new session in case it hasn't already been started on the including page

@session_start();



// Initialize jcart after session start

$jcart = $_SESSION['jcart'];

if(!is_object($jcart)) {

	$jcart = $_SESSION['jcart'] = new Jcart();

}



// Enable request_uri for non-Apache environments

// See: http://api.drupal.org/api/function/request_uri/7

if (!function_exists('request_uri')) {

	function request_uri() {

		if (isset($_SERVER['REQUEST_URI'])) {

			$uri = $_SERVER['REQUEST_URI'];

		}

		else {

			if (isset($_SERVER['argv'])) {

				$uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['argv'][0];

			}

			elseif (isset($_SERVER['QUERY_STRING'])) {

				$uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];

			}

			else {

				$uri = $_SERVER['SCRIPT_NAME'];

			}

		}

		$uri = '/' . ltrim($uri, '/');

		return $uri;

	}

}

?>