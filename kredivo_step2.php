<?php 
@session_start();
  include('config/connection.php');
  include "include/function.php";
  include "config/myconfig.php";

   if(isset($_SESSION['user_token'])==''):
    echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
   endif;
  
   if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else:  $Usertokenid = ''; endif;
   if(isset($_GET['codepay'])): $code = $_GET['codepay']; endif;
  
   $query = $db->query("SELECT * FROM `member` WHERE `tokenmember`='$Usertokenid'");
   $res = $query->fetch_assoc();
   $idmember = $res['id']; 

   $query2 = $db->query("SELECT * FROM `billing_address` WHERE `idmember`='$idmember'");
   $rowaddress = $query2->fetch_assoc();

   //select order
   $que = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') as tglbel, DATE_FORMAT(`date`, '%d%m%Y') as tglbeltrx , DATE_FORMAT(`date`, '%Y%m%d') as kodetrx 
   FROM `order_header` WHERE `idmember`='$idmember' and `status_payment`='Pending On Payment' and `tokenpay`='$code'");
   $jumdataorder = $que->num_rows;
   if($jumdataorder < 1):
    echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
   endif;
   
   $data = $que->fetch_assoc();
   $totalorder = (($data['orderamount']+$data['shippingcost']+$data['kode_unik']+$data['handling_fee'])-$data['discountamount'])-$data['deposit_amount'];

   //Kredivo Modul
   //st item list

    $items_details = array();



    //order detail----

    $datalisprod = $db->query("SELECT * FROM order_detail WHERE tokenpay = '$code' ORDER BY id ASC");

    $nomneruid = 1; $prodname = ''; $prodnameList = '';

    foreach($datalisprod as $key => $val){

      $itemwarnalist = explode("#",$val['nama_detail']);

      $warnaprod = $itemwarnalist[0];             

      $prodname = $val['sku'].' - '.$warnaprod.' - '.$val['name'];

      $prodnameList = substr($prodname,0,50);

      

      $item_dataorder[$key] = array(

        'id' => $nomneruid,

        'name' => "".$prodnameList."",

        'price' => $val['price'],

        'quantity' => $val['qty']

        

      );

      

      $items_detailsListOdr = array_push($items_details, $item_dataorder[$key]);

      $nomneruid++;

    } 

    //voucher code

    if($data['discountamount']>0 and $data['vouchercode']<>''):

      $ongkirno = $nomneruid+1;

      $diskonamount = $data['discountamount'];

      $itemdiskonprice = array(

        'id' => 'discount', //Atas request dari pihak Kredivo, id discount jadi discount

        'name' => "Voucher (".$data['vouchercode'].")",

        'price' => $diskonamount,

        'quantity' => 1

        
      );
      
      $items_detailsListOdr = array_push($items_details, $itemdiskonprice); 

    else:

      $ongkirno = $nomneruid; 

    endif;


    if($data['shippingcost']>0):

      //Ongkos Kirim

      $ongkirnoshp = $ongkirno+1;

      $itemOngkoskirim = array(

        'id' => "shippingfee", //Atas request dari pihak Kredivo, id shipping jadi shipping fee (Digabung)

        'name' => "Shipping Cost",

        'price' => $data['shippingcost'],

        'quantity' => 1


      );

      $items_detailsListOdr = array_push($items_details, $itemOngkoskirim);

    endif;


    if($data['handling_fee'] > 0):
      $OngkosHandling = array(

        'id' => "additionalfee", //Atas request dari pihak Kredivo, id handling jadi additional fee or taxfee

        'name' => "Handling Fee",

        'price' => $data['handling_fee'],

        'quantity' => 1


      );

      $items_detailsListOdr = array_push($items_details, $OngkosHandling);

    endif;


    // Required
    $transaction_details = array(

      'amount'      => $totalorder,

      'order_id'    => $data['id'],

      'items'       => $items_details

    );

    // Opsional

    $customer_details = array(

        'first_name'    => $res['name'],

        'last_name'     => $res['lastname'],

        'email'         => $res['email'],

        'phone'         => $res['mobile_phone']

    );

    $namakabupaten_member = getnamakota($rowaddress['idcity']);

    $billing_address = array(

        'first_name'    => $res['name'],

        'last_name'     => $res['lastname'],

        'address'       => $rowaddress['address'].' '.$rowaddress['kabupaten'],

        'city'          => $namakabupaten_member,

        'postal_code'   => $rowaddress['kodepos'],

        'phone'         => $res['phone'],

        'country_code'  => 'IDN'

    );

    $shipping_address = array(

      'first_name'    => $data['nama_penerima'],

      'last_name'     => "",

      'address'       => $data['address_penerima'].' '.$data['kabupaten_penerima'],

      'city'          => $data['kota_penerima'],

      'postal_code'   => $data['kodepos'],

      'phone'         => $data['phone_penerima'],

      'country_code'  => 'IDN'

    );

    //Set token auth

     $authToken = '8tLHIx8V0N6KtnSpS9Nbd6zROFFJH7'; //key sandbox

    // $authToken = '2juHSUQt5cpvexZDQUmn5temB7XtFX'; //key fixcomart

    //Set request array
    $request = array(

      // 'server_key'     => '8tLHIx8V0N6KtnSpS9Nbd6zROFFJH7', //key sandbox kredivo

       'server_key'     => '2juHSUQt5cpvexZDQUmn5temB7XtFX', //key sandbox kredivo-fixco

      // 'server_key'      => 'ZGwNpwVBttv9eLUaVt9GYdC35Tuu4z', //key production kredivo-fixco

      'payment_type'      => '12_months',

      'transaction_details'   => $transaction_details,

      'customer_details'    => $customer_details,

        'billing_address'     => $billing_address,

        'shipping_address'    => $shipping_address,

         "push_uri"        => "https://fixcomart.ngcdemo.com/kredivo-notif", //Tempat terima response dari Kredivo dan update tabel

        // "push_uri"        => "http://fixcomart.com/kredivo/kredivo_response.php", //Tempat terima response dari Kredivo dan update tabel

         "back_to_store_uri"  => "http://fixcomart.ngcdemo.com/kredivo-finish" //Kredivo akan redirect back kesini

        // "back_to_store_uri"   => "http://fixcomart.com/kredivo-finish"

    );

    // link checkout sandbox  : https://sandbox.kredivo.com/kredivo/v2/checkout_url
    // link checkout real     : https://api.kredivo.com/kredivo/v2/checkout_url

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
    if($response === ERROR){
        die(curl_error($ch));
    }
    else
    {
      // Decode the response
      $responseData = json_decode($response, TRUE);

      header('Location: ' . $responseData['redirect_url']);
    }
    ?>