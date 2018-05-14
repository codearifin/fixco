<?php

@session_start();

require('../config/connection.php');

include_once('../veritrans/veritrans-php-master/Veritrans.php');



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

	

// Set our server key

Veritrans_Config::$serverKey = 'VT-server-ep12_4yLkHITcQp5MebDz2-R';



// Use sandbox account

Veritrans_Config::$isProduction = false; // true or false



// Required

$transaction_details = array(

  'order_id' => $orederidmm,

  'gross_amount' => $totalordermember,

);









//set item list

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

		'price' => $val['price'],

		'quantity' => $val['qty'],

		'name' => "".$prodnameList.""

	);

	

	$items_detailsListOdr = array_push($items_details, $item_dataorder[$key]);

	$nomneruid++;

}



//voucher code

if($res['discountamount']>0 and $res['vouchercode']<>''):

	$ongkirno = $nomneruid+1;

	$diskonamount = $res['discountamount']*-1;

	$itemdiskonprice = array(

		'id' => $ongkirno,

		'price' => $diskonamount,

		'quantity' => 1,

		'name' => "Voucher (".$res['vouchercode'].")"

	);

	

	$items_detailsListOdr = array_push($items_details, $itemdiskonprice);	

else:

	$ongkirno = $nomneruid;	

endif;





if($Shipongkir>0):

	//Ongkos Kirim

	$ongkirnoshp = $ongkirno+1;

	$itemOngkoskirim = array(

		'id' => $ongkirnoshp,

		'price' => $Shipongkir,

		'quantity' => 1,

		'name' => "Shipping Cost"

	);

	$items_detailsListOdr = array_push($items_details, $itemOngkoskirim);

endif;



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



// Opsional

$customer_details = array(

    'first_name'    => $namamenber,

    'last_name'     => $namamenberLast,

    'email'         => $emailmember,

    'phone'         => $member_phone,

    'billing_address'  => $billing_address,

    'shipping_address' => $shipping_address

);



// Fill transaction details

$transaction = array(

    'payment_type' => 'vtweb',

    'vtweb' => array(

        'credit_card_3d_secure' => true,

        ),

    'transaction_details' => $transaction_details,

    'customer_details' => $customer_details,

    'item_details' => $items_details,

);

$Request = array(
	'server_key' => '2juHSUQt5cpvexZDQUmn5temB7XtFX',
	'payment_type' => '30_days',
	'transaction_details' => $transaction_details,
	
);

$vtweb_url = Veritrans_Vtweb::getRedirectionUrl($transaction);



// Go to VT-Web page

header('Location: ' . $vtweb_url);

?>