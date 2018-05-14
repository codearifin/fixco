<?php 
include_once('../scripts/jcart/jcart.php');
@session_start();	
require('../config/connection.php');
require('../config/myconfig.php');
require('function.php');
require('webconfig-parameters.php');

function global_insert($table_name, $arr_data, $debug=false) {
    global $db;
    $str_column = "";
    $str_values = "";
    foreach($arr_data AS $key => $val) {
        $str_column .= ($str_column == "" ? "":", ")."`".$key."`";
        $str_values .= ($str_values == "" ? "":", ")."'".$val."'";
    }

    $str = "INSERT INTO `".$table_name."` (".$str_column.") VALUES (".$str_values.")";
    if($debug) { echo $str; exit; }

    $result = $db->query($str) or die($db->error);
    if($result):
        return $db->insert_id;
	else:
    	return false;
	endif;
}

function getnamegeneralRedeem($iddata,$fieldid,$act,$field){
	global $db;
	$query = $db->query("SELECT `".$field."` FROM `".$act."` WHERE `".$fieldid."` = '$iddata' ");
	$row = $query->fetch_assoc();
	return ucwords($row[$field]);	
}
		
if(isset($_POST['submit'])){

	if(isset($_SESSION['user_token'])==''):
		$_SESSION['error_msg']='login-first';
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;

	//member data
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
	$query = $db->query("SELECT * FROM `member` WHERE `tokenmember`='$Usertokenid'");
	$res = $query->fetch_assoc();
	$idmember = $res['id'];	
	$totalpointmember = gettotalpintmemberTotal($idmember);
 	//email and name
	$emailmember = $res['email'];
	$membername = $res['name'].' '.$res['lastname'];
				 	
	
	if($res['status_complete']==0):
		echo '<script language="JavaScript">window.location="'.$GLOBALS['SITE_URL'].'my-account";</script>';
	endif;	
			
	$idprodList = $_POST['idprod_redeem'];
	$query = $db->query("SELECT * FROM `redeem_product` WHERE `id`='$idprodList' and `publish`=1 ") or die($db->error);
	$jumpage = $query->num_rows;
	if($jumpage>0):
		$row = $query->fetch_assoc();	
		$idprodlist = $row['id'];
		$totalpointreward = $row['point_redeem'];
		$Skuprod = $row['sku_product'];
		$itemnamelist = $row['name'];
		
		if($row['point_redeem']>$totalpointmember or $row['stock'] < 1):
			echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'redeem-point"</script>';
		endif;
		
	else:
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'redeem-point"</script>';
	endif;
	
	
				//next save data redeem
				date_default_timezone_set('Asia/Jakarta');
				$dateNow = date("Y-m-d H:i:s");
								
				if(isset($_SESSION['member_shiipinid'])): $idabook = $_SESSION['member_shiipinid']; else: $idabook = 0; endif;
				
				if($idabook=="" or $idabook < 1):
					$idcitymember = getidcitymember($idmember);
					$alamatKirim = getalamatkirim($idmember);
							
					//order header list detail address					
					$nama_penerima = $res['name'].' '.$res['lastname'];
					$phone_penerima = $res['mobile_phone'];
					$address_penerima = getnamegeneralRedeem($res['id'],"idmember","billing_address","address");
					$country_penerima = 'Indonesia';
					$provinsi_penerima = getnamegeneralRedeem($res['id'],"idmember","billing_address","provinsi");
					$kabupaten_penerima = getnamegeneralRedeem($res['id'],"idmember","billing_address","kabupaten");
					$kota_penerima = getnamakota($idcitymember);
					$kodepos = getnamegeneralRedeem($res['id'],"idmember","billing_address","kodepos");
				
				else:
					$idcitymember = getcodecityabook($idabook,$idmember);
					$alamatKirim = getalamatkirimbook($idabook,$idmember);	
			
					//order header list detail address					
					$nama_penerima = getnamegeneralRedeem($idabook,"id","address_book","name").' '.getnamegeneralRedeem($idabook,"id","address_book","lastname");
					$phone_penerima = getnamegeneralRedeem($idabook,"id","address_book","mobile_phone");
					$address_penerima = getnamegeneralRedeem($idabook,"id","address_book","address");
					$country_penerima = 'Indonesia';
					$provinsi_penerima = getnamegeneralRedeem($idabook,"id","address_book","provinsi");
					$kabupaten_penerima = getnamegeneralRedeem($idabook,"id","address_book","kabupaten");
					$kota_penerima = getnamakota($idcitymember);
					$kodepos = getnamegeneralRedeem($idabook,"id","address_book","kodepos");
										
				endif;	
	
				//generate code order			
				$ipname_user = $_SERVER['REMOTE_ADDR']; 
				$itemmemberCode = $dateNow.'-'.$idmember.'-'.$emailmember.'-'.$ipname_user.'-'.$membername;
				$tokenidCode_1 = sha1($itemmemberCode);
				$tokenidCode_2 = sha1($tokenidCode_1);
				$tokenidCode_3 = md5($tokenidCode_2);
				$tokenidCode_4 = sha1($tokenidCode_3);
				$tokenpaymntid = sha1($tokenidCode_4);
				
				//save order list
				$odrListitem['date'] = $dateNow;
				$odrListitem['idmember'] = $idmember;
				$odrListitem['orderamount'] = $row['point_redeem'];
				$odrListitem['status_delivery'] = "Pending On Delivery";
				$odrListitem['kurir'] = $kurir_namaid;
				$odrListitem['nama_penerima'] = $nama_penerima;
				$odrListitem['phone_penerima'] = $phone_penerima;
				$odrListitem['address_penerima'] = $address_penerima;
				$odrListitem['country_penerima'] = $country_penerima;
				$odrListitem['provinsi_penerima'] = $provinsi_penerima;
				$odrListitem['kabupaten_penerima'] = $kabupaten_penerima;
				$odrListitem['kota_penerima'] = $kota_penerima;
				$odrListitem['kodepos'] = $kodepos;
				//prod
				$odrListitem['idproduct'] = $idprodlist;
				$odrListitem['sku_product'] = $Skuprod;
				$odrListitem['name'] = $itemnamelist;
				$odrListitem['point'] = $totalpointreward;
				$odrListitem['tokenpay'] = $tokenpaymntid;
				
				$savedata = global_insert("order_header_redeemlist",$odrListitem,false);	
				
				//update redeem point
				$textdata = 'Penukaran Point: '.$row['sku_product'].' - '.$row['name'].'';
				$quipst4 = $db->query("INSERT INTO `rewads_point_member` (`idmember`,`date`,`description`,`point`,`status`) VALUES ('$idmember','$dateNow','$textdata','$totalpointreward','0') ");	
				
				//upcate stock
				$qtyietm = 1;
				$quee_stock = $db->query("UPDATE `redeem_product` SET `stock` = `stock` - '$qtyietm' WHERE `id` = '$idprodlist' "); 
				
				require("order_email_member_redeem.php");	
				 				
				unset($_SESSION['member_shiipinid']);
				
				$_SESSION['error_msg'] = 'save_redeemsukses';
				echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'redeem-success/'.$tokenpaymntid.'"</script>';			
}
?>		
	