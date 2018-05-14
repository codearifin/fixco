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

function getnamegeneralOdr($iddata,$fieldid,$act,$field){
	global $db;
	$query = $db->query("SELECT `".$field."` FROM `".$act."` WHERE `".$fieldid."` = '$iddata' ");
	$row = $query->fetch_assoc();
	return ucwords($row[$field]);	
}
	
	
	
		
if(isset($_POST['submit'])){
		
		$typeidbanktrf = $_POST['banktransfer'];
		
		$kodetokenid = $_POST['kodetokenid'];
	    $TokenIdform = verifyFormToken('BelanjaDusdusan.comSpayment2014');
		if($TokenIdform==1):
				
				  date_default_timezone_set('Asia/Jakarta');
				  $dateNow = date("Y-m-d H:i:s");
							
				 //select member
				 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
				 $query = $db->query("SELECT * FROM `member` WHERE `tokenmember`='$Usertokenid'");
				 $res = $query->fetch_assoc();
				 $idmember = $res['id'];
				 $emailmember = $res['email'];
				 $membername = $res['name'].' '.$res['lastname'];

					//jcart
					$totalsatuan = 0; $beratbarang = 0; $JumlahBeratbrg = 0; $grandTotal = 0; $diskonPrice = 0; $totaldiskoncari = 0; $bonus_product = ''; $status_voucher_oke = 0;
					$totaldiskoncari_grandtotal = 0; $listproductid = '';
					
					foreach ($jcart->get_contents() as $item){	
						//total price
						$totalsatuan = $item['price']*$item['qty'];
						//weight
						$beratbarang = getberatbarangTunas($item['id'],$item['qty']);
						$JumlahBeratbrg =  $JumlahBeratbrg + $beratbarang;
						//grand total
						$grandTotal = $grandTotal+($item['qty']*$item['price']);
	
						//diskon
						if(isset($_SESSION['voucher_redeeemID'])<>''): 
							$totaldiskoncari = getdiskonmember($_SESSION['voucher_redeeemID'],$item['id_prod'],$item['qty'],$item['price'],$idmember);
							$diskonPrice = $diskonPrice + $totaldiskoncari; 
							
							$brandID = getidbarndproductlist($item['id_prod']);
							if($brandID>0): $listbrandprod.=$brandID.'#'; endif;		
							
							$listproductid.=$item['id_prod'].'#';
										
						endif;		
												
					}

					if(isset($_SESSION['voucher_redeeemID'])<>''): 
						$bonus_product = cek_statusgetdiskonmemberGiftsaveorder($_SESSION['voucher_redeeemID'],$idmember,$listbrandprod);
					endif;
    
					//cek voucher status--
					if($diskonPrice>0 or $bonus_product > 0): $status_voucher_oke = 1; else:
							
							//cek voucher grand total
							$totaldiskoncari_grandtotal = getdiskonmember_grandtotal($_SESSION['voucher_redeeemID'],$grandTotal,$idmember,$listbrandprod,$listproductid);									
							$diskonPrice = $totaldiskoncari_grandtotal;
							if($diskonPrice>0): $status_voucher_oke = 1; endif;
							
									
					endif;	
						
								
															
				//weight in kg
				$JumlahBeratbrgProd = ceil($JumlahBeratbrg/1000);	
				
				$kurir_namaid = $_POST['kurir_listid'];
				$kurir_lainnya = $_POST['kurir_lainnya'];
					
				if($kurir_namaid=="OTHER"):
					$kurir_orderlist = 'Other ('.$kurir_lainnya.')';
				else:
					$kurir_orderlist = $kurir_namaid;
				endif;	
				
				
				$notemember	= filter_var($_POST['notemember'], FILTER_SANITIZE_STRING);
				
				if(isset($_SESSION['member_shiipinid'])): $idabook = $_SESSION['member_shiipinid']; else: $idabook = 0; endif;
				
				if($idabook=="" or $idabook < 1):
						$idcitymember = getidcitymember($idmember);
						$alamatKirim = getalamatkirim($idmember);
						
						//order header list detail address					
						$nama_penerima = $res['name'].' '.$res['lastname'];
						$phone_penerima = $res['mobile_phone'];
						$address_penerima = getnamegeneralOdr($res['id'],"idmember","billing_address","address");
						$country_penerima = 'Indonesia';
						$provinsi_penerima = getnamegeneralOdr($res['id'],"idmember","billing_address","provinsi");
						$kabupaten_penerima = getnamegeneralOdr($res['id'],"idmember","billing_address","kabupaten");
						$kota_penerima = getnamakota($idcitymember);
						$kodepos = getnamegeneralOdr($res['id'],"idmember","billing_address","kodepos");
		
				else:
						$idcitymember = getcodecityabook($idabook,$idmember);
						$alamatKirim = getalamatkirimbook($idabook,$idmember);	
	
						//order header list detail address					
						$nama_penerima = getnamegeneralOdr($idabook,"id","address_book","name").' '.getnamegeneralOdr($idabook,"id","address_book","lastname");
						$phone_penerima = getnamegeneralOdr($idabook,"id","address_book","mobile_phone");
						$address_penerima = getnamegeneralOdr($idabook,"id","address_book","address");
						$country_penerima = 'Indonesia';
						$provinsi_penerima = getnamegeneralOdr($idabook,"id","address_book","provinsi");
						$kabupaten_penerima = getnamegeneralOdr($idabook,"id","address_book","kabupaten");
						$kota_penerima = getnamakota($idcitymember);
						$kodepos = getnamegeneralOdr($idabook,"id","address_book","kodepos");					
				endif;
	
				$ongkirPrice = getongkirdatalist($idcitymember,$JumlahBeratbrgProd,$kurir_namaid,$grandTotal);					
				 
				//payment---	
				if($typeidbanktrf=='Veritrans'):
					$idbanktrf = 0;	
					$payment_metod = 'Veritrans';
					$kodeuniklorder = 0;
					
				elseif($typeidbanktrf=='BCA KlikPay'):
					$idbanktrf = 0;	
					$payment_metod = 'BCA KlikPay';	
					$kodeuniklorder = 0;			
	
				elseif($typeidbanktrf=='Visa Master'):
					$idbanktrf = 0;	
					$payment_metod = 'Visa Master';	
					$kodeuniklorder = 0;	
								
				else:
					$payment_metod = 'BANK TRANSFER';
					$idbanktrf = $_POST['banktransfer'];
					//$kodeuniklorder = getkodeunikmemberList();
					$kodeuniklorder = 0;
	
				endif;	
						
				$totalfixorder = ( $grandTotal-$diskonPrice ) + $ongkirPrice + $kodeuniklorder + $handling_fee;	
				
				if(isset($_SESSION['voucher_redeeemID'])<>'' and $status_voucher_oke==1): 
					$kodevoucheridtext = $_SESSION['voucher_redeeemID'];					
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
				$odrListitem['idbank'] = $idbanktrf;
				$odrListitem['payment_metod'] = $payment_metod;
				$odrListitem['orderamount'] = $grandTotal;
				$odrListitem['shippingcost'] = $ongkirPrice;
				$odrListitem['handling_fee'] = $handling_fee;
				$odrListitem['weight'] = $JumlahBeratbrgProd;
				$odrListitem['discountamount'] = $diskonPrice;
				$odrListitem['status_payment'] = "Pending On Payment";
				$odrListitem['status_delivery'] = "Pending On Delivery";
				$odrListitem['note'] = $notemember;
				$odrListitem['kurir'] = $kurir_orderlist;
				$odrListitem['vouchercode'] = $kodevoucheridtext;
				$odrListitem['nama_penerima'] = $nama_penerima;
				$odrListitem['phone_penerima'] = $phone_penerima;
				$odrListitem['address_penerima'] = $address_penerima;
				$odrListitem['country_penerima'] = $country_penerima;
				$odrListitem['provinsi_penerima'] = $provinsi_penerima;
				$odrListitem['kabupaten_penerima'] = $kabupaten_penerima;
				$odrListitem['kota_penerima'] = $kota_penerima;
				$odrListitem['kodepos'] = $kodepos;
				$odrListitem['tokenpay'] = $tokenpaymntid;
				$odrListitem['kode_unik'] = $kodeuniklorder;
				
				
				//new save bonus if ada
				if(isset($_SESSION['voucher_redeeemID'])<>'' and $bonus_product > 0):
					insertitemlistprod($_SESSION['voucher_redeeemID'],$tokenpaymntid,$idmember,$listbrandprod);
					echo "Submit Order ....";
				endif;
								
				if(isset($_SESSION['voucher_redeeemID'])<>'' and $status_voucher_oke==1): 
					$quep_stockv = $db->query("UPDATE `voucher_online` SET `stock` = `stock` - 1 WHERE `voucher_code`='".$_SESSION['voucher_redeeemID']."' ");				
				endif;	
				
				//insert data order header--
				$savedata = global_insert("order_header",$odrListitem,false);	
				
										 	
				foreach ($jcart->get_contents() as $itemdetail){		
					
					$idprod = $itemdetail['id_prod'];
					$skuprod = $itemdetail['skuprod'];	
					$iddetailprod = $itemdetail['id'];
					$nameprod = $itemdetail['name'];	
					$namepaket = $itemdetail['nama_paket'];	
					$qtyietm = 	$itemdetail['qty'];
					$pirceprod = $itemdetail['price'];
					
					$quep = $db->query("INSERT INTO `order_detail` (`tokenpay`,`idproduct`,`sku`,`iddetail`,`name`,`nama_detail`,`qty`,`price`) 
					VALUES ('$tokenpaymntid','$idprod','$skuprod','$iddetailprod','$nameprod','$namepaket','$qtyietm','$pirceprod')");
	
					//upcate stock
					$quee_stock = $db->query("UPDATE `product_detail` SET `stock` = `stock` - '$qtyietm' WHERE `id` = '$iddetailprod' and `idproduct_header` = '$idprod' "); 
	
				}

			     require("order_email_member.php");		   
			     $jcart->empty_cart();
			   
			     unset($_SESSION['kuririd_pilih']);
			     unset($_SESSION['voucher_redeeemID']);
			     unset($_SESSION['notememberlist']);
			     unset($_SESSION['member_shiipinid']);
			     unset($_SESSION['kuririd_pilih_lainnya']);
			   

			   if($typeidbanktrf=='Veritrans'):	
			  	    $_SESSION['tokenorderidmember'] = $tokenpaymntid;
					echo'<script language="JavaScript">window.location="'.$SITE_URL.'veritrans-step2";</script>';	
			   
			   elseif($typeidbanktrf=='BCA KlikPay'):
			   		echo'<script language="JavaScript">window.location="'.$SITE_URL.'bca-klikpay-step2/'.$tokenpaymntid.'";</script>';
						
			   else:								 
					echo'<script type="text/javascript">window.location="'.$SITE_URL.'order-confirmation/'.$tokenpaymntid.'"</script>'; 
			   endif;	
	

	  
	  else:
	  	$_SESSION['error_msg'] = 'token-failed';
	  	echo'<script type="text/javascript">window.location="'.$SITE_URL.'finishorder"</script>';
	  endif;			
}
?>		
	