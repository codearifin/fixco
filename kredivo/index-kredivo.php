<?php

@session_start();

require('../config/connection.php');
Require('../config/myconfig.php');
// include_once('veritrans-php-master/Veritrans.php');

function replaceUrl($text){
			//fisrt replace
			$str_awal = array("<br>", "<br >", "<br />" , "<br/>");
			$text_awal = str_replace($str_awal," ",strtolower($text));
			
			//next
			$str = array("’", " " , "/" , "?" , "%" , "," , "!" , "#" , "$" , "@" , "^" , "&" , "\"" , "\\" , "\r\n" , "\n" , "\r" , "'", "." , "rsquo;");
			$newtext=str_replace($str,"-",strtolower($text_awal));
			return $newtext;
	}


function gb_queryall($table_name, $str_select, $str_where=false, $str_order=false) {

		global $db;

		$str = " SELECT ".$str_select." FROM `".$table_name."` 

			   ".($str_where ? "WHERE ".$str_where : "")."

		   	   ".($str_order ? "ORDER BY ".$str_order : "")." ";

	

		$result = $db->query($str);

		$jumpage = $result->num_rows;

		if($jumpage > 0):

			$arr_return_data = array();

			while($row = $result->fetch_assoc()):

				array_push($arr_return_data, $row);

			endwhile;

			

			return $arr_return_data;

		 endif;

	

		return false;

}



function getnamegeneral($iddata,$fieldid,$act,$field){

	global $db;

	$query = $db->query("SELECT `".$field."` FROM `".$act."` WHERE `".$fieldid."` = '$iddata' ");

	$row = $query->fetch_assoc();

	return ucwords($row[$field]);	

}



function getnamakota($id){

	global $db;

	$query = $db->query("SELECT `nama_kota` FROM `ongkir` WHERE `id`='$id'");

	$res = $query->fetch_assoc();

	return ucwords($res['nama_kota']);

}	



//token member--

if(isset($_SESSION['tokenorderidmember'])): $tokenid = $_SESSION['tokenorderidmember']; else: $tokenid = ''; endif;

if(isset($_SESSION['user_statusmember'])): $user_statusmember = $_SESSION['user_statusmember']; else: $user_statusmember = ''; endif;

if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;



if($user_statusmember=="REGULAR MEMBER"):

	//member data---

	$quepmm = $db->query("SELECT * FROM `member` WHERE `tokenmember`='$Usertokenid'");

	$datamember = $quepmm->fetch_assoc();	

else:

	$qummep = $db->query("SELECT `idmember_list` FROM `corporate_user` WHERE `id`='$Usertokenid'");

	$ros = $qummep->fetch_assoc();	

	$uidmembermain = $ros['idmember_list'];

	

	//member data---

	$quepmm = $db->query("SELECT * FROM `member` WHERE `id`='$uidmembermain'");

	$datamember = $quepmm->fetch_assoc();		

endif;



//member

$idmember = $datamember['id'];

$namamenber = $datamember['name'];

$namamenberLast = $datamember['lastname'];

$emailmember = $datamember['email'];

$member_phone = $datamember['mobile_phone'];



$shipp_address_member = getnamegeneral($idmember,"idmember","billing_address","address");

$idkotamember = getnamegeneral($idmember,"idmember","billing_address","idcity");

$namakota_member = getnamakota($idkotamember);

$namakabupaten_member = getnamegeneral($idmember,"idmember","billing_address","kabupaten").', '.getnamegeneral($idmember,"idmember","billing_address","provinsi");

$kodeposmember = getnamegeneral($idmember,"idmember","billing_address","kodepos");

if($kodeposmember =="" ): $koddepos = "0000"; else: $koddepos = $kodeposmember; endif;





//order header

$queryodr = $db->query("SELECT * FROM `order_header` WHERE `idmember`='$idmember' and `tokenpay`='$tokenid' ");

$res = $queryodr->fetch_assoc();



//order header--

$totalordermember = ( ($res['orderamount']+$res['shippingcost']+$res['kode_unik']+$res['handling_fee']) - $res['discountamount'] ) - $res['deposit_amount'];

$orederidmm = sprintf('%06d',$res['id']);

$Shipongkir = $res['shippingcost'];



$nama_penerima = $res['nama_penerima'];

$phone_penerima = $res['phone_penerima'];

$shipp_address = $res['address_penerima'];

$namakota = $res['kota_penerima'];

$namakabupaten = $res['kabupaten_penerima'].', '.$res['provinsi_penerima'];

if($res['kodepos']==""):

	$kodepos = '0000';

else:

	$kodepos = $res['kodepos'];

endif;	


//st item list

$items_details = array();



//order detail----

$datalisprod = gb_queryall("order_detail", "*", "`tokenpay`='$tokenid'", "`id` ASC");

$nomneruid = 1; $prodname = ''; $prodnameList = '';

