<?php 
@session_start();
  include('config/connection.php');
  include "include/function.php";
  include "config/myconfig.php";
  include_once "bni_call/BniHashing.php";

  // FROM BNI
  // DEMO
  // $client_id = '337';
  // $secret_key = '9474463622ad34801ffe28aab9f23580';
  // $url = 'https://apibeta.bni-ecollection.com/';

  // Production
  $client_id = '53860';
  $secret_key = '3b65c18b24e25fb469018b3a27bfb022';
  $url = 'https://api.bni-ecollection.com/';

	 if(isset($_SESSION['user_token'])==''):
	 	echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	 endif;
	
	 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else:  $Usertokenid = ''; endif;
	 if(isset($_GET['codepay'])): $code = $_GET['codepay']; endif;
	
	 $query = $db->query("SELECT `id` FROM `member` WHERE `tokenmember`='$Usertokenid'");
	 $res = $query->fetch_assoc();
	 $idmember = $res['id']; 

	 //select order
	 $que = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') as tglbel, DATE_FORMAT(`date`, '%d%m%Y') as tglbeltrx , DATE_FORMAT(`date`, '%Y%m%d') as kodetrx 
	 FROM `order_header` WHERE `idmember`='$idmember' and `status_payment`='Pending On Payment' and `tokenpay`='$code'");
	 $jumdataorder = $que->num_rows;
	 if($jumdataorder < 1):
	 	echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	 endif;
	 
	 $data = $que->fetch_assoc();
	 $totalorder = (($data['orderamount']+$data['shippingcost']+$data['kode_unik']+$data['handling_fee'])-$data['discountamount'])-$data['deposit_amount'];

   $data_asli = array(
      'type' => "createbilling",
      'client_id' => $client_id,
      'trx_id' => $data['id'],
      'trx_amount' => $totalorder,
      'billing_type' => 'c',
      'datetime_expired' => date('c', time() + 2 * 3600), // billing will be expired in 2 hours
      'virtual_account' => '',
      'customer_name' => $data['nama_penerima'],
   );

    $hashed_string = BniHashing::hashData(
      $data_asli,
      $client_id,
      $secret_key
    );

    $data = array(
      'client_id' => $client_id,
      'data' => $hashed_string,
    );

    $response = get_content($url, json_encode($data));
    $response_json = json_decode($response, true);

    if ($response_json['status'] !== '000') {
      // handling jika gagal
      var_dump($response_json);
    }
    else {
      $data_response = BniHashing::parseData($response_json['data'], $client_id, $secret_key);
      $temp = $data_response['virtual_account'];
      $_SESSION['virtual_account_number'] = $temp;  
      echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'order-confirmation/'.$code.'"</script>';
    }


?>
