<?php 
	@session_start();
	require('../config/connection.php');

	function getstatusvoucher($idmember,$codevoucher){
		global $db;
		$quep = $db->query("SELECT `id` FROM `order_header` WHERE `idmember`='$idmember' and `vouchercode`='$codevoucher'");
		$jumawalcode = $quep->num_rows;
		return $jumawalcode;
	}
	
	
	$notememberlist = $_POST['notememberlist'];
	$_SESSION['notememberlist'] = $notememberlist;
	//kurir lain
	$kurir_lainnya = $_POST['kurir_lainnya'];
	$_SESSION['kuririd_pilih_lainnya'] = $kurir_lainnya;
		
		
		
	date_default_timezone_set('Asia/Jakarta');
	$grandTotal = $_POST['totalbelanja'];
	$codevoucher = filter_var($_POST['codevoucher'], FILTER_SANITIZE_STRING);
	
	$tokenmember = $_SESSION['user_token'];
	$quipmember = $db->query("SELECT `id` FROM `member` WHERE `tokenmember`='$tokenmember'");
	$datamm = $quipmember->fetch_assoc();
	$idmember = $datamm['id'];	 	
    
	$quep = $db->query("SELECT * FROM `voucher_online` WHERE `voucher_code` = '$codevoucher' ");
	$jumawalcode = $quep->num_rows;
	if($jumawalcode>0):
			
			//fetch code voucher--
			$row = $quep->fetch_assoc();
			$jumstock_voucher = $row['stock'];
			$start_date = $row['start_date'];
			$expiry_date = $row['end_date'];
			$minbelanja = $row['min_transaksi_price'];
			$makbelanja = $row['mak_transaksi_price'];
							
										
			if($jumstock_voucher < 1):
				unset($_SESSION['voucher_redeeemID']);
				echo"STOK_VOUCHER_NOL";	
			else:
				//stock voucher oke
				
					$useruseVocuher = getstatusvoucher($idmember,$codevoucher);
					if($useruseVocuher>0):
						unset($_SESSION['voucher_redeeemID']);
						echo"SUDAH_DIGUNAKAN_MEMBER_INI";	
					else:
						
						 //belum digunakan member
						 if($grandTotal >= $minbelanja and ( $grandTotal<=$makbelanja or $makbelanja==0 ) ):
						 		
												//CEK DATE
												$waktu_sekarang	= date("Y-m-d");
												//date promo berakhir
												$pnt3=explode("-",$waktu_sekarang);	
												$tg_now=mktime(0,0,0,$pnt3[1],$pnt3[2],$pnt3[0]);
					
												$pnt6=explode("-",$start_date); 
												$tg_awal=mktime(0,0,0,$pnt6[1],$pnt6[2],$pnt6[0]); 
																		
												$pnt2=explode("-",$expiry_date); 
												$tg_akhir=mktime(0,0,0,$pnt2[1],$pnt2[2],$pnt2[0]); 
												
												$hitung_mulai=(($tg_now-$tg_awal)/86400);
												$hitung_akhir=(($tg_akhir-$tg_now)/86400);	
													
												if($hitung_akhir>=0 and $hitung_mulai>=0):
													$_SESSION['voucher_redeeemID'] = $codevoucher;
													echo"KODE_ACCEPTED";
												else:
													unset($_SESSION['voucher_redeeemID']);
													echo"KODE_VOUCHER_EXPIRED";
												endif;	
											   //END CEK DATE
								
						 else:
							 unset($_SESSION['voucher_redeeemID']);
							 echo"TOTAL_BELANJA_KURANG-".$minbelanja."-".$makbelanja."";	 
						 endif;	
						 //end belum digunakan member 
					
					endif;
					
				//end stock oke
			endif;
				
			
	else:
		unset($_SESSION['voucher_redeeemID']);
		echo"TIDAK_ADA_DI_DATABSE";	
	endif;
?>