foreach($datalisprod as $key => $val){

	$itemwarnalist = explode("#",$val['nama_detail']);

	$warnaprod = $itemwarnalist[0];							

	$prodname = $val['sku'].' - '.$warnaprod.' - '.$val['name'];

	$prodnameList = substr($prodname,0,50);

	

	$item_dataorder[$key] = array(

		'id' => $nomneruid,

		'name' => "".$prodnameList."",

		'price' => (double)$val['price'],

		'url' => $SITE_URL."product-detail/".replaceUrl($val['name'])."/".$val['idproduct'], //url

		'type' => $val['name'], //category

		'quantity' => (int)$val['qty']

	);
	

	$items_detailsListOdr = array_push($items_details, $item_dataorder[$key]);

	$nomneruid++;

}



//voucher code

if($res['discountamount']>0 and $res['vouchercode']<>''):

	$ongkirno = $nomneruid+1;

	$diskonamount = $res['discountamount'];

	$itemdiskonprice = array(

		'id' => 'discount', //Atas request dari pihak Kredivo, id discount jadi discount

		'name' => "Voucher (".$res['vouchercode'].")",

		'price' => (double)$diskonamount,

		'url' => $SITE_URL."discount", //url

		'type' => "Discount", //category

		'quantity' => 1

		
	);

	

	$items_detailsListOdr = array_push($items_details, $itemdiskonprice);	

else:

	$ongkirno = $nomneruid;	

endif;


if($Shipongkir>0):

	//Ongkos Kirim

	$ongkirnoshp = $ongkirno+1;

	$itemOngkoskirim = array(

		'id' => "shippingfee", //Atas request dari pihak Kredivo, id shipping jadi shipping fee (Digabung)

		'name' => "Shipping Cost",

		'price' => (double)$Shipongkir,

		'url' => $SITE_URL."shipping", //url

		'type' => "Shipping", //category

		'quantity' => 1


	);

	$items_detailsListOdr = array_push($items_details, $itemOngkoskirim);

endif;


if($res['handling_fee'] > 0):
	$OngkosHandling = array(

		'id' => "additionalfee", //Atas request dari pihak Kredivo, id handling jadi additional fee or taxfee

		'name' => "Handling Fee",

		'price' => (double)$res['handling_fee'],

		'url' => $SITE_URL."handling", //url

		'type' => "Handling", //category

		'quantity' => 1


	);

	$items_detailsListOdr = array_push($items_details, $OngkosHandling);

endif;


// Required
$transaction_details = array(

	'amount' 			=> $totalordermember,

  	'order_id' 			=> $orederidmm,

  	'items' 			=> $items_details

);

// Opsional

$customer_details = array(

    'first_name'    => $namamenber,

    'last_name'     => $namamenberLast,

    'email'         => $emailmember,

    'phone'         => $member_phone

);

$billing_address = array(

    'first_name'    => $namamenber,

    'last_name'     => $namamenberLast,

    'address'       => $shipp_address_member.' - '.$namakota_member,

    'city'          => $namakabupaten_member,

    'postal_code'   => $koddepos,

    'phone'         => $member_phone,

    'country_code'  => 'IDN'

);

$shipping_address = array(

	'first_name'    => $nama_penerima,

	'last_name'     => "",

	'address'       => $shipp_address.' - '.$namakota,

	'city'          => $namakabupaten,

	'postal_code'   => $kodepos,

	'phone'         => $phone_penerima,

	'country_code'  => 'IDN'

);

//Set token auth

$authToken = '8tLHIx8V0N6KtnSpS9Nbd6zROFFJH7'; //key sandbox

//$authToken = '2juHSUQt5cpvexZDQUmn5temB7XtFX'; //key fixcomart

//Set request array
$request = array(

	'server_key' 		=> '8tLHIx8V0N6KtnSpS9Nbd6zROFFJH7', //key sandbox kredivo

	// 'server_key' 		=> '2juHSUQt5cpvexZDQUmn5temB7XtFX', //key sandbox kredivo-fixco

	// 'server_key' 			=> 'ZGwNpwVBttv9eLUaVt9GYdC35Tuu4z', //key production kredivo-fixco

	'payment_type' 			=> "12_months",

	'transaction_details' 	=> $transaction_details,

	'customer_details' 		=> $customer_details,

  	'billing_address'  		=> $billing_address,

    'shipping_address' 		=> $shipping_address,

    "push_uri"				=> "https://fixcomart.ngcdemo.com/kredivo/notification.php", //Tempat terima response dari Kredivo dan update tabel

    //"push_uri"				=> "http://fixcomart.com/kredivo/notification.php", //Tempat terima response dari Kredivo dan update tabel

    "back_to_store_uri" 	=> "http://fixcomart.ngcdemo.com/kredivo-finish" //Kredivo akan redirect back kesini

    //"back_to_store_uri" 	=> "http://fixcomart.com/kredivo-finish"

);

// link checkout sandbox 	: https://sandbox.kredivo.com/kredivo/v2/checkout_url
// link checkout real 		: https://api.kredivo.com/kredivo/v2/checkout_url

// Setup cURL
$ch = curl_init('https://sandbox.kredivo.com/kredivo/v2/checkout_url');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => array(
        'Authorization: '.$authToken,
        'Content-Type: application/json'
    ),
    CURLOPT_POSTFIELDS => json_encode($request)
));

// Send the request
$response = curl_exec($ch);

// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}
else
{
	// Decode the response
	$responseData = json_decode($response, TRUE);

	header('Location: ' . $responseData['redirect_url']);
}
?>