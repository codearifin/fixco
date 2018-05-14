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
		
		$quote_token = $_POST['quote_token'];
		$kodetokenid = $_POST['kodetokenid'];
	    $TokenIdform = verifyFormToken('BelanjaDusdusan.comSpayment2014');
		if($TokenIdform==1):
				
				  date_default_timezone_set('Asia/Jakarta');
				  $dateNow = date("Y-m-d H:i:s");
				  

				  $datepointmember = date("d/m/Y");
				  $itemdates = explode("/",$datepointmember); //dd/mm/yyyy
				  $lastN = mktime(0, 0, 0, $itemdates[1], $itemdates[0]+$draft_quotation_expiry, $itemdates[2]);
				  $datesampai	= date("Y-m-d", $lastN);		
				  $expiry_date = $datesampai;					  
							
				 //select member
			 	 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
				 $qummep = $db->query("SELECT `id`,`idmember_list` FROM `corporate_user` WHERE `id`='$Usertokenid'");
				 $ros = $qummep->fetch_assoc();	
				 $uidmembermain = $ros['idmember_list']; $iduser = $ros['id'];

				 $query = $db->query("SELECT * FROM `member` WHERE `id`='$uidmembermain'");
				 $res = $query->fetch_assoc();
				 $idmember = $res['id'];
				 $emailmember = $res['email'];
				 $membername = $res['name'].' '.$res['lastname'];
				 $totaldeposit = gettotaldepositcorporate($idmember);

					//jcart
					$totalsatuan = 0; $beratbarang = 0; $JumlahBeratbrg = 0; $grandTotal = 0; $diskonPrice = 0; $totaldiskoncari = 0;	
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
						endif;		
												
					}
							
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
				

				if(isset($_SESSION['TokenDrafQuoteListidcity'])): $TokenDrafQuoteListidcity = $_SESSION['TokenDrafQuoteListidcity']; else: $TokenDrafQuoteListidcity = 0; endif;
				if(isset($_SESSION['TokenDrafQuoteList'])): $TokenDrafQuoteList = $_SESSION['TokenDrafQuoteList']; else: $TokenDrafQuoteList = ''; endif;
				
				if($TokenDrafQuoteListidcity>0):
					
						$idcitymember = $TokenDrafQuoteListidcity;
						$alamatKirim = getalamatkirimdraflist($TokenDrafQuoteList);
						
						$queaddresslist = $db->query("SELECT * FROM `draft_quotation_header` WHERE `tokenpay`='$TokenDrafQuoteList' ");
						$dataaddress = $queaddresslist->fetch_assoc();

						//order header list detail address					
						$nama_penerima = $dataaddress['nama_penerima'];
						$phone_penerima = $dataaddress['phone_penerima'];
						$address_penerima = $dataaddress['address_penerima'];
						$country_penerima = $dataaddress['country_penerima'];
						$provinsi_penerima = $dataaddress['provinsi_penerima'];
						$kabupaten_penerima = $dataaddress['kabupaten_penerima'];
						$kota_penerima = $dataaddress['kota_penerima'];
						$kodepos = $dataaddress['kodepos'];
				else:		
					
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
				endif;		
	
				$ongkirPrice = getongkirdatalist($idcitymember,$JumlahBeratbrgProd,$kurir_namaid,$grandTotal);					
				 
				//payment---
				$typeidbanktrf = $_POST['banktransfer'];	
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
					$kodeuniklorder = getkodeunikmemberList();
	
				endif;	
				
				$totalfixorder = ( ($grandTotal-$diskonPrice) + $ongkirPrice + $handling_fee);
					
				//deposit payment
				if(isset($_SESSION['gunakan_deposit'])): $gunakan_deposit = $_SESSION['gunakan_deposit']; else: $gunakan_deposit = 'NO'; endif;
				if($totaldeposit>0 and $gunakan_deposit=="OKE"):
					if($totaldeposit > $totalfixorder):
						$deposit_amount = ( ($grandTotal-$diskonPrice) + $ongkirPrice + $handling_fee);
						$totalbayar_potongdepo = 0;
					else:
						$deposit_amount = $totaldeposit;
						$totalbayar_potongdepo = ( ($grandTotal-$diskonPrice) + $ongkirPrice + $handling_fee) - $totaldeposit;
					endif;
				else:
					$deposit_amount = 0;
					$totalbayar_potongdepo = ( ($grandTotal-$diskonPrice) + $ongkirPrice + $handling_fee);
				endif;
						
				if($totalbayar_potongdepo < 1):
					$statpaymentList = 'Confirmed';
					$payment_metodList = 'Deposit';
					$lastkodeunikid = 0;
				else:
					$statpaymentList = 'Pending On Payment';
					$payment_metodList = $payment_metod;
					$lastkodeunikid = $kodeuniklorder;
				endif;						
				
				
				if(isset($_SESSION['voucher_redeeemID'])<>'' and $diskonPrice>0): 
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
				
				if($quote_token==1):
					
					
					 //save as quote
					 $odrListitemQuote['idmember_header'] = $uidmembermain;
					 $odrListitemQuote['id_user'] = $iduser;
					 $odrListitemQuote['date'] = $dateNow;
					 $odrListitemQuote['expiry_date'] = $expiry_date;
					 $odrListitemQuote['total_order'] = $grandTotal;
					 $odrListitemQuote['shippingcost'] = $ongkirPrice;
					 $odrListitemQuote['handling_fee'] = $handling_fee;
					 $odrListitemQuote['weight'] = $JumlahBeratbrgProd;
					 $odrListitemQuote['nama_penerima'] = $nama_penerima;
					 $odrListitemQuote['phone_penerima'] = $phone_penerima;
					 $odrListitemQuote['address_penerima'] = $address_penerima;
					 $odrListitemQuote['country_penerima'] = $country_penerima;
					 $odrListitemQuote['provinsi_penerima'] = $provinsi_penerima;
					 $odrListitemQuote['kabupaten_penerima'] = $kabupaten_penerima;
					 $odrListitemQuote['kota_penerima'] = $kota_penerima;
					 $odrListitemQuote['kodepos'] = $kodepos;
					 $odrListitemQuote['idcity'] = $idcitymember;
					 $odrListitemQuote['kurir'] = $kurir_orderlist;
					 $odrListitemQuote['note'] = $notemember;
					 $odrListitemQuote['status_order'] = 0;
					 $odrListitemQuote['tokenpay'] = $tokenpaymntid;
					// end save quote
					
					$savedata = global_insert("draft_quotation_header",$odrListitemQuote,false);	

					foreach ($jcart->get_contents() as $itemdetail){		
							
							$idprod = $itemdetail['id_prod'];
							$skuprod = $itemdetail['skuprod'];	
							$iddetailprod = $itemdetail['id'];
							$nameprod = $itemdetail['name'];	
							$namepaket = $itemdetail['nama_paket'];	
							$qtyietm = 	$itemdetail['qty'];
							$pirceprod = $itemdetail['price'];
							
							$quep = $db->query("INSERT INTO `draft_quotation_detail` (`tokenpay`,`idproduct`,`sku`,`iddetail`,`name`,`nama_detail`,`qty`,`price`) 
							VALUES ('$tokenpaymntid','$idprod','$skuprod','$iddetailprod','$nameprod','$namepaket','$qtyietm','$pirceprod')");

					}					
					
					$jcart->empty_cart();

					unset($_SESSION['kuririd_pilih']);
					unset($_SESSION['voucher_redeeemID']);
					unset($_SESSION['notememberlist']);
					unset($_SESSION['member_shiipinid']);
					unset($_SESSION['gunakan_deposit']);
				    unset($_SESSION['TokenDrafQuoteListidcity']);
					unset($_SESSION['TokenDrafQuoteList']);   	
					unset($_SESSION['kuririd_pilih_lainnya']);				
					   					
					$_SESSION['error_msg'] = 'savequoteloist';
					echo'<script type="text/javascript">window.location="'.$SITE_URL.'draft-quotation"</script>'; 
					//end save as quote
									
										
				else:	
						
						//save order list
						$odrListitem['date'] = $dateNow;
						$odrListitem['idmember'] = $idmember;
						$odrListitem['id_user'] = $iduser;
						$odrListitem['idbank'] = $idbanktrf;
						$odrListitem['payment_metod'] = $payment_metodList;
						$odrListitem['orderamount'] = $grandTotal;
						$odrListitem['deposit_amount'] = $deposit_amount;
						$odrListitem['shippingcost'] = $ongkirPrice;
						$odrListitem['handling_fee'] = $handling_fee;
						$odrListitem['weight'] = $JumlahBeratbrgProd;
						$odrListitem['discountamount'] = $diskonPrice;
						$odrListitem['status_payment'] = $statpaymentList;
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
						$odrListitem['kode_unik'] = $lastkodeunikid;
						
					
						$savedata = global_insert("order_header",$odrListitem,false);	
						
						if(isset($_SESSION['voucher_redeeemID'])<>'' and $diskonPrice>0): 
							$quep_stockv = $db->query("UPDATE `voucher_online` SET `stock` = `stock` - 1 WHERE `voucher_code`='".$_SESSION['voucher_redeeemID']."' ");				
						endif;		
						
						if($totaldeposit>0 and $gunakan_deposit=="OKE" and $deposit_amount>0):
							$textdepo = 'Pembelanjaan Order ID #'.sprintf('%06d',$savedata).'';
							$queppdepo = $db->query("INSERT INTO `deposit_member_corporatelist` (`idmember`,`date`,`description`,`amount`,`status`) VALUES ('$idmember','$dateNow','$textdepo','$deposit_amount','0') ");		
						endif;				
						
						if($TokenDrafQuoteList!=""):
							$queppdepo = $db->query("UPDATE `draft_quotation_header` SET `status_order` = 1 WHERE `idmember_header` = '$idmember' and `tokenpay` = '$TokenDrafQuoteList' ");
						endif;		 
						
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
		
					   require("order_email_member_corporate.php");		   
					   $jcart->empty_cart();
					   
					   unset($_SESSION['kuririd_pilih']);
					   unset($_SESSION['voucher_redeeemID']);
					   unset($_SESSION['notememberlist']);
					   unset($_SESSION['member_shiipinid']);
					   unset($_SESSION['gunakan_deposit']);
					   unset($_SESSION['TokenDrafQuoteListidcity']);
					   unset($_SESSION['TokenDrafQuoteList']);   
					   unset($_SESSION['kuririd_pilih_lainnya']);
		
					   if($typeidbanktrf=='Veritrans'):	
						   $_SESSION['tokenorderidmember'] = $tokenpaymntid;
							echo'<script language="JavaScript">window.location="'.$SITE_URL.'veritrans-step2";</script>';	
					   else:								 
							echo'<script type="text/javascript">window.location="'.$SITE_URL.'order-confirmation-corporate/'.$tokenpaymntid.'"</script>'; 
					   endif;	
			 
			 endif;	

	  
	  else:
	  	$_SESSION['error_msg'] = 'token-failed';
	  	echo'<script type="text/javascript">window.location="'.$SITE_URL.'finishorder-corporate"</script>';
	  endif;			
}
?>		
	