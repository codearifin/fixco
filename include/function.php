<?php
	require('webconfig-parameters.php');
	//BNI ECollection
    function get_content($url, $post = '') {
      $usecookie = __DIR__ . "/cookie.txt";
      $header[] = 'Content-Type: application/json';
      $header[] = "Accept-Encoding: gzip, deflate";
      $header[] = "Cache-Control: max-age=0";
      $header[] = "Connection: keep-alive";
      $header[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6";

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_VERBOSE, false);
      // curl_setopt($ch, CURLOPT_NOBODY, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_ENCODING, true);
      curl_setopt($ch, CURLOPT_AUTOREFERER, true);
      curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36");

      if ($post)
      {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      }

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      $rs = curl_exec($ch);

      if(empty($rs)){
        var_dump($rs, curl_error($ch));
        curl_close($ch);
        return false;
      }
      curl_close($ch);
      return $rs;
    }
    	
	//BCA SINGNATURE	
		 function genKeyId($clearKey) {
			return strtoupper(bin2hex(str2bin($clearKey)));
		 }
		
		 function genSignature($klikPayCode, $transactionDate, $transactionNo, $amount, $currency, $keyId) {
			// Signature Step 1
			$tempKey1 = $klikPayCode . $transactionNo . $currency . $keyId;
			$hashKey1 = getHash($tempKey1);
			//echo "tempKey1 : " . $tempKey1;
			//echo " hasKey1 : " . $hashKey1 . "<br>";
		
			// Signature Step 2
			$strDate = intval32bits($transactionDate);
			$amt = intval32bits($amount);
			$tempKey2 = $strDate + $amt;
			$hashKey2 = getHash((string)$tempKey2);
			//echo "tempKey2 : " . $tempKey2;
			//echo " hashKey2 : " . $hashKey2 . "<br>";
		
			// Generate Key Step 3
			$signature = abs($hashKey1 + $hashKey2);
		
			return $signature;
		}
		
		function genAuthKey($klikPayCode, $transactionNo, $currency, $transactionDate, $keyId) {
			//Step 1 - Padding
			$klikPayCode = str_pad($klikPayCode, 10, "0");
			$transactionNo = str_pad($transactionNo, 18, "A");
			$currency = str_pad($currency, 5, "1");
		
			// Step 2
			$value_1 = $klikPayCode . $transactionNo . $currency . $transactionDate . $keyId;
		
			// Step 3
		
			$hash_value_1 = strtoupper(md5($value_1));
			// Step 4
		
			if (strlen($keyId) == 32)
				$key = $keyId . substr($keyId,0,16);
			else if (strlen($keyId) == 48)
				$key = $keyId;
		
			// hex encode the return value
			return strtoupper(bin2hex(mcrypt_encrypt(MCRYPT_3DES, myhex2bin($key), myhex2bin($hash_value_1),MCRYPT_MODE_ECB)));
		
		} 
		
		function myhex2bin($data) {
			$len = strlen($data);
			return pack("H" . $len, $data);
		}
		
		function str2bin($data) {
			$len = strlen($data);
			return pack("a" . $len, $data);
		}
		
		function intval32bits($value) {
			if ($value > 2147483647)
				$value = ($value - 4294967296);
			else if ($value < -2147483648)
				$value = ($value + 4294967296);
			return $value;
		}
		function getHash($value) {
			$h = 0;
			for ($i = 0;$i < strlen($value);$i++) {
				$h = intval32bits(add31T($h) + ord($value{$i}));
			}
			return $h;
		}
		function add31T($value) {
			$result = 0;
			for($i=1;$i <= 31;$i++) {
				$result = intval32bits($result + $value);
			}
			return $result;
		}
		
	//END BCA SINGNATURE

	
	function getusernamemember($Usertokenid){
		global $db;
		$query = $db->query("SELECT * FROM `member` WHERE `tokenmember`='$Usertokenid'");
		$res = $query->fetch_assoc();
		echo $res['name'].' '.$res['lastname'];
	}
	
	function getproductlistbonus($tokenOrder,$xpageo){
		global $db;
		$nomer = $xpageo+1;
		$qummep = $db->query("SELECT * FROM `order_detail_bonus_product` WHERE `tokenpay` = '$tokenOrder' ORDER BY `id` ASC");
		while($ros = $qummep->fetch_assoc()):
				
				echo'<tr>';
					echo'<td align="center">'.$nomer.' .</td>';
					echo'<td><strong>'.$ros['sku'].' - '.$ros['name'].'</strong></td>';
					echo'<td align="left">* free item product</td>';
					echo'<td align="center">1</td>';
					echo'<td align="right">-</td>';
				echo'</tr>'; 
																
			$nomer++;	
		endwhile;	
	}
	
	function labelkategory($sidsubkat){
		global $db;
		
		$qummep = $db->query("SELECT `name` FROM `subcategory` WHERE `id` = '$sidsubkat' and `publish` = 1");
		$jumpage = $qummep->num_rows;
		if($jumpage>0):
			$ros = $qummep->fetch_assoc();
			$namesubkat = $ros['name'];
			
			$tmpcari = '';
			$query = $db->query("SELECT `id` FROM `subcategory` WHERE `name` = '$namesubkat' and  `publish` = 1 ORDER BY `sortnumber` ASC");
			$jumpagelist = $query->num_rows;
			$pointer = 1;
			while($data = $query->fetch_assoc()):
				if($pointer == $jumpagelist):
					$tmpcari.=" `idsubkat` = '".$data['id']."' ";
				else:
					$tmpcari.=" `idsubkat` = '".$data['id']."' or ";
				endif;	
				$pointer++;	
			endwhile;
			
			if($tmpcari!=""):
				return " ( ".$tmpcari." ) and ";
			endif;	

		endif;			
	}
	
	function getsubkatheadersearch($idsubkat){
		global $db;
		$qummep = $db->query("SELECT * FROM `subcategory` WHERE `publish` = 1 ORDER BY `sortnumber` ASC");
		while($ros = $qummep->fetch_assoc()):
			if($idsubkat==$ros['id']):
				echo'<option value="'.$ros['id'].'" selected="selected">'.$ros['name'].'</option>';		
			else:
				echo'<option value="'.$ros['id'].'">'.$ros['name'].'</option>';		
			endif;
		endwhile;	
	}
	
	function banneradshome(){
		global $db;
		$qummep = $db->query("SELECT * FROM `banner_ads_home` WHERE `publish` = 1 ORDER BY `sortnumber` ASC");
		while($ros = $qummep->fetch_assoc()):
			if($ros['custom_link']!=""):
				echo'<div class="item">';
					echo'<a href="'.$ros['custom_link'].'"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$ros['banner_image'].'" class="lazyload" data-expand="-10"></a>';
				echo'</div><!-- .bbc -->';		
			else:
				echo'<div class="item">';
					echo'<a><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$ros['banner_image'].'" class="lazyload" data-expand="-10"></a>';
				echo'</div><!-- .bbc -->';
			endif;
		endwhile;
	}
	
	function getkurirpertama(){
		global $db;
		$qummep = $db->query("SELECT `kurir_name` FROM `kurir_list` WHERE `publish` = 1 ORDER BY `sortnumber` ASC");
		$ros = $qummep->fetch_assoc();	
		return $ros['kurir_name'];		
	}
	
	function getkurirlistname($namakurir){
		global $db;
		$qummep = $db->query("SELECT `kurir_name` FROM `kurir_list` WHERE `publish` = 1 ORDER BY `sortnumber` ASC");
		while($ros = $qummep->fetch_assoc()):
			if($ros['kurir_name']==$namakurir): $Klass = 'selected="selected"'; else: $Klass = ''; endif;
			echo'<option value="'.$ros['kurir_name'].'" '.$Klass.'>'.$ros['kurir_name'].'</option>';
		endwhile;	
	}
		
	function getstatusmember($Usertokenid){
		global $db;
		$qummep = $db->query("SELECT `status` FROM `corporate_user` WHERE `id`='$Usertokenid'");
		$ros = $qummep->fetch_assoc();	
		return $ros['status'];		
	}
	
	function getmemberaddress_corporatedraff($TokenDrafQuoteList){
		global $db;
		$output = ''; 		
		$que = $db->query("SELECT * FROM `draft_quotation_header` WHERE `tokenpay`='$TokenDrafQuoteList' ");
		$row = $que->fetch_assoc();
		
        if($row['phone_penerima']<>''): $nope = '('.$row['phone_penerima'].')'; else: $nope = ''; endif;	
		$output.='<p><strong>'.ucwords($row['nama_penerima']).'</strong> '.$nope.'<br />';
        $output.=''.$row['address_penerima'].'<br />';
		$output.=''.getnamakota($row['idcity']).' - '.ucwords($row['kabupaten_penerima']).' <br /> '.ucwords($row['provinsi_penerima']).' - '.$row['kodepos'].' <span class="notfoundadd">(Draf Quotation Address)</span>';
        $output.='</p>';
		echo $output;	
	}

	function getalamatkirimdraflist($idmember){
		global $db;
		$output = ''; 
		$que = $db->query("SELECT * FROM `draft_quotation_header` WHERE `tokenpay`='$TokenDrafQuoteList' ");
		$row = $que->fetch_assoc();
		
        if($row['phone_penerima']<>''): $nope = '('.$row['phone_penerima'].')'; else: $nope = ''; endif;	
		$output.='<p><strong>'.ucwords($row['nama_penerima']).'</strong> '.$nope.'<br />';
        $output.=''.$row['address_penerima'].'<br />';
		$output.=''.getnamakota($row['idcity']).' - '.ucwords($row['kabupaten_penerima']).' <br /> '.ucwords($row['provinsi_penerima']).' - '.$row['kodepos'];
        $output.='</p>';
		return $output;		
	}	
		
	
	function getnamegeneral2($where,$tablename,$fieldname){
		global $db;
		$query = $db->query("SELECT `".$fieldname."` FROM `".$tablename."` WHERE ".$where." ") or die($db->error);
		$row = $query->fetch_assoc();
		return $row[$fieldname];
	}
	
	function updateongkirTotal($token){
		global $db;
		$idcity = getnamegeneral2("`tokenpay` = '$token'","draft_quotation_header","idcity");	
		$kurirname = getnamegeneral2("`tokenpay` = '$token'","draft_quotation_header","kurir");	
		
		$beratbarang = 0; $JumlahBeratbrg = 0; $JumlahBeratbrgProd = 0; $grandTotal = 0; $totalbeli = 0;
		$query = $db->query("SELECT `iddetail`,`qty`,`price` FROM `draft_quotation_detail` WHERE `tokenpay`='$token' ORDER BY `id` ASC");
		while($res = $query->fetch_assoc()):
			$beratbarang = getberatbarangTunas($res['iddetail'],$res['qty']);
			$JumlahBeratbrg =  $JumlahBeratbrg + $beratbarang;
			
			$totalbeli = $res['qty']*$res['price'];
			$grandTotal = $grandTotal+$totalbeli;
		endwhile;
		
		$JumlahBeratbrgProd = ceil($JumlahBeratbrg/1000);
		
		$ongkirPrice = getongkirdatalist($idcity,$JumlahBeratbrgProd,$kurirname,$grandTotal);	
		
		//last update header
		$quppprod = $db->query("UPDATE `draft_quotation_header` SET `total_order` = '$grandTotal', `shippingcost` = '$ongkirPrice', `weight` = '$JumlahBeratbrgProd' WHERE `tokenpay` = '$token' ");	
	}
	
	function getproductlistaddquote(){
		global $db;
		$query = $db->query("SELECT * FROM `product` WHERE `publish` = 1 ORDER BY `name` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):	
				echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
			endwhile;	
		endif;		
	}	
	
	function listmembercorporate($Usertokenid,$member_filteruid){
		global $db;
		$qummep = $db->query("SELECT * FROM `corporate_user` WHERE `id`='$Usertokenid'");
		$ros = $qummep->fetch_assoc();	
		if($ros['status']==1):
			//superadmin
			echo'<option value="">Tampilkan Semua</option>';
			$query = $db->query("SELECT * FROM `corporate_user` WHERE `idmember_list` = '".$ros['idmember_list']."' ORDER BY `id` ASC") or die($db->error);
			$jumpage = $query->num_rows;
			if($jumpage>0):
				while($row = $query->fetch_assoc()):	
					if($member_filteruid==$row['id']): $Klass = 'selected="selected"'; else: $Klass = ''; endif;
					echo'<option value="'.$row['id'].'" '.$Klass.'>'.$row['name'].' '.$row['lastname'].'</option>';
				endwhile;
			endif;	
						
		else:
			echo'<option value="" selected="selected">'.$ros['name'].' '.$ros['lastname'].'</option>';
		endif;	
	}
	
	function getmembermanagement($idmember){
		global $db;
		$query = $db->query("SELECT * FROM `corporate_user` WHERE `idmember_list`='$idmember' and `status` = 0 ORDER BY `id` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):	
				
				        echo'<div class="da-child">
                            <p><strong>'.$row['name'].'</strong><br />
                                <table cellspacing="0" cellpadding="0" class="f-1200-14px clean-table">
                                	<tr>
                                    	<td>Divisi</td>
                                        <td>: '.$row['divisi'].'</td>
                                    </tr>
                                    <tr>
                                    	<td>Email</td>
                                        <td>: '.$row['email'].'</td>
                                    </tr>	
                                    <tr>
                                    	<td>Telepon</td>
                                        <td>: '.$row['phone'].'</td>
                                    </tr>
                                    <tr>
                                    	<td>Telepon Seluler</td>
                                        <td>: '.$row['mobile'].'</td>
                                    </tr>
                                    <tr>
                                        <td><a href="'.$GLOBALS['SITE_URL'].'change-password-user/'.$row['id'].'" class="nuke-fancied2">Ganti Password</a></td>
                                    </tr>									
                                </table>
                            </p>
                            <div class="btn-area clearfix">
                                <a href="'.$GLOBALS['SITE_URL'].'popup-edit-user/'.$row['id'].'" class="btn-edit f-psb nuke-fancied2">EDIT</a>
                                <a href="'.$GLOBALS['SITE_URL'].'delete-user/'.$row['id'].'" class="btn-hapus f-psb" onclick="return confirm(\'Anda yakin untuk menghapus user ini?\');">DELETE</a>
                            </div><!-- .btn-area -->
                        </div><!-- .da-child -->';	
			endwhile;
		else:
			echo'<p>Anda belum menambahkan user.</p>';		
		endif;		
	}
	
	function membercooporatelist(){
		global $db;
		$query = $db->query("SELECT * FROM `cara_register_corporate` WHERE `publish` = 1 ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			$pageno = 1;
			while($row = $query->fetch_assoc()):	
				if($pageno==1):
			         	echo'<div class="corp-howto-child no-m">';
                            echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" />';
                            echo'<div class="chc">';
                                echo'<h3 class="f-pb">'.$row['title'].'</h3>';
                                echo $row['description']; 
                            echo'</div><!-- .chc -->';
                        echo'</div><!-- .corp-howto-child -->';							
                        echo'<span class="corp-atau f-pb"><span>atau</span></span>';
				else:
				
						if($pageno==$jumpage):
								echo'<div class="corp-howto-child no-m-bottom">';
									echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" />';
									echo'<div class="chc">';
										echo'<h3 class="f-pb">'.$row['title'].'</h3>';
										echo $row['description']; 
									echo'</div><!-- .chc -->';
								echo'</div><!-- .corp-howto-child -->';	
						else:
								echo'<div class="corp-howto-child">';
									echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" />';
									echo'<div class="chc">';
										echo'<h3 class="f-pb">'.$row['title'].'</h3>';
										echo $row['description']; 
									echo'</div><!-- .chc -->';
								echo'</div><!-- .corp-howto-child -->';	
						endif;
						
				endif;

				$pageno++;						
			endwhile;
				
		endif;	
	}
	
	function getkomisimemberlist($idmember){
		global $db;
		$total = 0;
		$query = $db->query("SELECT *, DATE_FORMAT(`date`,'%d/%m/%Y') as tgl, DATE_FORMAT(`date`,'%H:%i:%s') as tgl2  FROM `komisilist_member` WHERE `idmember` = '$idmember' ORDER BY `id` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):	
				
				if($row['status']==1):
					$total = $total+$row['amount'];
				else:
					$total = $total-$row['amount'];
				endif;
				
			  	echo'<tr>';
                       echo'<td>'.$row['tgl'].'<br>'.$row['tgl2'].'</td>';
                       echo'<td>'.$row['description'].'';
					   		 if($row['status']==0 and $row['mutasi_status']=0):
							 	getstatusbayar($row['description'],$row['amount'],$row['date'],$idmember);
							 endif;
					   echo'</td>';
                       if($row['member_name']==""): echo'<td>-</td>'; else: echo'<td>'.$row['member_name'].'</td>'; endif;
					   
					  if($row['status']==1):
                       	    echo'<td><span class="f-green f-psb">Rp '.number_format($row['amount']).'</span></td>';
					  else:
					    	echo'<td><span class="f-red f-psb">- Rp '.number_format($row['amount']).'</span></td>';
					  endif;
					   
                      echo'<td style="text-align:right;"><span class="f-pb" style="font-size:1.125em;">Rp '.number_format($total).'</span></td>';
               echo'</tr>';
								
			endwhile;
		else:
			echo'<tr><td colspan="5">Record not found.</td></tr>';			
		endif;		

	}
	
	function getquotetaionlist($idmember,$iduserdefault,$member_filteruid,$statususer,$sort_filtertahun){
		global $db;
		$output = ''; $pagexx = 0; $total = 0;
		
		if($statususer==1):
			//superadmin
			if($member_filteruid>0):
				if($sort_filtertahun<>''):
					$que = $db->query("SELECT *, DATE_FORMAT(`date`,'%d/%m/%Y') as tgl, DATE_FORMAT(`date`,'%H:%i:%s') as tgl2, DATE_FORMAT(`expiry_date`,'%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` 
					WHERE `idmember_header` = '$idmember' and `id_user` = '$member_filteruid' and DATE_FORMAT(`date`,'%Y-%m') = '$sort_filtertahun' ORDER BY `date` DESC") or die($db->error);
				else:
					$que = $db->query("SELECT *, DATE_FORMAT(`date`,'%d/%m/%Y') as tgl, DATE_FORMAT(`date`,'%H:%i:%s') as tgl2, DATE_FORMAT(`expiry_date`,'%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` 
					WHERE `idmember_header`='$idmember' and `id_user` = '$member_filteruid' ORDER BY `date` DESC") or die($db->error);
				endif;			
			else:
				if($sort_filtertahun<>''):
					$que = $db->query("SELECT *, DATE_FORMAT(`date`,'%d/%m/%Y') as tgl, DATE_FORMAT(`date`,'%H:%i:%s') as tgl2, DATE_FORMAT(`expiry_date`,'%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` 
					WHERE `idmember_header` = '$idmember' and DATE_FORMAT(`date`,'%Y-%m') = '$sort_filtertahun' ORDER BY `date` DESC") or die($db->error);
				else:
					$que = $db->query("SELECT *, DATE_FORMAT(`date`,'%d/%m/%Y') as tgl, DATE_FORMAT(`date`,'%H:%i:%s') as tgl2, DATE_FORMAT(`expiry_date`,'%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` 
					WHERE `idmember_header`='$idmember' ORDER BY `date` DESC") or die($db->error);
				endif;			
			endif;
			//end superadmin
			
		else:		
			if($sort_filtertahun<>''):
				$que = $db->query("SELECT *, DATE_FORMAT(`date`,'%d/%m/%Y') as tgl, DATE_FORMAT(`date`,'%H:%i:%s') as tgl2, DATE_FORMAT(`expiry_date`,'%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` 
				WHERE `idmember_header` = '$idmember' and `id_user` = '$iduserdefault' and DATE_FORMAT(`date`,'%Y-%m') = '$sort_filtertahun' ORDER BY `date` DESC") or die($db->error);
			else:
				$que = $db->query("SELECT *, DATE_FORMAT(`date`,'%d/%m/%Y') as tgl, DATE_FORMAT(`date`,'%H:%i:%s') as tgl2, DATE_FORMAT(`expiry_date`,'%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` 
				WHERE `idmember_header`='$idmember' and `id_user` = '$iduserdefault' ORDER BY `date` DESC") or die($db->error);
			endif;
		endif;	
		
		while($row = $que->fetch_assoc()):
						
						$totalamount = $row['total_order']+$row['shippingcost']+$row['handling_fee'];
					
						//CEK DATE
						$waktu_sekarang	= date("Y-m-d");
						
						//date promo berakhir
						$pnt3=explode("-",$waktu_sekarang);	
						$tg_now=mktime(0,0,0,$pnt3[1],$pnt3[2],$pnt3[0]);
																		
						$pnt2=explode("-",$row['expiry_date']); 
						$tg_akhir=mktime(0,0,0,$pnt2[1],$pnt2[2],$pnt2[0]); 
												
						$hitung_akhir = (($tg_akhir-$tg_now)/86400);	
						
														
						echo'<div class="otbc">';
                        	echo'<div class="otbc-1">';
                            	echo'<div class="otbc-1-1">';
                            		echo'<span class="f-pb f-blue">'.getnamegeneral($row['id_user'],"corporate_user","name").' '.getnamegeneral($row['id_user'],"corporate_user","lastname").'</span>';
                                echo'</div><!-- .otbc-1-1 -->';
                                echo'<div class="otbc-1-2">';
                                	echo'<span class="f-pb">#DQ-'.sprintf('%06d',$row['id']).'</span>';
                                	echo'<span class="f-1200-14px">'.$row['tgl'].' - '.$row['tgl2'].'</span>';
									echo'<span class="bg-expiry">Expiry : '.$row['expiry_datetgl'].'</span>';
                                echo'</div><!-- .otbc-1-2 -->';
                            echo'</div><!-- .otbc-1 -->';
                            echo'<div class="otbc-2">';
                            	echo'<div class="otbc-2-1">';
                                	echo'<ul class="order-product">';
                                     		echo getproductdetailjcartquote($row['tokenpay']);
                                    echo'</ul><!-- .order-product -->';
                                    echo'<div class="order-price f-1200-14px">';
                                    	echo'TOTAL <strong>Rp '.number_format($totalamount).'</strong>';
                                    echo'</div><!-- .order-price -->';
                                    echo'<a href="'.$GLOBALS['SITE_URL'].'quotation-detail/'.$row['tokenpay'].'" class="f-1200-14px">Lihat detail quotation</a>';
									
									if($hitung_akhir>=0 and $row['status_order']==0):
										echo'<a href="'.$GLOBALS['SITE_URL'].'edit-quotation-list/'.$row['tokenpay'].'" class="f-1200-14px nuke-fancied2">Edit Quotation</a>';
									endif;
									
                                echo'</div><!-- .otbc-2-1 -->';
								
                                echo'<div class="otbc-2-2">';                                    
									// if($row['status_order']==1):
									// 	echo'<span class="expiry-qoute f-green">Quotation Sudah di Order</span>';	
									// else:
									// Commented by Andy on 16 May 2017 - Req by coh Adri
										echo'<a href="" onclick="javascript:window.open(\''.$GLOBALS['SITE_URL'].'print-quotation-list/'.$row['tokenpay'].'\', \'\', 
										 \'toolbar=,location=no,status=no,menubar=yes,scroll bars=yes, resizable=no,width=800,height=650\'); return false" class="btn btn-yellow no-margin f-psb f-1200-14px">PRINT</a>';
															 
										if($hitung_akhir>=0):
											echo'<a href="'.$GLOBALS['SITE_URL'].'checkout-quotation-list/'.$row['tokenpay'].'" class="btn btn-red no-margin f-psb f-1200-14px">CHECKOUT</a>';
										else:
											echo'<span class="expiry-qoute">Expired Draft Quotation!</span>';	
										endif;
									// endif;
									
                                echo'</div><!-- .otbc-2-2 -->';
								
                            echo'</div><!-- .otbc-2 -->';
                        echo'</div><!-- .otbc -->';
						
			   			
			$pagexx++;
		endwhile;
		
		if($pagexx==0): echo'<span class="notfound">Record not found.<br /><br /></span>'; endif;		
	}

	function getproductdetailjcartquote($tokenid){
		global $db;
		$output = '';
		$que = $db->query("SELECT `idproduct`,`name` FROM `draft_quotation_detail` WHERE `tokenpay`='$tokenid' ORDER BY `id` ASC");
		while($row = $que->fetch_assoc()):
			$imgname = getimagesdetail($row['idproduct']);
			$output.='<li><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($row['name']).'/'.$row['idproduct'].'" target="_blank"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$imgname.'" /></a></li>';
		endwhile;
		return $output;	
	}		
	
	function getnamegeneral($id,$tablename,$fieldname){
		global $db;
		$query = $db->query("SELECT `".$fieldname."` FROM `".$tablename."` WHERE `id` = '$id' ") or die($db->error);
		$row = $query->fetch_assoc();
		return $row[$fieldname];
	}
	
	
	function historycorporatesaldo($idmember,$sort_filtertahun){
		global $db;
		$output = ''; $pagexx = 0; $total = 0;
		
		if($sort_filtertahun<>''):
			$que = $db->query("SELECT *, DATE_FORMAT(`date`,'%d/%m/%Y') as tgl, DATE_FORMAT(`date`,'%H:%i:%s') as tgl2 FROM `deposit_member_corporatelist` WHERE `idmember`='$idmember' and DATE_FORMAT(`date`,'%Y-%m') = '$sort_filtertahun' 
			ORDER BY `id` ASC") or die($db->error);
		else:
			$que = $db->query("SELECT *, DATE_FORMAT(`date`,'%d/%m/%Y') as tgl, DATE_FORMAT(`date`,'%H:%i:%s') as tgl2 FROM `deposit_member_corporatelist` WHERE `idmember`='$idmember' ORDER BY `id` ASC") or die($db->error);
		endif;
		
		while($row = $que->fetch_assoc()):
					
				if($row['status']==1):
					$total = $total+$row['amount'];
				else:
					$total = $total-$row['amount'];
				endif;
				
			  	echo'<tr>';
                       echo'<td>'.$row['tgl'].'<br>'.$row['tgl2'].'</td>';
                       echo'<td>'.$row['description'].'</td>';
                       
					  if($row['status']==1):
                       	    echo'<td><span class="f-green f-psb">Rp '.number_format($row['amount']).'</span></td>';
							echo'<td>Credit</td>';
					  else:
					    	echo'<td><span class="f-red f-psb">Rp '.number_format($row['amount']).'</span></td>';
							echo'<td>Debit</td>';
					  endif;
					   
                      echo'<td style="text-align:right;"><span class="f-pb" style="font-size:1.125em;">Rp '.number_format($total).'</span></td>';
               echo'</tr>';
			   			
			$pagexx++;
		endwhile;
		
		if($pagexx==0): echo'<tr><td colspan="5">Record not found.</td></tr>'; endif;		
	}

	
	function getstatusbayar($desc,$total,$date,$idmember){
		global $db;
		$query = $db->query("SELECT `status_payment` FROM `claim_komis_member` WHERE `idmember_claim` = '$idmember' and `total` = '$total' and `date` = '$date'") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):	
			$row = $query->fetch_assoc();
			if($row['status_payment']=="Pending On Payment"):
				echo '<span class="status_pay2">Status: '.$row['status_payment'].'</span>';
			
			elseif($row['status_payment']=="Paid"):
				echo '<span class="status_pay">Status: '.$row['status_payment'].'</span>';
			
			else:
				echo '<span class="status_pay2">Status: '.$row['status_payment'].'</span>';
			endif;
		endif;
	}

	function getkomisimemberlistTotal($idmember){
		global $db;
		$total = 0;
		$query = $db->query("SELECT * FROM `komisilist_member` WHERE `idmember` = '$idmember' ORDER BY `id` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):	
				if($row['status']==1):
					$total = $total+$row['amount'];
				else:
					$total = $total-$row['amount'];
				endif;
					
			endwhile;	
		endif;		
		
		return $total;		
	}

	function gettotaldepositcorporate($idmember){
		global $db;
		$total = 0;
		$query = $db->query("SELECT * FROM `deposit_member_corporatelist` WHERE `idmember` = '$idmember' ORDER BY `id` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):	
				if($row['status']==1):
					$total = $total+$row['amount'];
				else:
					$total = $total-$row['amount'];
				endif;
					
			endwhile;	
		endif;		
		
		return $total;		
	}		
	
	function getstatusappurel($idmember){
		global $db;
		$que = $db->query("SELECT `id` FROM `affiliate_member` WHERE `idmember_aid` = '$idmember' ");
		$jumpage = $que->num_rows;
		return $jumpage;
	}

	function getstatusappurelPage($idmember){
		global $db;
		$que = $db->query("SELECT * FROM `affiliate_member` WHERE `idmember_aid` = '$idmember' ");
		$jumpage = $que->num_rows;
		if($jumpage>0):
			
			$row = $que->fetch_assoc();
			
			if($row['status']==1):
			echo'<span class="f-pb f-green">Active</span>
						<div class="affiliate-url-wrap">
						<!-- Trigger -->
						<button class="clip-btn aff-btn aff-ok" data-clipboard-target="#affiliate-url">
							Copy URL
						</button>
						<!-- Target -->
						<textarea id="affiliate-url" readonly>'.$GLOBALS['SITE_URL'].'affiliate/'.$row['token_affiliate'].'</textarea>
				 </div><!-- .affiliate-url-wrap -->';	
			else:
				echo'<span class="f-pb f-red">In Active</span>';
				echo'<div class="affiliate-url-wrap">
                    	<!-- Trigger -->
                        <button class="aff-btn aff-not-ok" disabled>
                            Copy URL
                        </button>
                        <!-- Target -->
                        <textarea id="affiliate-url" readonly>Please contact admin for more information</textarea>';				
			endif;
									
		else:
				echo'<span class="f-pb f-red">In Active</span>';
				echo'<div class="affiliate-url-wrap">
                    	<!-- Trigger -->
                        <button class="aff-btn aff-not-ok" disabled>
                            Copy URL
                        </button>
                        <!-- Target -->
                        <textarea id="affiliate-url" readonly>Please contact admin for more information</textarea>';	
		endif;
	}
	
	function getmemberAffiliate($idmember){
		global $db;
		$que = $db->query("SELECT `anggotanya_member_id` FROM `affiliate_memberid` WHERE `member_id` = '$idmember' ORDER BY `id` ASC");
		$jumpage = $que->num_rows;
		if($jumpage>0):
			$nomer  = 1;
			while($data = $que->fetch_assoc()):
					
					$query = $db->query("SELECT *,DATE_FORMAT(`date`,'%d/%m/%Y') as tgl FROM `member` WHERE `id` = '".$data['anggotanya_member_id']."' ");
					$row = $query->fetch_assoc();
					if($row['status']=="Active"): $Klass = 'f-green'; else: $Klass = 'f-red'; endif;
				    echo'<tr>';
                           echo'<td>'.$nomer.'</td>';
                           echo'<td>'.$row['name'].' '.$row['lastname'].'</td>';
                           echo'<td>'.$row['tgl'].'</td>';
                           echo'<td><span class="'.$Klass.' f-psb">'.$row['status'].'</span></td>';
                    echo'</tr>';
					$nomer++;
			endwhile;
		else:
			echo'<tr><td colspan="4">Record not found.</td></tr>';	
		endif;
	}
		
	//order list member
	function getorderlistredeem($idmember,$sort_filtertahun){
		global $db;
		$output = ''; $pagexx = 0;
		
		if($sort_filtertahun<>''):
			$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d/%m/%Y, %h:%i:%s') as tgl FROM `order_header_redeemlist` WHERE `idmember`='$idmember' and DATE_FORMAT(`date`,'%Y-%m') = '$sort_filtertahun' ORDER BY `date` DESC") or die($db->error);
		else:
			$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d/%m/%Y, %h:%i:%s') as tgl FROM `order_header_redeemlist` WHERE `idmember`='$idmember' ORDER BY `date` DESC") or die($db->error);
		endif;
		
		while($row = $que->fetch_assoc()):
					
					if($row['status_delivery']=="Pending On Delivery"):
						$KLass = '<strong style="color:#FF9933;">Menunggu Pengiriman</strong>';
					elseif($row['status_delivery']=="Shipped"):
						$KLass = '<strong style="color:#339966;">Terkirim</strong>';
					else:
						$KLass = '<strong style="color:#FF9933;">'.$row['status_delivery'].'</strong>';	
					endif;	
					
					$nameprod = $row['sku_product'].' - '.$row['name'];
			
			          $output.='<div class="otbc">';
                        	$output.='<div class="otbc-1">';
                            	$output.='<span class="f-pb">#'.sprintf('%06d',$row['id']).'</span>';
                                $output.='<span class="f-1200-14px">'.$row['tgl'].'</span>';
                            $output.='</div><!-- .otbc-1 -->';
                            $output.='<div class="otbc-2">';
                            	$output.='<div class="otbc-2-1">';
                                	
									$output.='<ul class="order-product">';
                                    	$output.='<li><a href="'.$GLOBALS['SITE_URL'].'product-redeem-detail/'.replace($row['name']).'/'.$row['idproduct'].'" class="nuke-fancied2">'.getimageslistredeem($row['idproduct'],$nameprod).'</a></li>';
                                    $output.='</ul><!-- .order-product -->';
									
                                    $output.='<div class="order-price f-1200-14px">';
                                   		$output.='Point used: <strong>'.number_format($row['orderamount']).'</strong>';
                                    $output.='</div><!-- .order-price -->';
                                    $output.='<a href="'.$GLOBALS['SITE_URL'].'redeem-detail/'.$row['tokenpay'].'" class="f-1200-14px">Lihat detail redeem</a>';
                                $output.='</div><!-- .otbc-2-1 -->';
                                $output.='<div class="otbc-2-2">';
                                	$output.='<span class="order-stat"><span>STATUS: </span>'.$KLass.'</span>';
                                $output.='</div><!-- .otbc-2-2 -->';
                            $output.='</div><!-- .otbc-2 -->';
                        $output.='</div><!-- .otbc -->';

							
			$pagexx++;	
		endwhile;
		echo $output;
		
		if($pagexx==0): echo'<p style="padding-left:10px; padding-top:10px; color:#ffa827;">You haven\'t add any redeem order list.</p>'; endif;		
	}
	
	function getimageslistredeem($idprod,$nameprod){
		global $db;
		$query = $db->query("SELECT * FROM `redeem_product` WHERE `id` = '$idprod' ") or die($db->error);
		$row = $query->fetch_assoc();
		if($row['image']==""):
			return $nameprod;
		else:
			return '<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$nameprod.'" />';
		endif;
	}

	
	function gettotalpintmember($idmember){
		global $db;

			echo'<div class="table-wrap">
                    <table cellspacing="0" cellpadding="0" class="blue-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th style="line-height:1.5em; width:50%;">Info</th>
                                <th>Jumlah Point</th>
                                <th style="text-align:right;">Balance Point</th>
                            </tr>
                        </thead>
                        <tbody>';


		$batas = 20;
		if(isset($_GET['halaman'])):
			$halaman = $_GET['halaman'];
		else:
			$halaman = 1;	
		endif;
		
		if(empty($halaman)):
			$posisi=0;
			$halaman=1;	
		else: 
			$posisi=($halaman-1) * $batas; 
		endif; 						
	
		$sql2 = $db->query("SELECT `id` FROM `rewads_point_member` WHERE `idmember` = '$idmember' ORDER BY `id` ASC") or die($db->error);
		$jmldata = $sql2->num_rows;
		$jmlhalaman = ceil($jmldata/$batas); 
		$file = "".$GLOBALS['SITE_URL']."point-reward";	
					
		$total = 0; $totalsub = 0;
		$query = $db->query("SELECT *, DATE_FORMAT(`date`,'%d/%m/%Y') as tgl, DATE_FORMAT(`date`,'%H:%i:%s') as tgl2 FROM `rewads_point_member` WHERE `idmember` = '$idmember' ORDER BY `id` ASC  LIMIT $posisi,$batas") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):						
			while($row = $query->fetch_assoc()):	
				
				//get first total
				if($halaman>1):
					$totalsub = gettotalpointrewards($idmember,$batas,$halaman);
				endif;	
				$total = $total+$totalsub;
				
			
				if($row['status']==1):
							$total = $total+$row['point'];
				        	echo'<tr>
                                <td>'.$row['tgl'].'<br>'.$row['tgl2'].'</td>
                                <td>'.$row['description'].'</td>
                                <td><span class="f-green f-psb">'.number_format($row['point']).' point</span></td>
                                <td style="text-align:right;"><span class="f-pb" style="font-size:1.125em;">'.number_format($total).' point</span></td>
                            </tr>';
				else:
							$total = $total-$row['point'];
				        	echo'<tr>
                                <td>'.$row['tgl'].'<br>'.$row['tgl2'].'</td>
                                <td>'.$row['description'].'</td>
                                <td><span class="f-red f-psb">- '.number_format($row['point']).' point</span></td>
                                <td style="text-align:right;"><span class="f-pb" style="font-size:1.125em;">'.number_format($total).' point</span></td>
                            </tr>';				
				endif;
					
			endwhile;	
		else:
             echo'<tr><td colspan="4">Record not found</td></tr>';		
		endif;	
		
		echo'
                        </tbody>
                    </table>
                </div><!-- .table-wrap -->
                <br>';

                            if($jmldata>0): 
                                echo'<div class="product-list-bottom">';
                                 echo'<div class="nuke-pagination">';
                                                 
                                        if($halaman>1):
                                            $previous=$halaman-1;
											echo'<a href="'.$file.'/1" class="np-first np-bigger">&laquo;</a>';
                                            echo'<a href="'.$file.'/'.$previous.'" class="np-prev np-bigger">&lsaquo;</a>';
                                        else: 
											echo'<a href="#" class="np-first np-bigger">&laquo;</a>';
                                            echo'<a href="#" class="np-prev np-bigger">&lsaquo;</a>';
                                        endif;		
                                                                                                                                
                                        for($y=1;$y<=$jmlhalaman;$y++){
                                            if($y!=$halaman): 
                                                 echo'<a href="'.$file.'/'.$y.'">'.$y.'</a>';
                                            else: 
                                                 echo'<a href="#" class="np-active">'.$y.'</a>';	
                                            endif;	
                                        }
                                                                                                                                              
                                        if($halaman < $jmlhalaman):
                                             $next=$halaman+1;
                                             echo'<a href="'.$file.'/'.$next.'" class="np-last np-bigger">&rsaquo;</a>';
											 echo'<a href="'.$file.'/'.$jmlhalaman.'"  class="np-last np-bigger" />&raquo;</a>';
                                        else: 
                                             echo'<a href="#" class="np-last np-bigger">&rsaquo;</a>';
											  echo'<a href="#"  class="np-last np-bigger" />&raquo;</a>';
                                        endif;
                                                                
                                 echo'</div><!-- .nuke-pagination -->';	
                            echo'</div><!-- .product-list-bottom -->'; 		
                         endif;
						 							
	}
	
	function gettotalpointrewards($idmember,$batas,$halaman){
		global $db;
		$batasbaru = $batas*($halaman-1);
		$total = 0;
		$query = $db->query("SELECT * FROM `rewads_point_member` WHERE `idmember` = '$idmember' ORDER BY `id` ASC LIMIT 0,$batasbaru") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):	
				if($row['status']==1):
					$total = $total+$row['point'];
				else:
					$total = $total-$row['point'];
				endif;
					
			endwhile;	
		endif;		
		
		return $total;
	}
	
	function gettotalpintmemberTotal($idmember){
		global $db;
		$total = 0;
		$query = $db->query("SELECT * FROM `rewads_point_member` WHERE `idmember` = '$idmember' ORDER BY `id` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):	
				if($row['status']==1):
					$total = $total+$row['point'];
				else:
					$total = $total-$row['point'];
				endif;
					
			endwhile;	
		endif;		
		
		return $total;
	}
		
	function getstatuspayment($idbank,$metodepayment){
		global $db;
		if($metodepayment=="BANK TRANSFER"):
			echo 'Deposit by Transfer '.getbanknamelist($idbank);
		else:
			echo $metodepayment;
		endif;	
	}
	
	function getbanknamelist($id){
		global $db;
		$query = $db->query("SELECT `bank_name` FROM `bank_account` WHERE `id` = '$id' ") or die($db->error);
		$row = $query->fetch_assoc();
		return $row['bank_name'];		
	}
	
	
	function getjumlahmutasilist(){
		global $db;
		$query = $db->query("SELECT * FROM `membership_deposit_adm` ORDER BY `price` ASC") or die($db->error);
		while($row = $query->fetch_assoc()):	
			echo'<option value="'.$row['id'].'">'.$row['title'].' - Rp. '.number_format($row['price']).'</option>';
		endwhile;
	}
	
	function gettanggalsort($tahun,$bulan,$tambahjum){
		$bp = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'Nopember','12'=>'Desember');
		 
		$lastN = mktime(0, 0, 0, $bulan - $tambahjum, 01, $tahun); //MM-DD-YYYY
		$datesampai	= date("Y-m-d", $lastN);		
		$datekahir = $datesampai;	
		$itemlist = explode("-",$datekahir);	
		$tanggalbulan = $bp[$itemlist[1]];
		echo $tanggalbulan.' '.$itemlist[0];	
	}

	function gettanggalsort2($tahun,$bulan,$tambahjum){
		$bp = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'Nopember','12'=>'Desember');
		 
		$lastN = mktime(0, 0, 0, $bulan - $tambahjum, 01, $tahun); //MM-DD-YYYY
		$datesampai	= date("Y-m-d", $lastN);		
		$datekahir = $datesampai;	
		$itemlist = explode("-",$datekahir);	
		echo $itemlist[0].'-'.$itemlist[1];	
	}

	function gettanggalsort3($tahun,$bulan,$tambahjum,$sort_filtertahun){
		$bp = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'Nopember','12'=>'Desember');
		 
		$lastN = mktime(0, 0, 0, $bulan - $tambahjum, 01, $tahun); //MM-DD-YYYY
		$datesampai	= date("Y-m-d", $lastN);		
		$datekahir = $datesampai;	
		$itemlist = explode("-",$datekahir);	
		$dataLuist = $itemlist[0].'-'.$itemlist[1];	
		
		if($sort_filtertahun==$dataLuist): echo'selected="selected"'; endif;
	}
			
	//fixco mart function
	function getproductlist($idpord){
	
		global $db;
		$query = $db->query("SELECT * FROM `product` WHERE 1=1 and `id` = '$idpord' ") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			$row = $query->fetch_assoc();
			
			echo'<div class="item idproditemlist-'.$row['id'].'">';
				   echo'<div class="compare-value compare-image cvalue-1">';
				   echo'<div class="compare-image-content">';
						echo'<a href="#" class="compare-remove removecompare" id="listprod-'.$row['id'].'">Remove</a>';
				   echo'<div class="img-wrap"><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($row['name']).'/'.$row['id'].'"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['name'].'" /></a></div>';
							 echo'<a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($row['name']).'/'.$row['id'].'">'.$row['name'].'</a>';
					echo'</div><!-- .compare-image-content -->';
					echo'</div><!-- .compare-image -->';
					
					echo'<div class="compare-value compare-harga cvalue-2">';
						echo'<div class="compare-harga-content">';
							if($row['discount_value']>0):		
								$diskonval1 = ($row['price']*$row['discount_value'])/100;
								$diskonval2 = round($diskonval1);
								$diskonval3 = $row['price']-$diskonval1;
								echo'<span style="line-height:1.3em; display:block; text-align:left; margin-bottom:10px;"><del style="font-size:13px; color:#777;">Rp '.number_format($row['price']).',-</del> <br /> Rp '.number_format($diskonval3).',-</span>';
							else:
								echo'<span style="display:block; text-align:left; margin-bottom:10px;">Rp '.number_format($row['price']).',-</span>';	
							endif;
						echo'</div><!-- .compare-harga-content -->';
					 echo'</div><!-- .compare-value -->';
					 

					  $brand = generalselect("brand","name"," `id` = '".$row['brand']."' ");
					  if($brand!=""): $brandList = $brand; else: $brandList = '-'; endif;					 
					  echo'<div class="compare-value cvalue-3">';
						echo'<span class="vCenter">'.$brandList.'</span>';
					  echo'</div><!-- .compare-value -->';
					  
					  $beratprod = generalselect("product_detail","weight"," `idproduct_header` = '".$row['id']."' ");
					  if($beratprod>0 or $beratprod!=""): $beratlist = number_format($beratprod).' gram'; else: $beratlist = '-'; endif;
					  
					  echo'<div class="compare-value cvalue-6">';
						 echo'<span class="vCenter">'.$beratlist.'</span>';
					  echo'</div><!-- .compare-value -->';
					  
					  echo'<div class="compare-value compare-button">';
						  echo getbuttonbeliprod($row['id']);
					  echo'</div><!-- .compare-value -->';
			 echo'</div><!-- .item -->';	
		endif; 		
	}
	
	function productescalator(){
		global $db;
		$query = $db->query("SELECT * FROM `product_escalator` WHERE 1=1 and `publish`=1 ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			$nomer = 1;
			while($row = $query->fetch_assoc()):	
				
				if(strtolower($row['type_list'])=="type 1 (image: 322x556px)"): 
					$Klass = 'style-1'; 
					$type3 = 0;
				elseif(strtolower($row['type_list'])=="type 2 (imge: 877x278px)"): 
					$Klass = 'style-2'; 
					$type3 = 0;
				elseif(strtolower($row['type_list'])=="type 3 (image: 584x278px)"): 
					$Klass = 'style-3'; 
					$type3 = 1;
				endif;
				
				
			 $tanggaUrut1 = $nomer+1;
			 $tanggaUrut2 = $nomer-1;
			 	
			 echo'<div id="iec-'.$nomer.'" class="ie-child '.$Klass.'">';
                	echo'<div class="iec-header">';
                    	echo'<h2 class="f-pb txt-up">'.$row['title'].'</h2>';
                        echo'<div class="iec-escalator">';
							if($jumpage==$nomer):
								echo'<a class="iec-next iec-scroll">Next</a>';
							else:
                        		echo'<a href="#iec-'.$tanggaUrut1.'" class="iec-next iec-scroll">Next</a>';
							endif;
							
                            if($nomer==1): 
								echo'<a class="iec-prev iec-scroll">Previous</a>'; 
							else:
								echo'<a href="#iec-'.$tanggaUrut2.'" class="iec-prev iec-scroll">Previous</a>'; 
							endif;
							
                        echo'</div><!-- .iec-escalator -->';
                    echo'</div><!-- .iec-header -->';
                    echo'<div class="iec-content">';
                        echo'<div class="iec-2"><a href=""><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['title'].'" class="lazyload" data-expand="-10" /></a></div>';
                    	echo'<div class="iec-1">';
                        	echo'<div class="iec-1-content">';
                                if($row['brand_image']<>''): echo'<div class="img-wrap"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['brand_image'].'" alt="'.$row['title'].'" /></div>'; endif;
                                echo $row['short_description'];
                            echo'</div><!-- .iec-1-content -->';
                        echo'</div><!-- .iec-1 -->';
                        echo'<div class="iec-3">';
                        	echo'<div class="iec-carousel">';
								getproductlistlantai($row['id'],$type3);
                            echo'</div><!-- .iec-carousel -->';
                        echo'</div><!-- .iec-3 -->';
                    echo'</div><!-- .iec-content-->';				
				echo'</div><!-- .ie-child -->';
					
				$nomer++;	
			endwhile; 
		else:
			echo'<div class="notfound">Record not found.</div>';
		endif;	
	}
	
	function getproductlistlantai($idlantai,$type3){
		global $db;
		$query = $db->query("SELECT `id_product_list` FROM `product_escalator_list` WHERE `idheader` = '$idlantai' ORDER BY `sortnumber` DESC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):

			$totalpage = 1; $totalpage2 = 1; $nameprod = '';  $imageprod = '';
			while($row = $query->fetch_assoc()):
				if($totalpage==1): echo'<div class="item">'; endif;
				if($totalpage==1 and $type3==1): $Klass = 'first'; else: $Klass = ''; endif;	
				
					$nameprod = generalselect("product","name"," `id` = '".$row['id_product_list']."' ");
					$imageprod = generalselect("product","image"," `id` = '".$row['id_product_list']."' ");
					
					echo'<div class="iec-item '.$Klass.'">';
						 echo'<div class="img-wrap"><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($nameprod).'/'.$row['id_product_list'].'">
						 <img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$imageprod.'" alt="'.$nameprod.'" class="lazyload" data-expand="-10" /></a></div>';
						 echo'<h3 class="f-pb"><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($nameprod).'/'.$row['id_product_list'].'"><span>'.$nameprod.'</span></a></h3>';
					echo'</div><!-- .iec-item -->';
							
				if($totalpage==4 or $totalpage2==$jumpage): echo'</div><!-- .item -->'; $totalpage = 0; endif;

				$totalpage++; $totalpage2++;
			endwhile;	
		endif;				
	}

	function productescalatornew(){
		global $db;
		$query = $db->query("SELECT * FROM `product_escalator` WHERE 1=1 and `publish`=1 ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;

		if($jumpage>0):
			while($row = $query->fetch_assoc()):

				if(strtolower($row['type_list'])=="type 1 (image: 322x556px)"): 
					$Klass = 'style-1'; 
					$type3 = 0;
				elseif(strtolower($row['type_list'])=="type 2 (imge: 877x278px)"): 
					$Klass = 'style-2'; 
					$type3 = 0;
				elseif(strtolower($row['type_list'])=="type 3 (image: 584x278px)"): 
					$Klass = 'style-3'; 
					$type3 = 1;
				endif;


				echo '<div class="product-floor">';
					echo '<div class="pf-heading">
							<h2 class="f-pb">'.$row['title'].'</h2>
						  </div><!-- .pf-heading -->';
					echo '<div class="pf-body">';
                		echo '<div class="pfb-sidebar">
		                        <div class="brand">';
		                        if($row['brand_image']<>''): 
		                        	echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['brand_image'].'" alt="'.$row['title'].'" />'; 
		                        endif;
		                echo '</div><!-- .brand -->
		                        <div class="pfb-carousel-wrap">
		                            <div class="pfb-carousel">
		                                <div class="item">
		                                    <a href=""><img src="images/ads/prod-ads-1.jpg" alt="ADS TITLE HERE" class="lazyload" data-expand="-10"></a>
		                                </div><!-- .item -->
		                                <div class="item">
		                                    <a href=""><img src="images/ads/prod-ads-2.jpg" alt="ADS TITLE HERE" class="lazyload" data-expand="-10"></a>
		                                </div><!-- .item -->
		                            </div><!-- .pfb-carousel -->
		                        </div><!-- .pfb-carousel-wrap -->
		                    </div><!-- .pfb-sidebar -->';

			         echo '<div class="pfb-content">
	                       	<div class="pfb-content-carousel-wrap">
	                        	<div class="pfb-content-carousel">';
	                     			getproductlistlantainew($row['id'],$type3);
	                 		echo '</div><!-- .pfb-content-carousel -->';
	                 	echo '</div><!-- .pfb-content-carousel-wrap -->';
	                 echo '</div><!-- .pfb-content -->';
                 echo '</div><!-- .pf-body -->';
             echo '</div><!-- .product-floor -->';
			endwhile; 
		else:
			echo'<div class="notfound">Record not found.</div>';
		endif;	
	}

	function getproductlistlantainew($idlantai,$type3){
		global $db;
		$query = $db->query("SELECT `id_product_list` FROM `product_escalator_list` WHERE `idheader` = '$idlantai' ORDER BY `sortnumber` DESC") or die($db->error);
		$jumpage = $query->num_rows;
		$ctr = 1;
		if($jumpage>0):

			$nameprod = '';  $imageprod = ''; $titleprod = ''; $discountprod = ''; $priceprod = '';
			while($row = $query->fetch_assoc()):
				$nameprod = generalselect("product","name"," `id` = '".$row['id_product_list']."' ");
				$imageprod = generalselect("product","image"," `id` = '".$row['id_product_list']."' ");
				$titleprod = generalselect("product_detail","title"," `idproduct_header` = '".$row['id_product_list']."' ");
				$discountprod = generalselect("product","discount_value"," `id` = '".$row['id_product_list']."' ");
				$priceprod = generalselect("product","price"," `id` = '".$row['id_product_list']."' ");
				if($ctr%2 == 1){
					echo '<div class="item">';
						echo '<div class="pfb-product">';
				}else if($ctr%2 == 0){
					echo '<div class="pfb-product last">';
				}
                	echo '<div class="content">
                             <div class="ngc-media">
                                <a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($nameprod).'/'.$row['id_product_list'].'"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$imageprod.'" alt="'.$titleprod.'" class="lazyload" data-expand="-10"></a>
                            </div><!-- .ngc-media -->
                            <div class="ngc-text">
                                <h3 class="f-pr"><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($nameprod).'/'.$row['id_product_list'].'">'.$nameprod.'</a></h3>';

                                if($discountprod>0):
								
									$diskonval1 = ($priceprod*$discountprod)/100;
									$diskonval2 = round($diskonval1);
									$diskonval3 = $priceprod-$diskonval1;
				
									echo '<p class="f-pb">Rp '.number_format($priceprod).',-
												<span class="old-price">Rp '.number_format($diskonval3).',-</span></p>';
								else:
									echo '<p class="f-pb">Rp '.number_format($priceprod).',-</p>';	
								endif;
                                $idprod = $row['id_product_list'];
                                $quepjum = $db->query("SELECT `id` FROM `product_detail` WHERE `idproduct_header` = '$idprod' and `stock` > 0 and `publish` = 1 
								ORDER BY `sortnumber` ASC ");
								$jumlahprod = $quepjum->num_rows;
								
								$nameprod = getnameprodList($idprod);
											
								$query2 = $db->query("SELECT `id`,`soldout_contactus` FROM `product` WHERE `id`='$idprod' ");
								$row2 = $query2->fetch_assoc();
								$totalstock = gettotalstock($row2['id']);

								$quep = $db->query("SELECT `id` FROM `product_detail` WHERE `idproduct_header` = '$idprod' and `stock` > 0 and `publish` = 1 ORDER BY `sortnumber` ASC ");
								$res = $quep->fetch_assoc();
										
								if($totalstock>0):
									if($jumlahprod>1):
									echo '<a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($nameprod).'/'.$idprod.'" class="btn btn-red no-margin f-psb small"><span style="font-size:12px;">PICK SIZE</span></a>';			
									else:
									echo '<a href="" class="btn-atc small add_to_cartbtnList" id="listprod-'.$row2['id'].'-'.$res['id'].'"><span style="font-size:12px;">ADD TO CART</span></a>';
									endif;
								else:
									if(strtolower($row2['soldout_contactus'])=="yes"):
										echo '<a href="'.$GLOBALS['SITE_URL'].'contact" class="btn btn-red no-margin f-psb small"><span class="contactkami" style="font-size:12px;">CONTACT US</span></a>';
									else:
										echo '<a class="btn soldoutbtn no-margin f-psb small"><span style="font-size:12px;">SOLD OUT</span></a>';
									endif;
								endif;
                      echo '</div><!-- .ngc-text -->
                        </div><!-- .content -->
                    </div><!-- .pfb-product -->';

                if($ctr%2 == 0){
                	echo '</div><!-- .item -->';
                }

                $ctr+=1;
			endwhile;
			if($ctr%2 == 0){
            	echo '</div><!-- .item -->';
            }
		endif;				
	}

	function bloglistindex(){
		global $db;
		$category = Array();
		$query_header = $db->query("SELECT `title`,`description` FROM `blog_header` WHERE `publish` = 1") or die($db->error);
		$jumpage_header = $query_header->num_rows;

		if($jumpage_header>0):
			while($row = $query_header->fetch_assoc()):
				echo '<h2 class="ngc-maintitle n-align-center">'.$row['title'].'</h2>
			            <div class="lr-auto max-580">
			                <p class="ngc-intro n-align-center">'.$row['description'].'</p>
			            </div>';
			endwhile;
		endif;

		$query_cat = $db->query("SELECT `id`, `category_name` FROM `blog_category` WHERE `publish` = 1") or die($db->error);
		$jumpage_cat = $query_cat->num_rows;

		if($jumpage_cat>0):
			while($row = $query_cat->fetch_assoc()):
				$category[$row['id']] = $row['category_name'];
			endwhile;
		endif;


		$query = $db->query("SELECT `header`,`description`,`post_date`,`image`,`blog_url`,`id_blog_category` FROM `blog_detail` WHERE `publish` = 1 ORDER BY `post_date` DESC LIMIT 4") or die($db->error);
		$jumpage = $query->num_rows;

		if($jumpage>0):
			echo '<div class="row same-height medium-gutter">';
			while($row = $query->fetch_assoc()):
				$temp_desc = explode(".",$row['description']);
				echo '<div class="grid-child n-768-1per2">
                        <div class="blog-child">
                            <div class="ngc-media">
                                <a href="'.$GLOBALS['SITE_URL'].'blog-detail/'.$category[$row['id_blog_category']].'/'.$row['blog_url'].'">
                                    <img src="'.$GLOBALS['UPLOAD_FOLDER'].$row['image'].'" alt="'.$row['header'].'" class="lazyload" data-expand="-10">
                                    <span class="blog-date">
                                        <span class="large">'.date("d",strtotime($row['post_date'])).'</span>
                                        '.date("M",strtotime($row['post_date'])).'
                                    </span>
                                </a>
                            </div><!-- .ngc-media -->
                            <div class="ngc-text">
                                <h3 class="ngc-title">
                                    <a href="'.$GLOBALS['SITE_URL'].'blog-detail/'.$category[$row['id_blog_category']].'/'.$row['blog_url'].'">
                                        '.$row['header'].'
                                    </a>
                                </h3>';
                        echo '<p>';
                        for ($i=0; $i < 3; $i++) { 
                        	echo $temp_desc[$i] . ". ";
                        }
                        echo '</p>';
                        echo   '<a href="'.$GLOBALS['SITE_URL'].'blog-detail/'.$category[$row['id_blog_category']].'/'.$row['blog_url'].'" class="read-more">Read More <span class="fa fa-angle-right"></span></a>
                            </div><!-- .ngc-text -->
                        </div><!-- .blog-child -->
                    </div><!-- .grid-child -->';
			endwhile;
			echo '</div><!-- .row -->';
		endif;
	}
	
	function getprodutterbaru(){
		global $db;
		$ctr = 1;
		$query = $db->query("SELECT `product`.`id` as prodid FROM `product` WHERE 1=1 and `product`.`publish`=1 and ( `product_unggulan` != 'Produk Unggulan' AND `product_unggulan` != 'produk unggulan' AND `product_terbaik` != 'Produk Terbaik' or `product_terbaik` != 'produk terbaik') ORDER BY `sortnumber` DESC LIMIT 0,30") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				if(($ctr%2) == 1){
					echo '<div class="item">';
					getitemproductlistItem($row['prodid']);
					echo '<br>';
					$ctr += 1;
				}else if(($ctr%2) == 0){
					getitemproductlistItem($row['prodid']);
					echo '</div>';
					$ctr += 1;
				}
			endwhile;
		else:
			echo'<div class="item">Record not found.</div>';
		endif;	
	}
	
	function getprodutterunggulan(){
		global $db;
		$ctr = 1;
		$query = $db->query("SELECT `product`.`id` as prodid FROM `product` WHERE 1=1 and ( `product_unggulan` = 'Produk Unggulan' or `product_unggulan` = 'produk unggulan' )  and `product`.`publish`=1 ORDER BY `sortnumber` DESC LIMIT 0,30") 
		or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				if(($ctr%2) == 1){
					echo '<div class="item">';
					getitemproductlistItem($row['prodid']);
					echo '<br>';
					$ctr += 1;
				}else if(($ctr%2) == 0){
					getitemproductlistItem($row['prodid']);
					echo '</div>';
					$ctr += 1;
				}
			endwhile;
		else:
			echo'<div class="item">Record not found.</div>';
		endif;	
	}

	function getproduterbaik(){
		global $db;
		$ctr = 1;
		$query = $db->query("SELECT `product`.`id` as prodid FROM `product` WHERE 1=1 and ( `product_terbaik` = 'Produk Terbaik' or `product_terbaik` = 'produk terbaik' )  and `product`.`publish`=1 ORDER BY `sortnumber` DESC LIMIT 0,30") 
		or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):	
				if(($ctr%2) == 1){
					echo '<div class="item">';
					getitemproductlistItem($row['prodid']);
					echo '<br>';
					$ctr += 1;
				}else if(($ctr%2) == 0){
					getitemproductlistItem($row['prodid']);
					echo '</div>';
					$ctr += 1;
				}
			endwhile;
		else:
			echo'<div class="item">Record not found.</div>';
		endif;	
	}
		
	function productlistdetail($idprod,$status){
		global $db;
		$query = $db->query("SELECT * FROM `product_detail` WHERE `idproduct_header` = '$idprod' and `publish` = 1 ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):	
				if($row['stock']>0):		
					echo'<option value="'.$row['id'].'">'.$row['title'].'</option>';
				else:
					echo'<option value="'.$row['id'].'" disabled="disabled" style="background:#ddd;">'.$row['title'].' (Sold Out)</option>';
				endif;	
			endwhile; 
		else:
			echo'<option value="" selected="selected">Pilih '.$status.'</option>';
		endif;	
	}
	
	function getproddetaillist($idprod){
		global $db;
		$query = $db->query("SELECT `id` FROM `product_detail` WHERE `idproduct_header` = '$idprod' and `publish` = 1 ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			$row = $query->fetch_assoc();
			return $row['id'];
		else:
			return 0;
		endif;	
	}
	
	function getprodpriceFirst($idprod,$iddetail){
		global $db;
		
		//select prd
		$query = $db->query("SELECT * FROM `product` WHERE `id`='$idprod' ");
		$row = $query->fetch_assoc();
		
		//select prd detail
		$quepp = $db->query("SELECT * FROM `product_detail` WHERE `id`='$iddetail' and `idproduct_header`='$idprod' ");
		$ros = $quepp->fetch_assoc();
		
		$diskonval1 = 0; $diskonval2 = 0;
		if($row['discount_value']>0):
			$diskonval1 = ($ros['price']*$row['discount_value'])/100;
			$diskonval2 = round($diskonval1);
			$price = $ros['price']-$diskonval1;
		
			echo'<span class="old-price">Rp '.number_format($ros['price']).',-</span>';
			echo'<span class="prod-price f-pb">Rp '.number_format($price).',-</span>';		
		else:	
		  echo'<span class="prod-price f-pb">Rp '.number_format($ros['price']).',-</span>';
		endif;
	}
	
	function getprodpriceFirsttbale($idprod,$iddetail){
		global $db;
		
		//select prd
		$query = $db->query("SELECT * FROM `product` WHERE `id`='$idprod' ");
		$row = $query->fetch_assoc();
		
		//select prd detail
		$quepp = $db->query("SELECT * FROM `product_detail` WHERE `id`='$iddetail' and `idproduct_header`='$idprod' ");
		$ros = $quepp->fetch_assoc();
		
		echo'
		
		<table cellspacing="0" cellpadding="0" class="pdtr-table">
		
		<tr>
		
			<td>SKU Number</td>
		
			<td>: '.$ros['sku_product'].'</td>
		
		</tr>
		
		
		
		<tr>
		
		<td>Manufacture</td>
		
		<td>: '.getnamegeneral($row['brand'],'brand','name').'</td>
		
		</tr>
		
		</table>
		
										   
		
		<table cellspacing="0" cellpadding="0" class="pdtr-table last">
		
		<tr>
		
			<td>Model</td>
		
			<td>: '.$ros['title'].'</td>
		
		</tr>
		
		
		
		<tr>
		
		   <td>Berat Pengiriman</td>
		
		   <td>: '.number_format($ros['weight']).' gram</td>
		
		</tr>
		
		</table>
		
		';		
	}
	
	function gettotalstock($idprod){
		global $db;
		$query = $db->query("SELECT `id` FROM `product_detail` WHERE `idproduct_header` = '$idprod' and `stock` > 0 and `publish` = 1 ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		return $jumpage;		
	}
	
	function getdatalistfilterprod($Listmaster_attribute,$idkat,$idsubkat,$idsublevel){
		global $db;
		if($Listmaster_attribute=="" or ( $idkat==0 and $idsubkat==0 and $idsublevel==0) ):
		
		else:
			$hasilprod = ''; $hasilprodText = '';
			$itemdata = explode("#",$Listmaster_attribute);
			$jumdata = count($itemdata);
			for($i = 0; $i<$jumdata; $i++){
				$listdata = explode("-",$itemdata[$i]);
				$idmaster = $listdata[0];
				$idattribute = $listdata[1];
				$attibutename = generalselect("attribute_product","attribute_value"," `id` = '$idattribute' ");
				
				$bandingstatusattribute = bandingfilterattribute($idmaster,$idkat,$idsubkat,$idsublevel);
				if($bandingstatusattribute==1):
					$hasilprod.= getidprodlistdataitem($idmaster,$attibutename);
				endif;
			}
			
			if($hasilprod!=""):
				$hasilprod.="LASTLISTDATA";
				$hasilprodText = replacedatastar($hasilprod);
				
				if($hasilprodText!=""): return " and ( ".$hasilprodText." )"; endif;
			endif;
		endif;		
	}
	
	function bandingfilterattribute($idmaster,$idkat,$idsubkat,$idsublevel){
		global $db;
		$jumdata = 0;

		if($idsublevel>0):

				$query = $db->query("SELECT `id` FROM `maste_attribute_product` WHERE `idsubcate_level` = '$idsublevel' and `publish` = 1 ORDER BY `sortnumber` ASC") or die($db->error);
				while($row = $query->fetch_assoc()):
					  if($idmaster==$row['id']): $jumdata = 1; endif;	 
				endwhile;
					
		elseif($idsubkat):

				$query = $db->query("SELECT `id` FROM `maste_attribute_product` WHERE `idsubcategory` = '$idsubkat' and `publish` = 1 ORDER BY `sortnumber` ASC") or die($db->error);
				while($row = $query->fetch_assoc()):
					   if($idmaster==$row['id']): $jumdata = 1; endif;	 
				endwhile;
				
						
		elseif($idkat):

				$query = $db->query("SELECT `id` FROM `maste_attribute_product` WHERE `idcategory` = '$idkat' and `publish` = 1 ORDER BY `sortnumber` ASC") or die($db->error);
				while($row = $query->fetch_assoc()):
					    if($idmaster==$row['id']): $jumdata = 1; endif;	 
				endwhile;
						
		endif;
				
		
		return $jumdata;
	}
	
	function getidprodlistdataitem($idmaster,$nameattribute){
		global $db;
		$output = ''; $hasildata = '';
		$query = $db->query("SELECT DISTINCT(`product_id`) FROM `attribute_product` WHERE `attribute_id` = '$idmaster' and `attribute_value` = '$nameattribute' ORDER BY `sortnumber` ASC") or die($db->error);
		while($row = $query->fetch_assoc()):			
			$output.=" `product`.`id` = '".$row['product_id']."' or ";
		endwhile; 
		return $output; 

	}
		
	function replacenameattribute($text){
			//next
			$str = array("’", " " , "/" , "?" , "%" , "," , "!" , "#" , "$" , "@" , "^" , "&" , "\"" , "\\" , "\r\n" , "\n" , "\r" , "'", "." , "rsquo;");
			$newtext=str_replace($str,"",strtolower($text));
			return $newtext;
	}	

	function replacekurir($text){
			//next
			$str = array(")");
			$newtext=str_replace($str,"",$text);
			return $newtext;
	}
		
	function masteratrubutelist($idkat,$idsubkat,$idsublevel,$Listmaster_attribute){
		global $db;
		if($idsublevel>0):

				$query = $db->query("SELECT * FROM `maste_attribute_product` WHERE `idsubcate_level` = '$idsublevel' and `publish` = 1 ORDER BY `sortnumber` ASC") or die($db->error);
				while($row = $query->fetch_assoc()):
					   $statusList = getattributeliststatus($row['id'],"sublevel",$idsublevel);  
					   
					   if($statusList>0):
						   echo'<div class="ps-child">';
								 echo'<h3 class="f-pb psc-toggle txt-up"><span>'.$row['attribute_title'].'</span></h3>';
								 echo'<div class="psc">';
									 getattributelist($row['id'],"sublevel",$idsublevel,$Listmaster_attribute);  
								 echo'</div><!-- .psc -->';
							 echo'</div><!-- .ps-child -->';
					   endif;	 
				endwhile;
					
		elseif($idsubkat):

				$query = $db->query("SELECT * FROM `maste_attribute_product` WHERE `idsubcategory` = '$idsubkat' and `publish` = 1 ORDER BY `sortnumber` ASC") or die($db->error);
				while($row = $query->fetch_assoc()):
					   $statusList = getattributeliststatus($row['id'],"subkat",$idsubkat);  
					   
					   if($statusList>0):	
					    echo'<div class="ps-child">';
                        	 echo'<h3 class="f-pb psc-toggle txt-up"><span>'.$row['attribute_title'].'</span></h3>';
                             echo'<div class="psc">';
                             	 getattributelist($row['id'],"subkat",$idsubkat,$Listmaster_attribute);  
                             echo'</div><!-- .psc -->';
                         echo'</div><!-- .ps-child -->';
					   endif;	 
				endwhile;
				
						
		elseif($idkat):

				$query = $db->query("SELECT * FROM `maste_attribute_product` WHERE `idcategory` = '$idkat' and `publish` = 1 ORDER BY `sortnumber` ASC") or die($db->error);
				while($row = $query->fetch_assoc()):
					    $statusList = getattributeliststatus($row['id'],"kat",$idkat);  
						
						if($statusList>0):
						echo'<div class="ps-child">';
                        	 echo'<h3 class="f-pb psc-toggle txt-up"><span>'.$row['attribute_title'].'</span></h3>';
                             echo'<div class="psc">';
                             	 getattributelist($row['id'],"kat",$idkat,$Listmaster_attribute);  
                             echo'</div><!-- .psc -->';
                         echo'</div><!-- .ps-child -->';
						endif; 
				endwhile;
						
		endif;
	}
	
	function getattributeliststatus($idattribute,$status,$idlist){
		global $db;
		
		//get product id
		$labelidprod = getidprodcutlistid($status,$idlist);
		if($labelidprod!=""): $labelidprodList = ' and ( '.$labelidprod.' )'; else: $labelidprod = ''; endif;
		
		$query = $db->query("SELECT DISTINCT(`attribute_value`) as attributename FROM `attribute_product` WHERE `attribute_id` = '$idattribute' ".$labelidprodList." ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		return $jumpage;
	}
			
	function getattributelist($idattribute,$status,$idlist,$Listmaster_attribute){
		global $db;
		
		//get product id
		$labelidprod = getidprodcutlistid($status,$idlist);
		if($labelidprod!=""): $labelidprodList = ' and ( '.$labelidprod.' )'; else: $labelidprod = ' and ( `product_id`.`id` = 0 ) '; endif;
		
		$query = $db->query("SELECT DISTINCT(`attribute_value`) as attributename FROM `attribute_product` WHERE `attribute_id` = '$idattribute' ".$labelidprodList." ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):

			$totalpage = 1; $totalpage2 = 0;
			while($row = $query->fetch_assoc()):
				$namelist = replacenameattribute($row['attributename']);
				$idlistdatauid = $idattribute.'-'.replacenameattributeID($row['attributename']);
				
				if($totalpage==1): echo'<ul class="checkbox-list">'; endif;
					
					echo'<li>';
                            echo'<input type="checkbox" value="'.$idlistdatauid.'" name="attributename[]" '.getlabelattributedata($Listmaster_attribute,$idlistdatauid).' class="cbox attributelist attributelistItem[]" id="attributename'.$namelist.'" />';
                            echo'<label for="attributename'.$namelist.'">'.$row['attributename'].'</label>';
                    echo'</li>';
							
				if($totalpage==15 or $totalpage==$jumpage): echo'</ul>'; endif;
				if($totalpage==15): echo'<ul class="checkbox-list hidden">'; $totalpage2 = 1; endif;
				
				$totalpage++;
			endwhile;
			
			if($totalpage2==1): echo'</ul>'; echo'<a href="" class="check-toggle">Selengkapnya</a>'; endif;
			
		endif;			
	}
	
	function replacenameattributeID($name){
		global $db;
		$query = $db->query("SELECT `id` FROM `attribute_product` WHERE `attribute_value` = '$name' ") or die($db->error);
		$row = $query->fetch_assoc();
		return $row['id'];
	}
	
	function getidprodcutlistid($status,$idlist){
		global $db;
		$output = '';
		
		if($status=="kat"):
		
			$query = $db->query("SELECT `id` FROM `product` WHERE `idkat` = '$idlist' and `publish` = 1 ORDER BY `sortnumber` DESC") or die($db->error);
			$jumpage = $query->num_rows;
			if($jumpage>0):
				$nomer = 1;
				while($row = $query->fetch_assoc()):
					if($nomer==$jumpage):
						$output.= " `product_id` = '".$row['id']."' ";
					else:
						$output.= " `product_id` = '".$row['id']."' or ";
					endif;
					
					$nomer++;
				endwhile;
			endif;
				
		elseif($status=="subkat"):

			$query = $db->query("SELECT `id` FROM `product` WHERE `idsubkat` = '$idlist' and `publish` = 1 ORDER BY `sortnumber` DESC") or die($db->error);
			$jumpage = $query->num_rows;
			if($jumpage>0):
				$nomer = 1;
				while($row = $query->fetch_assoc()):
					if($nomer==$jumpage):
						$output.= " `product_id` = '".$row['id']."' ";
					else:
						$output.= " `product_id` = '".$row['id']."' or ";
					endif;
					
					$nomer++;
				endwhile;
			endif;
			
					
		elseif($status=="sublevel"):

			$query = $db->query("SELECT `id` FROM `product` WHERE `idsublevel` = '$idlist' and `publish` = 1 ORDER BY `sortnumber` DESC") or die($db->error);
			$jumpage = $query->num_rows;
			if($jumpage>0):
				$nomer = 1;
				while($row = $query->fetch_assoc()):
					if($nomer==$jumpage):
						$output.= " `product_id` = '".$row['id']."' ";
					else:
						$output.= " `product_id` = '".$row['id']."' or ";
					endif;
					
					$nomer++;
				endwhile;
			endif;
			
					
		endif;
		
		return $output;
		
	}
	
	function replacedatastar($labelbintang2){
			$str = array("or LASTLISTDATA", "orLASTLISTDATA");
			$newtext=str_replace($str,"",$labelbintang2);
			return $newtext;	
	}
	
	function getfilterstarprod($bintang5,$bintang4,$bintang3,$bintang2,$bintang1,$bintang0){
		global $db;
		$labelbintang = '';
		$labelbintang2 = '';
		$labelbintangGet = '';
		
		if($bintang5==1): $labelbintang.= getidprodbintang(5); endif;
		if($bintang4==1): $labelbintang.= getidprodbintang(4); endif;
		if($bintang3==1): $labelbintang.= getidprodbintang(3); endif;
		if($bintang2==1): $labelbintang.= getidprodbintang(2); endif;
		if($bintang1==1): $labelbintang.= getidprodbintang(1); endif;
		if($bintang0==1): $labelbintang.= getidprodbintang(0); endif;
		
		if($labelbintang!=""): 
			$labelbintang2 = $labelbintang.'LASTLISTDATA'; 
			$labelbintangGet = " and ( ".replacedatastar($labelbintang2)." )";	
		endif;
		
		return $labelbintangGet;
	}
	
	function getidprodbintang($type){
		global $db;
		$output = '';
		$query = $db->query("SELECT DISTINCT(`idproductlist`) FROM `ulasan_product` WHERE `publish` = 1 ") or die($db->error);
		while($row = $query->fetch_assoc()):
			$jumlahrates = gettotalreviewTotal($row['idproductlist']);
			if($type == $jumlahrates):
				$output.= " `product`.`id` = '".$row['idproductlist']."' or ";	
			else:
				$output.= " `product`.`id` = 0 or ";		
			endif;	
		endwhile;
		return $output;
	}

	function gettotalreviewTotal($idprod){
		global $db;
		$jumlahdata = 0; $hasil = 0;
		$query = $db->query("SELECT `rating` FROM `ulasan_product` WHERE `idproductlist`='$idprod' and `publish` = 1 ORDER BY `id` ASC ") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):		
			while($row = $query->fetch_assoc()):
               $jumlahdata = $jumlahdata+$row['rating'];		
			endwhile;
			$hasil = round($jumlahdata/$jumpage);
			return $hasil; 
		else:
			return $jumlahdata;
		endif;		
	}	
	
	function getlabelcaribrand($brandlistuid,$idkatbrand){
		if($brandlistuid!=""):
			 $labelcaribarnd = ''; $idbrand = 0; $gethasilcaribarnd = '';
			 
			 $tagsbrand = explode('#',$brandlistuid);
			 $tags = count($tagsbrand);
			 $jumdata = $tags-1; 
			 for($x = 0; $x < $tags; $x++):
				$idbrand = $tagsbrand[$x];
				$bandingstatus = getbandingstatus($idkatbrand,$idbrand);
				
				if($idbrand>0 and $bandingstatus==1): 
					if($jumdata==$x):
						$labelcaribarnd.= " `product`.`brand` = '".$idbrand."' "; 
					else:
						$labelcaribarnd.= " `product`.`brand` = '".$idbrand."' or "; 
					endif;
				endif;
			 endfor;	
			 
			 if($labelcaribarnd!=""): $gethasilcaribarnd = " and ( ".$labelcaribarnd." ) "; endif;
			 
			 return $gethasilcaribarnd;	
		else:
			return '';	  
		endif;
	}
	
	function getbandingstatus($idkatbrand,$idbrand){
		global $db;
		$pagejum = 0;	
		if($idkatbrand==0):
			return 1;
		else:
			$query = $db->query("SELECT `id` FROM `brand` WHERE `idcate_product` = '$idkatbrand' and `publish` = 1 ") or die($db->error);
			while($row = $query->fetch_assoc()):	
				if($idbrand==$row['id']): $pagejum = 1; endif; 
			endwhile;	
			return $pagejum;
		endif;
	}
		
	function replaceamount($text){
			$str = array(","," ");
			$newtext=str_replace($str,"",$text);
			return $newtext;
	}	
	function getidkatband($idsublevel){
		global $db;
		$query = $db->query("SELECT `idkat` FROM `sub_level_category` WHERE `id`='$idsublevel'") or die($db->error);
		$row = $query->fetch_assoc();
		return $row['idkat'];	
	}
	
	function getidkatbandsubkat($idsubkat){
		global $db;
		$query = $db->query("SELECT `idkat` FROM `subcategory` WHERE `id`='$idsubkat'") or die($db->error);
		$row = $query->fetch_assoc();
		return $row['idkat'];		
	}
	
	function getbrandsidebar($idkat,$brandlistuid){
		global $db;
		if($idkat==0):
			$query = $db->query("SELECT * FROM `brand` WHERE `publish`=1 ORDER BY `sortnumber` ASC ") or die($db->error);
		else:
			$query = $db->query("SELECT * FROM `brand` WHERE `idcate_product`='$idkat' and `publish`=1 ORDER BY `sortnumber` ASC ") or die($db->error);
		endif;
		
		$jumpage = $query->num_rows;
		if($jumpage>0):
			
			$totalpage = 1; $totalpage2 = 0; $nomer = 0;
			while($row = $query->fetch_assoc()):
				
				if($totalpage==1): echo'<ul class="checkbox-list">'; endif;
						
							echo'<li>';
                                    echo'<input type="checkbox" value="'.$row['id'].'" name="brandid[]" class="cbox brandlist brandlistItem'.$nomer.'" id="brandid'.$row['id'].'" '.ceklabelbrand($row['id'],$brandlistuid).' />';
                                    echo'<label for="brandid'.$row['id'].'">'.$row['name'].'</label>';
                            echo'</li>';
							
				if($totalpage==15 or $totalpage==$jumpage): echo'</ul>'; endif;
				if($totalpage==15): echo'<ul class="checkbox-list hidden">'; $totalpage2 = 1; endif;
				
				$totalpage++; $nomer++;
			endwhile;
			
			if($totalpage2==1): echo'</ul>'; echo'<a href="" class="check-toggle">Selengkapnya</a>'; endif;
			
		endif;
	}
	
	function ceklabelbrand($idbrand,$brandlistuid){
		if($brandlistuid!=""):
			 $tagsbrand = explode('#',$brandlistuid);
			 $tags = count($tagsbrand);
			 for($x = 0; $x < $tags; $x++):
				if($tagsbrand[$x]==$idbrand): return ' checked="checked" '; endif;
			 endfor;		 
		endif;
	}
	
	function getlabelattributedata($brandlistuid,$idbrand){
		if($brandlistuid!=""):
			 $tagsbrand = explode('#',$brandlistuid);
			 $tags = count($tagsbrand);
			 for($x = 0; $x < $tags; $x++):
				if($tagsbrand[$x]==$idbrand): return ' checked="checked" '; endif;
			 endfor;		 
		endif;
	}
	
	function ulasanproduct($idprod){
		global $db;
		$query = $db->query("SELECT *,DATE_FORMAT(`date`,'%d %M %Y') as tgl FROM `ulasan_product` WHERE `idproductlist`='$idprod' and `publish` = 1 ORDER BY `id` DESC ") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):		
			while($row = $query->fetch_assoc()):
                echo'<div class="ulc">';
                       	echo'<h3 class="f-psb">'.$row['title_ulasan'].'</h3>';
               		    echo'<div class="prod-raty-wrap">';
                        echo'<div class="prod-raty" data-score="'.$row['rating'].'"></div>';
                        echo'</div><!-- .prod-raty-wrap -->';
                         echo'<p>'.$row['description'].'</p>';
                         echo'<span class="ulc-meta">'.$row['name'].', '.$row['tgl'].'</span>';
                    echo'</div><!-- .ulc -->';			
			endwhile;
		else:
			echo'<div class="no-ulasan">
                    <span class="fa fa-comments-o icon"></span>
                    <h2 class="ngc-title">Belum Ada Ulasan</h2>
                    <p>Jadilah yang pertama mengulas produk ini!</p>
                 </div><!-- .no-ulasan -->';
		endif;	
	}
	
	function attributeList($idprod){
		global $db;
		$query = $db->query("SELECT * FROM `attribute_product` WHERE `product_id`='$idprod' ORDER BY `sortnumber` ASC ") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):	
		
			echo'<table cellspacing="0" cellpadding="0" class="price-table">';	
			while($row = $query->fetch_assoc()):
            		echo'<tr>';
						echo'<td><strong>'.generalselect("maste_attribute_product","attribute_title"," `id` = '".$row['attribute_id']."' ").'</strong></td>';
						echo'<td>'.$row['attribute_value'].'</td>';
					echo'</tr>';  			
			endwhile;
			
			echo'</table>';
		endif;	
		
	}
	
	function gettotalreview($idprd){
		global $db;
		$jumkomen = 0;
		$query = $db->query("SELECT `id` FROM `ulasan_product` WHERE `idproductlist`='$idprd' and `publish` = 1 ") or die($db->error);
		$jumpage = $query->num_rows;	
		$jumkomen = $jumpage;
		return number_format($jumkomen);	
	}

	function ulasanproductbintang($idprod){
		global $db;
		$jumlahdata = 0; $hasil = 0;
		$query = $db->query("SELECT `rating` FROM `ulasan_product` WHERE `idproductlist`='$idprod' and `publish` = 1 ORDER BY `id` DESC ") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):		
			while($row = $query->fetch_assoc()):
               $jumlahdata = $jumlahdata+$row['rating'];		
			endwhile;
			$hasil = round($jumlahdata/$jumpage);
			return $hasil; 
		else:
			return $jumlahdata;
		endif;	
	}
		
	function getitemproductlist($idprod){
		global $db;
		$output = ''; $diskonval1 = 0; $diskonval2 = 0;
		$query = $db->query("SELECT * FROM `product` WHERE `id`='$idprod' ") or die($db->error);
		$row = $query->fetch_assoc();
 
 						$output.='<div class="plc">';
                            	$output.='<div class="plc-1">';
                                	if($row['discount_value']>0):
										$output.='<div class="ctab-badge-wrap">';
											$output.='<span class="ctab-badge f-psb">'.$row['discount_value'].'% OFF</span>';
										$output.='</div><!-- .ctab-badge-wrap -->';
                            		endif;	
									$output.='<div class="img-wrap"><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($row['name']).'/'.$row['id'].'"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['name'].'" /></a></div>';
                                $output.='</div><!-- .plc-1 -->';
                                $output.='<div class="plc-2">';
                                	$output.='<div class="prod-label-wrap">';
										if(strtolower($row['product_terbaik'])=="produk terbaik"): $output.='<span class="prod-label">Produk Terbaik</span>'; endif;
                                    	if(strtolower($row['product_unggulan'])=="produk unggulan"): $output.='<span class="prod-label">Produk Unggulan</span>'; endif;
                                    $output.='</div><!-- .prod-label-wrap -->';
                                    $output.='<div class="plc-2-content">';
                                    	$output.='<div class="plc-2-1">';
                                        	$output.='<h2><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($row['name']).'/'.$row['id'].'">'.$row['name'].'</a></h2>';
											$output.='<div class="prod-raty-wrap">';
                                            	$output.='<div class="prod-raty" data-score="'.ulasanproductbintang($row['id']).'"></div>';
                                               $output.='<span class="pr-number">('.gettotalreview($row['id']).' review)</span>';
                                            $output.='</div><!-- .prod-raty-wrap -->';
                                           
										   $output.='<p>'.substr(strip_tags($row['short_description']),0,200).' &hellip;</p>';
                                        $output.='</div><!-- .plc-2-1 -->';
                                        
										$output.='<div class="plc-2-2">';
                                        	
											$output.='<div class="prod-price-wrap">';
                                            	if($row['discount_value']>0):
								
													$diskonval1 = ($row['price']*$row['discount_value'])/100;
													$diskonval2 = round($diskonval1);
													$diskonval3 = $row['price']-$diskonval1;
								
													$output.='<span class="old-price">Rp '.number_format($row['price']).',-</span>';
               										$output.='<span class="prod-price f-pb">Rp '.number_format($diskonval3).',-</span>';	
												else:
													$output.='<span class="prod-price f-pb">Rp '.number_format($row['price']).',-</span>';	
												endif;
                                            $output.='</div><!-- .prod-price-wrap -->';
											
											
                                            $output.='<div class="prod-compare-toggle">';
                                            	$output.='<input type="checkbox" id="compareprodList-'.$row['id'].'" name="prod-compare" class="cbox compareListData" '.cekdatalistcompare($row['id']).' />';
                                                $output.='<label for="compareprodList-'.$row['id'].'"><span>Bandingkan Produk</span>';
												$output.='<span class="small">(max. 4 produk)</span></label>';
                                            $output.='</div><!-- .prod-compare-toggle -->';
											
											$output.= getbuttonbeliprod($row['id']);
                                            
											
                                        $output.='</div><!-- .plc-2-2 -->';
								   $output.='</div><!-- .plc-2-content -->';
                               $output.='</div><!-- .plc-2 -->';
                            $output.='</div><!-- .plc -->';
			
			echo $output;									       		
	}
	
	function getnameprodList($idprod){
		global $db;
		$query = $db->query("SELECT `name` FROM `product` WHERE `id`='$idprod' ");
		$row = $query->fetch_assoc();
		return $row['name'];
	}
	
	function getbuttonbeliprod($idprod){
		global $db;
		$output = '';
		
		//new
		$quepjum = $db->query("SELECT `id` FROM `product_detail` WHERE `idproduct_header` = '$idprod' and `stock` > 0 and `publish` = 1 
		ORDER BY `sortnumber` ASC ");
		$jumlahprod = $quepjum->num_rows;
		
		$nameprod = getnameprodList($idprod);
					
		$query = $db->query("SELECT `id`,`soldout_contactus` FROM `product` WHERE `id`='$idprod' ");
		$row = $query->fetch_assoc();
		$totalstock = gettotalstock($row['id']);

		$quep = $db->query("SELECT `id` FROM `product_detail` WHERE `idproduct_header` = '$idprod' and `stock` > 0 and `publish` = 1 ORDER BY `sortnumber` ASC ");
		$res = $quep->fetch_assoc();
				
		if($totalstock>0):
			if($jumlahprod>1):
			$output.='<a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($nameprod).'/'.$idprod.'" class="btn btn-red no-margin f-psb"><span style="font-size:12px;">PICK SIZE</span></a>';			
			else:
			$output.='<a href="" class="btn btn-red no-margin f-psb add_to_cartbtnList" id="listprod-'.$row['id'].'-'.$res['id'].'"><span style="font-size:12px;">ADD TO CART</span></a>';
			endif;
		else:
			if(strtolower($row['soldout_contactus'])=="yes"):
				$output.='<a href="'.$GLOBALS['SITE_URL'].'contact" class="btn btn-red no-margin f-psb"><span class="contactkami" style="font-size:12px;">CONTACT US</span></a>';
			else:
				$output.='<a class="btn soldoutbtn no-margin f-psb"><span style="font-size:12px;">SOLD OUT</span></a>';
			endif;
		endif;
		return $output;
	}
	
	function cekdatalistcompare($idproduct){

		if(isset($_SESSION['useridprod_compare1'])): $idcompare1 = $_SESSION['useridprod_compare1']; else: $idcompare1 = ''; endif;
		if(isset($_SESSION['useridprod_compare2'])): $idcompare2 = $_SESSION['useridprod_compare2']; else: $idcompare2 = ''; endif;
		if(isset($_SESSION['useridprod_compare3'])): $idcompare3 = $_SESSION['useridprod_compare3']; else: $idcompare3 = ''; endif;
		if(isset($_SESSION['useridprod_compare4'])): $idcompare4 = $_SESSION['useridprod_compare4']; else: $idcompare4 = ''; endif;
		
		if($idproduct<>$idcompare1 and $idproduct<>$idcompare2 and $idproduct<>$idcompare3 and $idproduct<>$idcompare4):
			//no data
		else:
			return ' checked="checked" ';
		endif;	
	}
	
	function getitemproductlistItem($idprod){
		global $db;
		$output = ''; $diskonval1 = 0; $diskonval2 = 0;
		$query = $db->query("SELECT * FROM `product` WHERE `id`='$idprod' ") or die($db->error);
		$row = $query->fetch_assoc();

         // $output.='<div class="item">';
              $output.='<div class="ctab-content smaller">';
                  $output.='<div class="ctab-badge-wrap">';
                  	if($row['discount_value']>0):
                  		$output.='<span class="ctab-badge f-psb">'.$row['discount_value'].'% OFF</span>';
                  	endif;
                      $output.='</div><!-- .ctab-badge-wrap -->';
                                  $output.='<div class="img-wrap"><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($row['name']).'/'.$row['id'].'">';
								  			$output.='<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['name'].'" class="lazyload" data-expand="-10" /></a></div>';
                                  $output.='<div class="ctab-content-prod">';
								  
                                  $output.='<span class="prod-brand f-yellow f-psb">'.generalselect("category","name"," `id` = '".$row['idkat']."' ").'</span>';
                                  $output.='<h3 class="f-psb"><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($row['name']).'/'.$row['id'].'" title="'.$row['name'].'">'.substr($row['name'],0,50).'</a></h3>';
                                  $output.='<div class="prod-price-wrap">';
                              
							                   	if($row['discount_value']>0):
								
													$diskonval1 = ($row['price']*$row['discount_value'])/100;
													$diskonval2 = round($diskonval1);
													$diskonval3 = $row['price']-$diskonval1;
								
													$output.='<span class="old-price">Rp '.number_format($row['price']).',-</span>';
               										$output.='<span class="prod-price f-pb">Rp '.number_format($diskonval3).',-</span>';	
												else:
													$output.='<span class="prod-price f-pb">Rp '.number_format($row['price']).',-</span>';	
												endif;
												
                                  $output.='</div><!-- .prod-price-wrap -->';
                            $output.='</div><!-- .ctab-content-prod -->';

                    	$output.='<div class="btn-wrap">';
					$output.= getbuttonbeliprod($row['id']);
				$output.='</div><!-- .btn-wrap -->';

            $output.='</div><!-- .ctab-content -->';
            
  		 // $output.='</div><!-- .item -->';	
		 
		 echo $output;		
	}

	function getproductimg($idprod,$imgname){
		global $db;
		$que = $db->query("SELECT `product_image` FROM `product_images` WHERE `product_id`='$idprod' ORDER BY `sortnumber` ASC");
		$jumpage = $que->num_rows;
		if($jumpage>0):
			$row = $que->fetch_assoc();
			echo'<img id="zoom_01" class="zoomable" src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['product_image'].'" data-zoom-image="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['product_image'].'" />';
		else:
			echo'<img id="zoom_01" class="zoomable" src="'.$GLOBALS['UPLOAD_FOLDER'].''.$imgname.'" data-zoom-image="'.$GLOBALS['UPLOAD_FOLDER'].''.$imgname.'" />';
		endif;	
	}	

	function getproductimgList($idprod,$imgname){
		global $db;
		$que = $db->query("SELECT `product_image` FROM `product_images` WHERE `product_id`='$idprod' ORDER BY `sortnumber` ASC");
		$jumpage = $que->num_rows;
		if($jumpage>0):
			$pageno = 1;
			while($row = $que->fetch_assoc()):
					if($pageno==1):
						echo'<a href="#" data-image="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['product_image'].'" data-zoom-image="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['product_image'].'" class="active">';
						   echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['product_image'].'" />';
						echo'</a>';			
					else:
						echo'<a href="#" data-image="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['product_image'].'" data-zoom-image="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['product_image'].'">';
						   echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['product_image'].'" />';
						echo'</a>';						
					endif;
							
				$pageno++;
			endwhile;
		else:
            echo'<a href="#" data-image="'.$GLOBALS['UPLOAD_FOLDER'].''.$imgname.'" data-zoom-image="'.$GLOBALS['UPLOAD_FOLDER'].''.$imgname.'" class="active">';
               echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$imgname.'" />';
            echo'</a>';
		endif;	
	}

	function relatedproduct($idprod,$idkat,$idsubkat,$idsublevel){
		global $db;
		$query = $db->query("SELECT `id` FROM `product` WHERE `id` <> '$idprod' and (`idkat` = '$idkat' or `idsubkat` = '$idsubkat' or `idsublevel` = '$idsublevel' ) and `publish` = 1 ORDER BY `sortnumber` DESC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):		
			while($row = $query->fetch_assoc()):
                getitemproductlistItem($row['id']);	
			endwhile;	
		else:
	         echo'<span class="notfound">Record not found.</span>';
		endif;	
	}
				
	function getlinkurelprod($idkat,$idsubkat,$idsublevel){
		global $db;
		
		if($idsublevel>0):
			$quesub = $db->query("SELECT * FROM `sub_level_category` WHERE `id` = '$idsublevel' and `publish` = 1 ");
			$datasub = $quesub->fetch_assoc();
			
			//category
			$query = $db->query("SELECT * FROM `category` WHERE `id` = '".$datasub['idkat']."' and `publish` = 1 ");
			$jumpage = $query->num_rows;
			if($jumpage>0):
				$row = $query->fetch_assoc();
				
				$query2 = $db->query("SELECT * FROM `subcategory` WHERE `idkat` = '".$row['id']."' and `publish` = 1 ");
				$jumpage2 = $query2->num_rows;
				if($jumpage2>0):
					echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-category/'.replace($row['name']).'/'.$row['id'].'" title="">'.replacebr($row['name']).'</a></li>';
				else:
					echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-category/'.replace($row['name']).'/'.$row['id'].'" title="">'.replacebr($row['name']).'</a></li>';	
				endif;
			endif;	
			//end category
			
			
			
			//subcategory
			$quepp = $db->query("SELECT * FROM `subcategory` WHERE `id` = '".$datasub['idsubkat']."' and `publish` = 1 ");
			$jumpapp = $quepp->num_rows;
			if($jumpapp>0):
				$res = $quepp->fetch_assoc();
				
				$quepp2 = $db->query("SELECT * FROM `subcategory` WHERE `idkat` = '".$row['id']."' and `publish` = 1 ");
				$jumpapp2 = $quepp2->num_rows;
				if($jumpapp2>0):
					echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-subcategory/'.replace($res['name']).'/'.$res['id'].'" title="">'.replacebr($res['name']).'</a></li>';
				else:
					echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-subcategory/'.replace($res['name']).'/'.$res['id'].'" title="">'.replacebr($res['name']).'</a></li>';	
				endif;
			endif;				
			//end sub.category
		
		
		elseif($idsubkat>0):			
			$quesub = $db->query("SELECT * FROM `subcategory` WHERE `id` = '$idsubkat' and `publish` = 1 ");
			$datasub = $quesub->fetch_assoc();

			//category
			$query = $db->query("SELECT * FROM `category` WHERE `id` = '".$datasub['idkat']."' and `publish` = 1 ");
			$jumpage = $query->num_rows;
			if($jumpage>0):
				$row = $query->fetch_assoc();
				
				$query2 = $db->query("SELECT * FROM `subcategory` WHERE `idkat` = '".$row['id']."' and `publish` = 1 ");
				$jumpage2 = $query2->num_rows;
				if($jumpage2>0):
					echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-category/'.replace($row['name']).'/'.$row['id'].'" title="">'.replacebr($row['name']).'</a></li>';
				else:
					echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-category/'.replace($row['name']).'/'.$row['id'].'" title="">'.replacebr($row['name']).'</a></li>';	
				endif;
			endif;	
			//end category			
			
		endif;
	}

	function getlinkurelproddetail($idkat,$idsubkat,$idsublevel){
		global $db;
		
		if($idsublevel>0):
			$quesub = $db->query("SELECT * FROM `sub_level_category` WHERE `id` = '$idsublevel' and `publish` = 1 ");
			$datasub = $quesub->fetch_assoc();
			
			//category
			$query = $db->query("SELECT * FROM `category` WHERE `id` = '".$datasub['idkat']."' and `publish` = 1 ");
			$jumpage = $query->num_rows;
			if($jumpage>0):
				$row = $query->fetch_assoc();
				
				$query2 = $db->query("SELECT * FROM `subcategory` WHERE `idkat` = '".$row['id']."' and `publish` = 1 ");
				$jumpage2 = $query2->num_rows;
				if($jumpage2>0):
					echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-category/'.replace($row['name']).'/'.$row['id'].'" title="">'.replacebr($row['name']).'</a></li>';
				else:
					echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-category/'.replace($row['name']).'/'.$row['id'].'" title="">'.replacebr($row['name']).'</a></li>';	
				endif;
			endif;	
			//end category
						
			
			//subcategory
			$quepp = $db->query("SELECT * FROM `subcategory` WHERE `id` = '".$datasub['idsubkat']."' and `publish` = 1 ");
			$jumpapp = $quepp->num_rows;
			if($jumpapp>0):
				$res = $quepp->fetch_assoc();
				
				$quepp2 = $db->query("SELECT * FROM `subcategory` WHERE `idkat` = '".$row['id']."' and `publish` = 1 ");
				$jumpapp2 = $quepp2->num_rows;
				if($jumpapp2>0):
					echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-subcategory/'.replace($res['name']).'/'.$res['id'].'" title="">'.replacebr($res['name']).'</a></li>';
				else:
					echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-subcategory/'.replace($res['name']).'/'.$res['id'].'" title="">'.replacebr($res['name']).'</a></li>';	
				endif;
			endif;				
			//end sub.category
		

			//sub detail
			echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-sublevel/'.replace($datasub['name']).'/'.$datasub['id'].'" title="">'.replacebr($datasub['name']).'</a></li>';	
			
					
		elseif($idsubkat>0):			
			$quesub = $db->query("SELECT * FROM `subcategory` WHERE `id` = '$idsubkat' and `publish` = 1 ");
			$datasub = $quesub->fetch_assoc();

			//category
			$query = $db->query("SELECT * FROM `category` WHERE `id` = '".$datasub['idkat']."' and `publish` = 1 ");
			$jumpage = $query->num_rows;
			if($jumpage>0):
				$row = $query->fetch_assoc();
				
				$query2 = $db->query("SELECT * FROM `subcategory` WHERE `idkat` = '".$row['id']."' and `publish` = 1 ");
				$jumpage2 = $query2->num_rows;
				if($jumpage2>0):
					echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-category/'.replace($row['name']).'/'.$row['id'].'" title="">'.replacebr($row['name']).'</a></li>';
				else:
					echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-category/'.replace($row['name']).'/'.$row['id'].'" title="">'.replacebr($row['name']).'</a></li>';	
				endif;
			endif;	
			//end category			
			
		endif;
	}	
	
	function productsublevel($idkat,$idsubkat){
		global $db;
		$query = $db->query("SELECT * FROM `sub_level_category` WHERE `idkat` = '$idkat' and `idsubkat` = '$idsubkat' and `publish` = 1 ORDER BY `sortnumber` ASC");
		while($row = $query->fetch_assoc()):
			echo'<li>';
				echo'<div class="pcw">';
					  echo'<div class="img-wrap"><a href="'.$GLOBALS['SITE_URL'].'product-list-sublevel/'.replace($row['name']).'/'.$row['id'].'" title="">
					  	   <img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['thumb_image'].'" alt="'.$row['name'].'" /></a></div>';
					  echo'<p><a href="'.$GLOBALS['SITE_URL'].'product-list-sublevel/'.replace($row['name']).'/'.$row['id'].'" title=""><span>'.$row['name'].'</span></a></p>';
				 echo'</div><!-- .pcw -->';
		   echo'</li>';	
	   endwhile;
	}

	function productsubcategory($idkat){
		global $db;
		$query = $db->query("SELECT * FROM `subcategory` WHERE `idkat` = '$idkat' and `publish` = 1 ORDER BY `sortnumber` ASC");
		while($row = $query->fetch_assoc()):
				
					$query3 = $db->query("SELECT `id` FROM `sub_level_category` WHERE `idkat` = '".$row['idkat']."' and `idsubkat` = '".$row['id']."' and `publish` = 1 ORDER BY `sortnumber` ASC");
					$jumpage3 = $query3->num_rows;
					if($jumpage3>0):	
						echo'<li>';
							echo'<div class="pcw">';
								  echo'<div class="img-wrap"><a href="'.$GLOBALS['SITE_URL'].'product-subcategory/'.replace($row['name']).'/'.$row['id'].'" title="">
								  <img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['thumb_image'].'" alt="'.$row['name'].'" /></a></div>';
								  echo'<p><a href="'.$GLOBALS['SITE_URL'].'product-subcategory/'.replace($row['name']).'/'.$row['id'].'" title=""><span>'.$row['name'].'</span></a></p>';
							 echo'</div><!-- .pcw -->';
					   echo'</li>';						
					else:								
						echo'<li>';
							echo'<div class="pcw">';
								  echo'<div class="img-wrap"><a href="'.$GLOBALS['SITE_URL'].'product-list-subcategory/'.replace($row['name']).'/'.$row['id'].'" title="">
								  <img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['thumb_image'].'" alt="'.$row['name'].'" /></a></div>';
								  echo'<p><a href="'.$GLOBALS['SITE_URL'].'product-list-subcategory/'.replace($row['name']).'/'.$row['id'].'" title=""><span>'.$row['name'].'</span></a></p>';
							 echo'</div><!-- .pcw -->';
					   echo'</li>';	
				   endif;
				   
	   endwhile;
	}
	
	function generalselect($tablename,$field,$where){
		global $db;
		$query = $db->query("SELECT `".$field."` FROM `".$tablename."` WHERE ".$where." ");
		$row = $query->fetch_assoc();
		return $row[$field];
	}	
	
	function generateFormTokenorderform($form) {
		date_default_timezone_set('Asia/Jakarta');
		$timeTg = date("YmdHms");
		$tokenIdFormall = $timeTg;
		$tokenIdForm = sha1($tokenIdFormall);
		$_SESSION[$form.'_token'] = $tokenIdForm; 
    	return $tokenIdForm;
	}	
		
	function replace($text){
			//fisrt replace
			$str_awal = array("<br>", "<br >", "<br />" , "<br/>");
			$text_awal = str_replace($str_awal," ",strtolower($text));
			
			//next
			$str = array("’", " " , "/" , "?" , "%" , "," , "!" , "#" , "$" , "@" , "^" , "&" , "\"" , "\\" , "\r\n" , "\n" , "\r" , "'", "." , "rsquo;");
			$newtext=str_replace($str,"-",strtolower($text_awal));
			return $newtext;
	}

	function replacebr($text){
			//fisrt replace
			$str_awal = array("<br>", "<br >", "<br />" , "<br/>");
			$text_awal = str_replace($str_awal," ",$text);
			return $text_awal;
	}
			
	function replaceUrel($katatext){
		if($katatext<>''):
			$str = array("or", "union", "=", "==", "|", "concat", "&", "*", "where", "<", ">", "/", "'", "select", "from");
			$kata_text = strtolower($katatext);
			$katatext_1	= filter_var($kata_text, FILTER_SANITIZE_STRING);	
			$katatext_2 = strip_tags($katatext_1);
			$newtext = str_replace($str,"",$katatext_2);
			return $newtext;	
		endif;		
	}

	function replaceback($text){
			$str = array("-");
			$newtext=str_replace($str," ",strtolower($text));
			return $newtext;
	}
	
	function replaceUrelKeyword($katatext){
		if($katatext<>''):
			$keyword = replaceback($katatext);
			$str = array("union", "==", "|", "concat", "&", "*", "where", "<", ">", "/", "'", "select", "from");
			$kata_text = strtolower($keyword);
			$katatext_1	= filter_var($kata_text, FILTER_SANITIZE_STRING);	
			$katatext_2 = strip_tags($katatext_1);
			$newtext = str_replace($str,"",$katatext_2);
			return $newtext;	
		endif;		
	}
		
	function global_select_single($table_name, $str_select, $str_where=false, $str_order=false) { 
		global $db;
	
		$str =  " SELECT ".$str_select." FROM ".$table_name."
				".($str_where ? "WHERE ".$str_where : "")."
			    ".($str_order ? "ORDER BY ".$str_order : "")."
		";
		
		$result = $db->query($str);
		if($result->num_rows > 0) { 
			$row = $result->fetch_assoc();
			return $row;
		}
		
		return false;
	}	

	function pages($id){
		global $db;
		$query = $db->query("SELECT `description` FROM `pages` WHERE `id`='$id'");
		$row = $query->fetch_assoc();
		echo $row['description']; 
	}	
	
	function info_pages_title($id){
		global $db;
		$query = $db->query("SELECT `pages` FROM `info_pages` WHERE `id`='$id'");
		$row = $query->fetch_assoc();
		echo $row['pages'];
	}

	function info_pages_desc($id){
		global $db;
		$query = $db->query("SELECT `description` FROM `info_pages` WHERE `id`='$id'");
		$row = $query->fetch_assoc();
		echo $row['description'];
	}

	function allcategoryside(){
		global $db;
		$query = $db->query("SELECT * FROM `category` WHERE `publish` = 1 ORDER BY `sortnumber` ASC");
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):
					
					//select to sub
					$query2 = $db->query("SELECT * FROM `subcategory` WHERE `idkat` = '".$row['id']."' and `publish` = 1 ORDER BY `sortnumber` ASC");
					$jumpage2 = $query2->num_rows;
					if($jumpage2>0):
						
						echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-category/'.replace($row['name']).'/'.$row['id'].'" title="" class="show-allprod-subnav"><span>'.replacebr($row['name']).'</span></a>';			
						echo'<div class="allprod-subnav">';
                    		 echo'<div class="aps-mason">';
							 	
								 while($row2 = $query2->fetch_assoc()):
								 	echo'<div class="apsc">';
										
										//select to sub level
										$query3 = $db->query("SELECT * FROM `sub_level_category` WHERE `idkat` = '".$row['id']."' and `idsubkat` = '".$row2['id']."' and `publish` = 1 ORDER BY `sortnumber` ASC");
										$jumpage3 = $query3->num_rows;
										if($jumpage3>0):
											  echo'<h3 class="f-psb"><a href="'.$GLOBALS['SITE_URL'].'product-subcategory/'.replace($row2['name']).'/'.$row2['id'].'" title="">'.replacebr($row2['name']).'</a></h3>';	
												  echo'<ul>';
													  while($row3 = $query3->fetch_assoc()):
														 echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-sublevel/'.replace($row3['name']).'/'.$row3['id'].'" title="">'.replacebr($row3['name']).'</a></li>';	
													  endwhile;
												  echo'</ul>';
										else:
											// no sub level
											echo'<h3 class="f-psb"><a href="'.$GLOBALS['SITE_URL'].'product-list-subcategory/'.replace($row2['name']).'/'.$row2['id'].'" title="">'.replacebr($row2['name']).'</a></h3>';		  
										endif;
										//end select to sub level
						
										
									echo'</div><!-- .apsc -->';	
								 endwhile;
					  		
						  echo'</div><!-- .aps-mason -->';
						echo'</div><!-- .allprod-subnav -->';	
						
					else:
						// no sub cate
						echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-category/'.replace($row['name']).'/'.$row['id'].'" title=""><span>'.replacebr($row['name']).'</span></a>';	
					endif;	
					//end select to sub
					
					echo'</li>';			
			endwhile;
		endif;		
	}
	
	function allcategoryheader(){
		global $db;
		$query = $db->query("SELECT * FROM `category` WHERE `publish_in_main_header`='Yes' and `publish` = 1 ORDER BY `sortnumber` ASC LIMIT 0,9");
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):
					
					if( strpos($row['name'], "<br />") == true or strpos($row['name'], "<br/>")==true or strpos($row['name'], "<br>")==true): $Klass = 'two-rows'; else: $Klass = ''; endif; 
					
					//select to sub
					$query2 = $db->query("SELECT * FROM `subcategory` WHERE `idkat` = '".$row['id']."' and `publish` = 1 ORDER BY `sortnumber` ASC");
					$jumpage2 = $query2->num_rows;
					if($jumpage2>0):
						echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-category/'.replace($row['name']).'/'.$row['id'].'" title="" class="has-sub txt-up '.$Klass.'">'.$row['name'].'</a>';			
							echo'<div class="cat-subnav-wrap">';
                            	echo'<ul class="cat-subnav">';
									 while($row2 = $query2->fetch_assoc()):
									 	
										//select to sub level
										$query3 = $db->query("SELECT * FROM `sub_level_category` WHERE `idkat` = '".$row['id']."' and `idsubkat` = '".$row2['id']."' and `publish` = 1 ORDER BY `sortnumber` ASC");
										$jumpage3 = $query3->num_rows;
										if($jumpage3>0):
											echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-subcategory/'.replace($row2['name']).'/'.$row2['id'].'" title="">';
													echo'<span class="img-wrap"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row2['thumb_image'].'" alt="'.$row2['name'].'" /></span>';
														echo'<span class="f-pb">'.replacebr($row2['name']).'</span>';
													echo'</a>';
											echo'</li>';		
										else:
											// no sub level
											echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-subcategory/'.replace($row2['name']).'/'.$row2['id'].'" title="">';
													echo'<span class="img-wrap"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row2['thumb_image'].'" alt="'.$row2['name'].'" /></span>';
														echo'<span class="f-pb">'.replacebr($row2['name']).'</span>';
													echo'</a>';
											echo'</li>';	  
										endif;
										//end select to sub level
										
									 endwhile;	
								echo'</ul>';
							echo'</div>';	
					else:
						// no sub cate
						echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-category/'.replace($row['name']).'/'.$row['id'].'" title="" class="txt-up '.$Klass.'">'.$row['name'].'</a>';	
					endif;	
					//end select to sub
					
					echo'</li>';			
			endwhile;
		endif;		
	}

	function allcategoryheader2(){
		global $db;
		$query = $db->query("SELECT * FROM `category` WHERE `publish_in_main_header`='Yes' and `publish` = 1 ORDER BY `sortnumber` ASC LIMIT 0,9");
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):
					
					if( strpos($row['name'], "<br />") == true or strpos($row['name'], "<br/>")==true or strpos($row['name'], "<br>")==true): $Klass = 'two-rows'; else: $Klass = ''; endif; 
					
					//select to sub
					$query2 = $db->query("SELECT * FROM `subcategory` WHERE `idkat` = '".$row['id']."' and `publish` = 1 ORDER BY `sortnumber` ASC");
					$jumpage2 = $query2->num_rows;
					if($jumpage2>0):
						echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-category/'.replace($row['name']).'/'.$row['id'].'" title="" class="has-sub txt-up '.$Klass.'">'.$row['name'].'</a>';			
							echo'<div class="cat-subnav-wrap">';
                            	echo'<ul class="cat-subnav">';
									 while($row2 = $query2->fetch_assoc()):
									 	
										//select to sub level
										$query3 = $db->query("SELECT * FROM `sub_level_category` WHERE `idkat` = '".$row['id']."' and `idsubkat` = '".$row2['id']."' and `publish` = 1 ORDER BY `sortnumber` ASC");
										$jumpage3 = $query3->num_rows;
										if($jumpage3>0):
											echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-subcategory/'.replace($row2['name']).'/'.$row2['id'].'" title="">';
													echo'<span class="img-wrap"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row2['thumb_image'].'" alt="'.$row2['name'].'" /></span>';
														echo'<span class="f-pb">'.replacebr($row2['name']).'</span>';
													echo'</a>';
											echo'</li>';		
										else:
											// no sub level
											echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-subcategory/'.replace($row2['name']).'/'.$row2['id'].'" title="">';
													echo'<span class="img-wrap"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row2['thumb_image'].'" alt="'.$row2['name'].'" /></span>';
														echo'<span class="f-pb">'.replacebr($row2['name']).'</span>';
													echo'</a>';
											echo'</li>';	  
										endif;
										//end select to sub level
										
									 endwhile;	
								echo'</ul>';
							echo'</div>';	
					else:
						// no sub cate
						echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-category/'.replace($row['name']).'/'.$row['id'].'" title="" class="txt-up '.$Klass.'">'.$row['name'].'</a>';	
					endif;	
					//end select to sub
					
					echo'</li>';			
			endwhile;
		endif;		
	}

	function allcategoryheadermobile(){
		global $db;
		$query = $db->query("SELECT * FROM `category` WHERE `publish` = 1 ORDER BY `sortnumber` ASC");
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):
					
					//select to sub
					$query2 = $db->query("SELECT * FROM `subcategory` WHERE `idkat` = '".$row['id']."' and `publish` = 1 ORDER BY `sortnumber` ASC");
					$jumpage2 = $query2->num_rows;
					if($jumpage2>0):
						echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-category/'.replace($row['name']).'/'.$row['id'].'" title="" class="has-sub"><span>'.replacebr($row['name']).'</span></a>';			
                            	echo'<ul class="sub-menu">';
									 while($row2 = $query2->fetch_assoc()):
									 	
										//select to sub level
										$query3 = $db->query("SELECT * FROM `sub_level_category` WHERE `idkat` = '".$row['id']."' and `idsubkat` = '".$row2['id']."' and `publish` = 1 ORDER BY `sortnumber` ASC");
										$jumpage3 = $query3->num_rows;
										if($jumpage3>0):
											
											echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-subcategory/'.replace($row2['name']).'/'.$row2['id'].'" title="" class="has-sub"><span>'.replacebr($row2['name']).'</span></a>';
												echo'<ul class="sub-menu">';
													 while($row3 = $query3->fetch_assoc()):
													 	 echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-sublevel/'.replace($row3['name']).'/'.$row3['id'].'" title="">'.replacebr($row3['name']).'</a></li>';	
													 endwhile;	
												echo'</ul>';
											echo'</li>';	
											
												
										else:
											// no sub level
											echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-subcategory/'.replace($row2['name']).'/'.$row2['id'].'" title="">'.replacebr($row2['name']).'</a></li>';	  
										endif;
										//end select to sub level
										
									 endwhile;	
								echo'</ul>';
					else:
						// no sub cate
						echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-category/'.replace($row['name']).'/'.$row['id'].'" title=""><span>'.replacebr($row['name']).'</span></a>';	
					endif;	
					//end select to sub
					
					echo'</li>';			
			endwhile;
		endif;		
	}

	function allcategorynew(){
		global $db;
		$query_cat = $db->query("SELECT * FROM `category` WHERE `publish` = 1 ORDER BY `sortnumber` ASC");
		$jumpage_cat = $query_cat->num_rows;
		$ctr = 1;

		if($jumpage_cat>0):
			echo '<ul class="main-category">';
			while($row = $query_cat->fetch_assoc()):
				echo'<li class="has-sub"><a href="#mc-'.$ctr.'">'.replacebr($row['name']).'</a></li>';
				$ctr += 1;
			endwhile;
			echo '</ul>';
		endif;

		$query = $db->query("SELECT * FROM `category` WHERE `publish` = 1 ORDER BY `sortnumber` ASC");
		$jumpage = $query->num_rows;
		$ctr = 1;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):
					
					//select to sub
					$query2 = $db->query("SELECT * FROM `subcategory` WHERE `idkat` = '".$row['id']."' and `publish` = 1 ORDER BY `sortnumber` ASC");
					$jumpage2 = $query2->num_rows;
					if($jumpage2>0):
						
						echo'<div id="mc-'.$ctr.'" class="new-mega">';
                    		 echo'<div class="aps-mason">';
							 	
								 while($row2 = $query2->fetch_assoc()):
								 	echo'<div class="apsc">';
										
										//select to sub level
										$query3 = $db->query("SELECT * FROM `sub_level_category` WHERE `idkat` = '".$row['id']."' and `idsubkat` = '".$row2['id']."' and `publish` = 1 ORDER BY `sortnumber` ASC");
										$jumpage3 = $query3->num_rows;
										if($jumpage3>0):
											  echo'<h3 class="f-psb"><a href="'.$GLOBALS['SITE_URL'].'product-subcategory/'.replace($row2['name']).'/'.$row2['id'].'" title="">'.replacebr($row2['name']).'</a></h3>';	
												  echo'<ul>';
													  while($row3 = $query3->fetch_assoc()):
														 echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-sublevel/'.replace($row3['name']).'/'.$row3['id'].'" title="">'.replacebr($row3['name']).'</a></li>';	
													  endwhile;
												  echo'</ul>';
										else:
											// no sub level
											echo'<h3 class="f-psb"><a href="'.$GLOBALS['SITE_URL'].'product-list-subcategory/'.replace($row2['name']).'/'.$row2['id'].'" title="">'.replacebr($row2['name']).'</a></h3>';		  
										endif;
										//end select to sub level
						
										
									echo'</div><!-- .apsc -->';	
								 endwhile;
					  		
						  echo'</div><!-- .aps-mason -->';
						echo'</div><!-- .new-mega -->';	
						
					else:
						// no sub cate
						// echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-list-category/'.replace($row['name']).'/'.$row['id'].'" title=""><span>'.replacebr($row['name']).'</span></a>';	
					endif;	
					//end select to sub
				$ctr+=1;
			endwhile;
		endif;		
	}
			
		
	function corporateinvitation(){
		global $db;
		$query = $db->query("SELECT * FROM `corporate_invitation` ORDER BY `id` ASC");
		$row = $query->fetch_assoc();	
		
		echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['title'].' '.$row['subtitle'].'"  />';
            echo'<div class="ci-content">';
             	echo'<h2><span class="f-psb">'.$row['title'].'</span> <span class="f-pb">'.$row['subtitle'].'</span></h2>';
        	    echo $row['description'];
                echo'<a href="'.$GLOBALS['SITE_URL'].'register-corporate" class="btn btn-red no-margin">DAFTAR SEKARANG</a>';
                if($row['custom_link']!=""): echo'<a href="'.$row['custom_link'].'" title="" target="_blank" class="link-more">Selengkapnya &raquo;</a>'; endif;
            echo'</div><!-- .ci-content -->';		
	}
	
	function banner(){
		global $db;
		$query = $db->query("SELECT * FROM `banner` WHERE `publish`=1 ORDER BY `sortnumber` ASC");
		while($row = $query->fetch_assoc()):
			echo'<li>';
				if($row['custom_link']==""):
              		  echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['banner_image'].'" class="flexImages" alt="'.$row['name'].'" />';
				else:
					echo'<a href="'.$row['custom_link'].'" title="" target="_blank"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['banner_image'].'" class="flexImages" alt="'.$row['name'].'" /></a>';	
				endif;
            echo'</li>';	
		endwhile;
	}		

	function bannerads(){
		global $db;
		$query = $db->query("SELECT * FROM `banner_ads` WHERE `publish`=1 ORDER BY `sortnumber` ASC");
		while($row = $query->fetch_assoc()):
			echo'<li class="split-2-640">';
				if($row['custom_link']==""):
              		  echo'<a title=""><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['title'].'" /></a>';
				else:
					echo'<a href="'.$row['custom_link'].'" title="" target="_blank"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['title'].'" /></a>';	
				endif;
            echo'</li>';	
		endwhile;
	}	

	function smallbannerads(){
		global $db;
		$query = $db->query("SELECT * FROM `small_banner_ads` WHERE `publish`=1 ORDER BY `sortnumber` ASC");
		while($row = $query->fetch_assoc()):
			echo'<li class="split-3-640">';
				if($row['custom_link']==""):
              	    echo'<a title=""><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['title'].'" class="lazyload" data-expand="-10" /></a>';
				else:
					echo'<a href="'.$row['custom_link'].'" title="" target="_blank"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['title'].'" class="lazyload" data-expand="-10" /></a>';	
				endif;
            echo'</li>';	
		endwhile;
	}	
		
	function seo_page_general($server_uri){
		global $db;
		if($server_uri=="/" or $server_uri==""):
			$pageuid = '/index';
		else:	
			$pageuid = $server_uri;
		endif;	
		
		$quip = $db->query("SELECT `seo_title`,`seo_keyword`,`seo_description` FROM `seo` WHERE `page`='$pageuid'");
		$jumpage = $quip->num_rows;
		if($jumpage>0):
			$res = $quip->fetch_assoc();
			return array('seo_title'=>$res['seo_title'], 'seo_keyword'=>$res['seo_keyword'], 'seo_description'=>$res['seo_description']);
		else:
			$quip2 = $db->query("SELECT `seo_title`,`seo_keyword`,`seo_description` FROM `seo` WHERE `page`='/index' or `id`=1 ");
			$res2 = $quip2->fetch_assoc();
			return array('seo_title'=>$res2['seo_title'], 'seo_keyword'=>$res2['seo_keyword'], 'seo_description'=>$res2['seo_description']);
		endif;	
  	}	
	
	function homepagelabel($id,$type){
		global $db;
		$query = $db->query("SELECT * FROM `homepage_label` WHERE `id`='$id'");
		$row = $query->fetch_assoc();
		if($type=="link"): if($row['custom_link']<>''): echo' href="'.$row['custom_link'].'" target="_blank"'; endif; else: echo $row['title']; endif;	
	}	
	

	function homepageaboutus(){
		global $db;
		$query = $db->query("SELECT * FROM `homepage_aboutus` ORDER BY `id` ASC ");
		$row = $query->fetch_assoc();
		echo'<div class="split-1per4-768 fab-1">';
             echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['title'].'" />';
        echo'</div><!-- .split-1per4-768 -->';
		echo'<div class="split-3per4-768 fab-2">';
         	   echo $row['description'];         
        echo'</div><!-- .split-3per4-768 -->';
	}
	
	
	function ourpartner(){
		global $db;
		$query = $db->query("SELECT * FROM `partner_list` WHERE `publish`=1 ORDER BY `sortnumber` ASC");
		while($row = $query->fetch_assoc()):
			echo'<li><div class="img-wrap">';
				if($row['website_link']!=""):
					echo'<a href="'.$row['website_link'].'" title="" target="_blank"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['thumb_image'].'" alt="'.$row['partner_name'].'" /></a>';
				else:
					echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['thumb_image'].'" alt="'.$row['partner_name'].'" />';
				endif;
			echo'</div></li>';
		endwhile;
	}		

	function ourclient(){
		global $db;
		$query = $db->query("SELECT * FROM `client_list` WHERE `publish`=1 ORDER BY `sortnumber` ASC");
		while($row = $query->fetch_assoc()):
			echo'<li><div class="img-wrap">';
				if($row['website_link']!=""):
					echo'<a href="'.$row['website_link'].'" title="" target="_blank"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['thumb_image'].'" alt="'.$row['client_name'].'" /></a>';
				else:
					echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['thumb_image'].'" alt="'.$row['client_name'].'" />';
				endif;
			echo'</div></li>';
		endwhile;
	}		

	function faqlist(){
		global $db;
		$query = $db->query("SELECT * FROM `faq` WHERE `publish`=1 ORDER BY `sortnumber` ASC");
		while($row = $query->fetch_assoc()):
         	echo'<div class="general_accor">
                     <h3>'.$row['question'].'</h3>
                     <div class="ga_content">
                        <div class="nuke-wysiwyg">
                            '.$row['answer'].'
                         </div><!-- .nuke-wysiwyg -->
                     </div><!-- .ga_content -->
                </div><!-- .general_accor -->';					
		endwhile;
	}
	
		
		
		
	
	
	
	
	
	
	
	
	
	
	
	//vocuher cari
	function getstatusvoucher($idmember,$codevoucher){
		global $db;
		$quep = $db->query("SELECT `id` FROM `order_header` WHERE `idmember`='$idmember' and `vouchercode`='$codevoucher'");
		$jumawalcode = $quep->num_rows;
		return $jumawalcode;
	}
	
	function getstatusidkat($idprod){
		global $db;
		$quep = $db->query("SELECT `idkat` FROM `product` WHERE `id`='$idprod'");
		$row = $quep->fetch_assoc();
		return $row['idkat'];	
	}
	
	function getdiskonmember($codevoucher,$idprod,$beliqty,$pricebeli,$idmember){
			global $db;
			date_default_timezone_set('Asia/Jakarta');				
			
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
					$status_item = $row['status_item'];
					$diskon_type = $row['diskon_type'];
					if($row['mak_item_buy']==0): 
						$mak_item_buy = 1; 
					else: 
						$mak_item_buy = $row['mak_item_buy']; 
					endif;
					
					$diskon_value = $row['diskon_value'];
					$idkatdiskon = $row['category_item'];
					$idproddiskon = $row['product_item'];
					$idbranditemdiskon = $row['brand_item'];
					
					//new
					$discountStatus = $row['discount_status'];
					if($discountStatus == "Grand Total"):
						return 0;
					else:									
												
							if($jumstock_voucher < 1):
								unset($_SESSION['voucher_redeeemID']);
								return 0;
							else:
								//stock voucher oke
								
									$useruseVocuher = getstatusvoucher($idmember,$codevoucher);
									if($useruseVocuher>0):
										unset($_SESSION['voucher_redeeemID']);
										return 0;
									else:
										
		
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
																	
																	//qty
																	if($beliqty>$mak_item_buy):
																		$totalqty_diskon = $mak_item_buy;
																	else:
																		$totalqty_diskon = $beliqty;
																	endif;
																			
																					
																	if($status_item=="ALL PRODUCT"):
																		
																		if($diskon_type=="AMOUNT"):
																			$diskon_harga1 = $diskon_value;
																			$diskon_harga2 = $diskon_harga1*$totalqty_diskon;
																			return $diskon_harga2;
																		elseif($diskon_type=="PERCENT"):
																			$diskon_harga1 = ($pricebeli*$diskon_value)/100;
																			$diskon_harga2 = round($diskon_harga1);
																			$diskon_harga3 = $diskon_harga2*$totalqty_diskon;
																			return $diskon_harga3;
																		endif;
																		
																	elseif($status_item=="ONLY CATEGORY"):
																			
																			$idkatprod = getstatusidkat($idprod);
																			if($idkatprod==$idkatdiskon):
																					if($diskon_type=="AMOUNT"):
																						$diskon_harga1 = $diskon_value;
																						$diskon_harga2 = $diskon_harga1*$totalqty_diskon;
																						return $diskon_harga2;
																					elseif($diskon_type=="PERCENT"):
																						$diskon_harga1 = ($pricebeli*$diskon_value)/100;
																						$diskon_harga2 = round($diskon_harga1);
																						$diskon_harga3 = $diskon_harga2*$totalqty_diskon;
																						return $diskon_harga3;
																					endif;	
																			endif;
																	
																	elseif($status_item=="ONLY PRODUCT"):
																	
																			if($idprod==$idproddiskon):
																					if($diskon_type=="AMOUNT"):
																						$diskon_harga1 = $diskon_value;
																						$diskon_harga2 = $diskon_harga1*$totalqty_diskon;
																						return $diskon_harga2;
																					elseif($diskon_type=="PERCENT"):
																						$diskon_harga1 = ($pricebeli*$diskon_value)/100;
																						$diskon_harga2 = round($diskon_harga1);
																						$diskon_harga3 = $diskon_harga2*$totalqty_diskon;
																						return $diskon_harga3;
																					endif;
																			endif;
		
																	elseif($status_item=="BRAND"):
																			
																			$status_dapat_haidah = 0;
																			$idbrandprod = getidbarndproductlist($idprod);
																			$status_ada_dapat = getstatusbrandgift($idbrandprod,$idbranditemdiskon);
																			if($status_ada_dapat==1): $status_dapat_haidah = $status_dapat_haidah+1; endif;	
																			
																			if($status_dapat_haidah>0):
																					if($diskon_type=="AMOUNT"):
																						$diskon_harga1 = $diskon_value;
																						$diskon_harga2 = $diskon_harga1*$totalqty_diskon;
																						return $diskon_harga2;
																					elseif($diskon_type=="PERCENT"):
																						$diskon_harga1 = ($pricebeli*$diskon_value)/100;
																						$diskon_harga2 = round($diskon_harga1);
																						$diskon_harga3 = $diskon_harga2*$totalqty_diskon;
																						return $diskon_harga3;
																					endif;
																			endif;																	
																	
																	endif;
																	//end cari voucher list
																	
																	// end qty
																	
																	
																else:
																	unset($_SESSION['voucher_redeeemID']);
																	return 0;
																endif;	
															   //END CEK DATE
															   
									
									endif;
									
								//end stock oke
							endif;
					
				endif;	
				//end new
						
					
			else:
				unset($_SESSION['voucher_redeeemID']);
				return 0;
			endif;
		
	}

	function getdiskonmember_grandtotal($codevoucher,$grandTotal,$idmember,$listbrandprod,$listproductid){
			global $db;
			date_default_timezone_set('Asia/Jakarta');	
			
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
					$status_item = $row['status_item'];
					$diskon_type = $row['diskon_type'];
					if($row['mak_item_buy']==0): 
						$mak_item_buy = 1; 
					else: 
						$mak_item_buy = $row['mak_item_buy']; 
					endif;
					
					$diskon_value = $row['diskon_value'];
					$idkatdiskon = $row['category_item'];
					$idproddiskon = $row['product_item'];
					$idbranditemdiskon = $row['brand_item'];
					
					//new
					$discountStatus = $row['discount_status'];
					if($discountStatus == "Per Item"):
						return 0;
					else:									
												
							if($jumstock_voucher < 1):
								unset($_SESSION['voucher_redeeemID']);
								return 0;
							else:
								//stock voucher oke
								
									$useruseVocuher = getstatusvoucher($idmember,$codevoucher);
									if($useruseVocuher>0):
										unset($_SESSION['voucher_redeeemID']);
										return 0;
									else:
										
		
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
																	
																	if($grandTotal >= $minbelanja and ( $grandTotal<=$makbelanja or $makbelanja==0 ) ):
																		
																		//voucher oke
																		if($status_item=="ALL PRODUCT"):
																			
																			if($diskon_type=="AMOUNT"):
																				return $diskon_value;
																			elseif($diskon_type=="PERCENT"):
																				$diskon_harga1 = ($grandTotal*$diskon_value)/100;
																				$diskon_harga2 = round($diskon_harga1);
																				return $diskon_harga2;
																			endif;
																			
																		elseif($status_item=="ONLY CATEGORY"):
																				$status_category_voucher = getstatusVouchercategory($listproductid,$idkatdiskon);
																				
																				if($status_category_voucher > 0):
																						if($diskon_type=="AMOUNT"):
																							return $diskon_value;
																						elseif($diskon_type=="PERCENT"):
																							$diskon_harga1 = ($grandTotal*$diskon_value)/100;
																							$diskon_harga2 = round($diskon_harga1);
																							return $diskon_harga2;
																						endif;	
																				endif;
																		
																		elseif($status_item=="ONLY PRODUCT"): //not progress
																		
																				$status_category_prodselected = getstatusVoucherproductseleted($listproductid,$idproddiskon);
																				
																				if($status_category_prodselected > 0):
																						if($diskon_type=="AMOUNT"):
																							return $diskon_value;
																						elseif($diskon_type=="PERCENT"):
																							$diskon_harga1 = ($grandTotal*$diskon_value)/100;
																							$diskon_harga2 = round($diskon_harga1);
																							return $diskon_harga2;
																						endif;	
																				endif;	
			
																		elseif($status_item=="BRAND"):
																				
																				$status_category_vbrand = getstatusVoucherbrandseleted($listbrandprod,$idbranditemdiskon);
																				
																				if($status_category_vbrand > 0):
																						if($diskon_type=="AMOUNT"):
																							return $diskon_value;
																						elseif($diskon_type=="PERCENT"):
																							$diskon_harga1 = ($grandTotal*$diskon_value)/100;
																							$diskon_harga2 = round($diskon_harga1);
																							return $diskon_harga2;
																						endif;	
																				endif;																
																		
																		endif;


																	else:
																		unset($_SESSION['voucher_redeeemID']);
																		return 0;
																	endif;
																	 
																	 
																	
																	
																else:
																	unset($_SESSION['voucher_redeeemID']);
																	return 0;
																endif;	
															   //END CEK DATE
															   
									
									endif;
									
								//end stock oke
							endif;
					
				endif;	
				//end new
						
					
			else:
				unset($_SESSION['voucher_redeeemID']);
				return 0;
			endif;
		
	}


	function cek_statusgetdiskonmemberGift($codevoucher,$idmember,$listbrandprod){
			global $db;
			date_default_timezone_set('Asia/Jakarta');				
			$output = ''; $statusbrand_idvoucher  = '';
			
			$quep = $db->query("SELECT * FROM `voucher_online` WHERE `voucher_code` = '$codevoucher' and `stock` > 0 ");
			$jumawalcode = $quep->num_rows;
			if($jumawalcode>0):
					
					$row = $quep->fetch_assoc();
					$idvoucheronline = $row['id'];
					$idbranditemdiskon = $row['brand_item'];
					$start_date = $row['start_date'];
					$expiry_date = $row['end_date'];
					$diskon_type = $row['diskon_type'];
					
					$useruseVocuher = getstatusvoucher($idmember,$codevoucher);
					if($useruseVocuher < 1):
										
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
								
								if($diskon_type=="BONUS PRODUCT"):
								
										 //validasi id brand
										 $status_dapat_haidah = 0; $idbard_explode = 0;
										 $list_explode_brand = explode("#",$listbrandprod);
										 $jumbrand = count($list_explode_brand);
										 
										 for($xBrand = 0; $xBrand<$jumbrand; $xBrand++){
											$idbard_explode = $list_explode_brand[$xBrand];
											if($idbard_explode>0):
												$status_ada_dapat = getstatusbrandgift($idbard_explode,$idbranditemdiskon);
												if($status_ada_dapat==1): $status_dapat_haidah = $status_dapat_haidah+1; endif;	
											endif;
										 }
										 //end validasi brand id
										 
										 if($status_dapat_haidah>0):
										 	$output = getbonuslist($idvoucheronline); 
										 else:
										 	//maaaf tidak dapat
										 endif;
										
								endif;
								//end type voucher
								
												
							endif;
							
				  endif;			

			endif;			
			
			return $output;
	}
	
	function getstatusbrandgift($idbard_prod,$idbranditemdiskon){
		$list_explode_brand = explode("#",$idbranditemdiskon);
		$jumbrand = count($list_explode_brand);
		
		for($xBrand = 0; $xBrand<$jumbrand; $xBrand++){	
			$idbard_explode = $list_explode_brand[$xBrand];
			if($idbard_explode==$idbard_prod):
				return 1;
			endif;
		}
	}
	
	function getstatusVouchercategory($listproductid,$idkatdiskon){
		$jumlah_v = 0;
		$list_explode_brand = explode("#",$listproductid);
		$jumbrand = count($list_explode_brand);	

		for($xBrand = 0; $xBrand<$jumbrand; $xBrand++){	
			$idprod_explode = $list_explode_brand[$xBrand];
			$idkatprod = getstatusidkat($idprod_explode);
			
			if($idkatprod == $idkatdiskon):
				$jumlah_v = $jumlah_v+1;
			endif;
		}
		
		return $jumlah_v;		
	}
	
	function getstatusVoucherproductseleted($listproductid,$idproddiskon){
		$jumlah_v = 0;
		$list_explode_brand = explode("#",$listproductid);
		$jumbrand = count($list_explode_brand);	

		for($xBrand = 0; $xBrand<$jumbrand; $xBrand++){	
			$idprod_explode = $list_explode_brand[$xBrand];
			
			if($idprod_explode == $idproddiskon):
				$jumlah_v = $jumlah_v+1;
			endif;
		}
		
		return $jumlah_v;	
	}
	
	function getstatusVoucherbrandseleted($listbrandprod,$idbranditemdiskon){

		$jumlah_v = 0;
		$list_explode_brand = explode("#",$listbrandprod);
		$jumbrand = count($list_explode_brand);	

		for($xBrand = 0; $xBrand<$jumbrand; $xBrand++){	
			$idprod_brandexplode = $list_explode_brand[$xBrand];
			
			$list_explode_brandVch = explode("#",$idbranditemdiskon);
			$jumbrandVch = count($list_explode_brandVch);	
			
			for($xBrandVch = 0; $xBrandVch<$jumbrandVch; $xBrandVch++){	
				$idprod_brandexplodeVch = $list_explode_brandVch[$xBrandVch];	
				if($idprod_brandexplodeVch == $idprod_brandexplode and $idprod_brandexplodeVch!="" and $idprod_brandexplodeVch!="#"):
					$jumlah_v = $jumlah_v+1;
				endif;
			}				
		}
		
		return $jumlah_v;	
			
	}


	function cek_statusgetdiskonmemberGiftsaveorder($codevoucher,$idmember,$listbrandprod){
			global $db;
			date_default_timezone_set('Asia/Jakarta');				
			$output = ''; $statusbrand_idvoucher  = '';
			
			$quep = $db->query("SELECT * FROM `voucher_online` WHERE `voucher_code` = '$codevoucher' and `stock` > 0 ");
			$jumawalcode = $quep->num_rows;
			if($jumawalcode>0):
					
					$row = $quep->fetch_assoc();
					$idvoucheronline = $row['id'];
					$idbranditemdiskon = $row['brand_item'];
					$start_date = $row['start_date'];
					$expiry_date = $row['end_date'];
					$diskon_type = $row['diskon_type'];
					
					$useruseVocuher = getstatusvoucher($idmember,$codevoucher);
					if($useruseVocuher < 1):
										
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
								
								if($diskon_type=="BONUS PRODUCT"):
								
									    //validasi id brand
										 $status_dapat_haidah = 0; $idbard_explode = 0;
										 $list_explode_brand = explode("#",$listbrandprod);
										 $jumbrand = count($list_explode_brand);
										 
										 for($xBrand = 0; $xBrand<$jumbrand; $xBrand++){
											$idbard_explode = $list_explode_brand[$xBrand];
											if($idbard_explode>0):
												$status_ada_dapat = getstatusbrandgift($idbard_explode,$idbranditemdiskon);
												if($status_ada_dapat==1): $status_dapat_haidah = $status_dapat_haidah+1; endif;	
											endif;
										 }
										 //end validasi brand id
										 
										 if($status_dapat_haidah>0):
										 	$output = cekproductavaildiskon($idvoucheronline);
										 else:
										 	//maaaf tidak dapat
										 endif;
										 
								endif;
								//end type voucher
								
												
							endif;
							
				  endif;			

			endif;			
			
			return $output;
	}
	
	function cekproductavaildiskon($idvoucheronline){
		global $db;
		$datajumm = 0;
		$que = $db->query("SELECT `id_product_gift` FROM `list_product_haidah` WHERE `id_voucher_online` = '$idvoucheronline' ORDER BY `id` ASC ");
		while($row = $que->fetch_assoc()):
				$query = $db->query("SELECT * FROM `product_gift` WHERE `id`='".$row['id_product_gift']."' and `publish` = 1 ");
				$jumpage = $query->num_rows;
				$datajumm = $datajumm+$jumpage;												
		endwhile;		
		
		return $datajumm;
	}
	
	function insertitemlistprod($codevoucher,$tokenpaymntid,$idmember,$listbrandprod){
			global $db;
			date_default_timezone_set('Asia/Jakarta');				
			$output = ''; $statusbrand_idvoucher  = '';
			
			$quep = $db->query("SELECT * FROM `voucher_online` WHERE `voucher_code` = '$codevoucher' and `stock` > 0 ");
			$jumawalcode = $quep->num_rows;
			if($jumawalcode>0):
					
					$row = $quep->fetch_assoc();
					$idvoucheronline = $row['id'];
					$idbranditemdiskon = $row['brand_item'];
					$start_date = $row['start_date'];
					$expiry_date = $row['end_date'];
					$diskon_type = $row['diskon_type'];
					
					$useruseVocuher = getstatusvoucher($idmember,$codevoucher);
					if($useruseVocuher < 1):
										
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
								
								if($diskon_type=="BONUS PRODUCT"):
								
										 //validasi id brand
										 $status_dapat_haidah = 0; $idbard_explode = 0;
										 $list_explode_brand = explode("#",$listbrandprod);
										 $jumbrand = count($list_explode_brand);
										 
										 for($xBrand = 0; $xBrand<$jumbrand; $xBrand++){
											$idbard_explode = $list_explode_brand[$xBrand];
											if($idbard_explode>0):
												$status_ada_dapat = getstatusbrandgift($idbard_explode,$idbranditemdiskon);
												if($status_ada_dapat==1): $status_dapat_haidah = $status_dapat_haidah+1; endif;	
											endif;
										 }
										 //end validasi brand id
										 
										 if($status_dapat_haidah>0):
										 	insertitemlistprodstep2($idvoucheronline,$tokenpaymntid); 
										 else:
										 	//maaaf tidak dapat
										 endif;
										 
								endif;
								//end type voucher
								
												
							endif;
							
				  endif;			

			endif;			
	}
	
	function insertitemlistprodstep2($idvoucheronline,$tokenOrder){
		global $db;
		$que = $db->query("SELECT `id_product_gift` FROM `list_product_haidah` WHERE `id_voucher_online` = '$idvoucheronline' ORDER BY `id` ASC ");
		while($row = $que->fetch_assoc()):
				$query = $db->query("SELECT * FROM `product_gift` WHERE `id`='".$row['id_product_gift']."' and `publish` = 1 ");
				$jumpage = $query->num_rows;
				if($jumpage>0):
					$item = $query->fetch_assoc(); 		
					$idpod = $item['id']; $skupord = $item['sku']; $namprod = $item['title'];
					$query = $db->query("INSERT INTO `order_detail_bonus_product` (`tokenpay`,`idproduct`,`sku`,`name`,`qty`) VALUES ('$tokenOrder','$idpod','$skupord','$namprod','1')") or die($db->error);
				endif;													
		endwhile;		
	}
	
	function cekstatusada($listdata,$iddata){
		if($listdata==""): return 0; else:
			$list_explode_brand = explode("#",$listdata);
			$jumbrand = count($list_explode_brand);
			for($xBrand = 0; $xBrand<$jumbrand; $xBrand++){
				$idbard_explode = $list_explode_brand[$xBrand];
				if($iddata == $idbard_explode): return 1; endif;
			}  
		endif;
	}
	
	function getbonuslist($idvoucherCode){
		global $db;
		$output = '';
		$que = $db->query("SELECT `id_product_gift` FROM `list_product_haidah` WHERE `id_voucher_online` = '$idvoucherCode' ORDER BY `id` ASC ");
		while($row = $que->fetch_assoc()):
				 
				$query = $db->query("SELECT * FROM `product_gift` WHERE `id`='".$row['id_product_gift']."' and `publish` = 1 ");
				$jumpage = $query->num_rows;
				if($jumpage>0):
					
					$item = $query->fetch_assoc(); 		
					$output.='<div class="cpt-child list_bonus_product">';
								 $output.='<div class="img-wrap"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$item['thumb_image'].'" /></div>';
												$output.='<div class="scb1-txt clearfix">';
													$output.='<div class="scb1-1">';
														$output.='<h3 class="f-pr">'.$item['title'].'';
														$output.= '<span class="jcartlistitem">';
															$output.= '<br /><strong>'.$item['sku'].'</strong>';
														$output.= '</span></h3>';
														$output.='<p>Qty. 1 item</p>';											
													$output.='</div><!-- .scb1-1 -->';
		
													$output.='<div class="scb23">';
														$output.='<div class="scb1-3">';
															$output.='<span class="error_voucherlist">* free item product</span>';
														$output.='</div><!-- .scb1-3 -->';
													$output.='</div><!-- .scb23 -->';
							  $output.='</div><!-- .scb1-txt -->';
					   $output.='</div><!-- .cpt-child -->';
 
				endif;						
										
		endwhile;
		
		return $output;
	}

	function getidbarndproductlist($idprod){
		global $db;
		$que = $db->query("SELECT `brand` FROM `product` WHERE `id`='$idprod' ");
		$row = $que->fetch_assoc();
		return $row['brand'];	
	}
	
	//order list member
	function getorderlist($idmember,$sort_filtertahun){
		global $db;
		$output = ''; $pagexx = 0; $totalamount = 0;
		
		if($sort_filtertahun<>''):
			$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d/%m/%Y, %h:%i:%s') as tgl FROM `order_header` WHERE `idmember`='$idmember' and DATE_FORMAT(`date`,'%Y-%m') = '$sort_filtertahun' ORDER BY `date` DESC") or die($db->error);
		else:
			$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d/%m/%Y, %h:%i:%s') as tgl FROM `order_header` WHERE `idmember`='$idmember' ORDER BY `date` DESC") or die($db->error);
		endif;
		
		while($row = $que->fetch_assoc()):
					
					$totalamount = (($row['orderamount']+$row['shippingcost']+$row['kode_unik']+$row['handling_fee'])-$row['discountamount']);
				
					if($row['status_payment']=="Pending On Payment"):
						$KLass = '<strong style="color:#FF3333;">Menunggu Pembayaran</strong>';
										
					elseif($row['status_payment']=="Waiting"):
						$KLass = '<strong style="color:#FF9933;">Menunggu Konfirmasi Pembayaran</strong>';
										
					elseif($row['status_payment']=="Confirmed"):
						
						if($row['status_delivery']=="Pending On Delivery"):
							$KLass = '<strong style="color:#FF9933;">Menunggu Pengiriman</strong>';
						else:
							$KLass = '<strong style="color:#339966;">Terkirim</strong>';
						endif;	
				   
				   else:
						if($row['status_payment']=='Cancelled By User' or $row['status_payment']=='Cancelled By Admin' or $row['status_payment']=='Autocancel'):
							$KLass = '<strong style="color:#FF3333;">Dibatalkan</strong>';
						else:
							$KLass = '<strong style="color:#FF3333;">'.ucwords($row['status_payment']).'</strong>';
						endif;	
				   endif;	
			
					 $output.='<div class="otbc">';
                        	$output.='<div class="otbc-1">';
                            	$output.='<span class="f-pb">#'.sprintf('%06d',$row['id']).'</span>';
                                $output.='<span class="f-1200-14px">'.$row['tgl'].'</span>';
								$output.='<span class="f-1200-14px"><strong>PAY: '.strtoupper($row['payment_metod']).'</strong></span>';
                            $output.='</div><!-- .otbc-1 -->';
                            $output.='<div class="otbc-2">';
                            	$output.='<div class="otbc-2-1">';
                                	$output.='<ul class="order-product">';
                                    	 $output.= getproductdetailjcart($row['tokenpay']);
                                    $output.='</ul><!-- .order-product -->';
                                    $output.='<div class="order-price f-1200-14px">';
                                    	$output.='TOTAL <strong>Rp '.number_format($totalamount).'</strong>';
                                    $output.='</div><!-- .order-price -->';
									$output.='<a href="'.$GLOBALS['SITE_URL'].'order-detail/'.$row['id'].'-'.$row['tokenpay'].'" class="f-1200-14px">Lihat detail order dan detail status</a>';
                                $output.='</div><!-- .otbc-2-1 -->';
                                $output.='<div class="otbc-2-2">';
                                	$output.='<span class="order-stat"><span>STATUS: </span>'.$KLass.'</span>';
									
								  if($row['status_payment']=="Pending On Payment"):
								  		$output.='<a href="'.$GLOBALS['SITE_URL'].'cancel-order/'.$row['id'].'-'.$row['tokenpay'].'" class="f-1200-14px"
										onclick="return confirm(\'Are you sure cancel this order?\');">Cancel Order</a>';
								  endif;
								  
                               $output.='</div><!-- .otbc-2-2 -->';
                            $output.='</div><!-- .otbc-2 -->';
                       $output.='</div><!-- .otbc -->';
							
			$pagexx++;	
		endwhile;
		echo $output;
		
		if($pagexx==0): echo'<p style="padding-left:10px; padding-top:10px; color:#ffa827;">You haven\'t add any order list.</p>'; endif;		
	}

	function getorderlistcop($idmember,$sort_filtertahun,$iduser,$status_user){
		global $db;
		$output = ''; $pagexx = 0; $totalamount = 0;
		
		if($status_user==1):
			if($sort_filtertahun<>''):
				$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d/%m/%Y, %h:%i:%s') as tgl FROM `order_header` WHERE `idmember`='$idmember' and DATE_FORMAT(`date`,'%Y-%m') = '$sort_filtertahun' ORDER BY `date` DESC") or die($db->error);
			else:
				$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d/%m/%Y, %h:%i:%s') as tgl FROM `order_header` WHERE `idmember`='$idmember' ORDER BY `date` DESC") or die($db->error);
			endif;
		else:
			if($sort_filtertahun<>''):
				$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d/%m/%Y, %h:%i:%s') as tgl FROM `order_header` WHERE `idmember`='$idmember' and `id_user`='$iduser' and DATE_FORMAT(`date`,'%Y-%m') = '$sort_filtertahun' ORDER BY `date` DESC") 
				or die($db->error);
			else:
				$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d/%m/%Y, %h:%i:%s') as tgl FROM `order_header` WHERE `idmember`='$idmember' and `id_user`='$iduser' ORDER BY `date` DESC") or die($db->error);
			endif;		
		endif;
		
		while($row = $que->fetch_assoc()):
					
					if($row['payment_metod']=="Deposit"):
						$totalamount = (($row['orderamount']+$row['shippingcost']+$row['kode_unik']+$row['handling_fee'])-$row['discountamount']);
					else:
						$totalamount = (($row['orderamount']+$row['shippingcost']+$row['kode_unik']+$row['handling_fee'])-$row['discountamount'])-$row['deposit_amount'];
					endif;
					
					if($row['status_payment']=="Pending On Payment"):
						$KLass = '<strong style="color:#FF3333;">Menunggu Pembayaran</strong>';
										
					elseif($row['status_payment']=="Waiting"):
						$KLass = '<strong style="color:#FF9933;">Menunggu Konfirmasi Pembayaran</strong>';
										
					elseif($row['status_payment']=="Confirmed"):
						
						if($row['status_delivery']=="Pending On Delivery"):
							$KLass = '<strong style="color:#FF9933;">Menunggu Pengiriman</strong>';
						else:
							$KLass = '<strong style="color:#339966;">Terkirim</strong>';
						endif;	
				   
				   else:
						if($row['status_payment']=='Cancelled By User' or $row['status_payment']=='Cancelled By Admin' or $row['status_payment']=='Autocancel'):
							$KLass = '<strong style="color:#FF3333;">Dibatalkan</strong>';
						else:
							$KLass = '<strong style="color:#FF3333;">'.ucwords($row['status_payment']).'</strong>';
						endif;	
				   endif;	
			
					 $output.='<div class="otbc">';
                        	$output.='<div class="otbc-1">';
                            	$output.='<span class="f-pb">#'.sprintf('%06d',$row['id']).'</span>';
                                $output.='<span class="f-1200-14px">'.$row['tgl'].'</span>';
								$output.='<span class="f-1200-14px"><strong>PAY: '.strtoupper($row['payment_metod']).'</strong></span>';
								$output.='<span class="f-1200-14px">By: '.getnamegeneral($row['id_user'],"corporate_user","name").' '.getnamegeneral($row['id_user'],"corporate_user","lastname").'</span>';
                            $output.='</div><!-- .otbc-1 -->';
                            $output.='<div class="otbc-2">';
                            	$output.='<div class="otbc-2-1">';
                                	$output.='<ul class="order-product">';
                                    	 $output.= getproductdetailjcart($row['tokenpay']);
                                    $output.='</ul><!-- .order-product -->';
                                    $output.='<div class="order-price f-1200-14px">';
                                    	$output.='TOTAL <strong>Rp '.number_format($totalamount).'</strong>';
                                    $output.='</div><!-- .order-price -->';
									$output.='<a href="'.$GLOBALS['SITE_URL'].'order-detail-corporate/'.$row['id'].'-'.$row['tokenpay'].'" class="f-1200-14px">Lihat detail order dan detail status</a>';
                                $output.='</div><!-- .otbc-2-1 -->';
                                $output.='<div class="otbc-2-2">';
                                	$output.='<span class="order-stat"><span>STATUS: </span>'.$KLass.'</span>';
									
								  if($row['status_payment']=="Pending On Payment"):
								  		$output.='<a href="'.$GLOBALS['SITE_URL'].'cancel-order-corporate/'.$row['id'].'-'.$row['tokenpay'].'" class="f-1200-14px"
										onclick="return confirm(\'Are you sure cancel this order?\');">Cancel Order</a>';
								  endif;
								  
                               $output.='</div><!-- .otbc-2-2 -->';
                            $output.='</div><!-- .otbc-2 -->';
                       $output.='</div><!-- .otbc -->';
							
			$pagexx++;	
		endwhile;
		echo $output;
		
		if($pagexx==0): echo'<p style="padding-left:10px; padding-top:10px; color:#ffa827;">You haven\'t add any order list.</p>'; endif;		
	}	

	function getimagesdetail($id){
		global $db;
		$que = $db->query("SELECT `image` FROM `product` WHERE `id`='$id'");
		$row = $que->fetch_assoc();
		return $row['image'];		
	}
	
	function getimagesdetailbonus($id){
		global $db;
		$que = $db->query("SELECT `thumb_image` FROM `product_gift` WHERE `id`='$id'");
		$row = $que->fetch_assoc();
		return $row['thumb_image'];		
	}
		
	function getproductdetailjcart($tokenid){
		global $db;
		$output = '';
		$que = $db->query("SELECT `idproduct`,`name` FROM `order_detail` WHERE `tokenpay`='$tokenid' ORDER BY `id` ASC");
		while($row = $que->fetch_assoc()):
			$imgname = getimagesdetail($row['idproduct']);
			$output.='<li><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($row['name']).'/'.$row['idproduct'].'" target="_blank"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$imgname.'" /></a></li>';
		endwhile;
		
		//new cek bonus
		$query = $db->query("SELECT `idproduct` FROM `order_detail_bonus_product` WHERE `tokenpay`='$tokenid' ORDER BY `id` ASC");
		while($data = $query->fetch_assoc()):
			$imgname_list = getimagesdetailbonus($data['idproduct']);
			$output.='<li><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$imgname_list.'" /></li>';
		endwhile;		
		
		return $output;	
	}	
	
	
	function buyprodlistbutton($idprd){
		global $db;
		$query = $db->query(" SELECT 
							  
							  `product_detail`.`id` as pid,
							  `product_detail_size`.`id` as pidsize
							  
							   FROM `product_detail` LEFT JOIN `product_detail_size` ON `product_detail_size`.`id_product_detail` = `product_detail`.`id`
							 
							   WHERE `product_detail`.`id_product`='$idprd' and `product_detail`.`publish`=1 and `product_detail_size`.`stock` > 0
							 
							   ORDER BY `product_detail`.`sortnumber` ASC ") 
		
		or die($db->error);
		
		
		$jumpage = $query->num_rows;
		if($jumpage>0):		
			$row = $query->fetch_assoc();
			return'<a href="#" class="plc-addtocart add_to_cartbtnList" id="itemaddlist-'.$idprd.'-'.$row['pid'].'-'.$row['pidsize'].'">'.labelmenu2(62).'</a>';
		else:
			return'<a class="plc-addtocart">SOLD OUT</a>';
		endif;	
		 
	}	

	function statussoldprod($idprd){
		global $db;
		$query = $db->query(" SELECT 
							  
							  `product_detail`.`id` as pid,
							  `product_detail_size`.`id` as pidsize
							  
							   FROM `product_detail` LEFT JOIN `product_detail_size` ON `product_detail_size`.`id_product_detail` = `product_detail`.`id`
							 
							   WHERE `product_detail`.`id_product`='$idprd' and `product_detail`.`publish`=1 and `product_detail_size`.`stock` > 0
							 
							   ORDER BY `product_detail`.`sortnumber` ASC ") 
		
		or die($db->error);
		
		
		$jumpage = $query->num_rows;
		if($jumpage>0):		
			return 1;
		else:
			return 0;
		endif;	
		 
	}		

	function statuscolorselected($idprd){
		global $db;
		$query = $db->query(" SELECT 
							  
							  `product_detail`.`id` as pid
							  
							   FROM `product_detail` LEFT JOIN `product_detail_size` ON `product_detail_size`.`id_product_detail` = `product_detail`.`id`
							 
							   WHERE `product_detail`.`id_product`='$idprd' and `product_detail`.`publish`=1 and `product_detail_size`.`stock` > 0
							 
							   ORDER BY `product_detail`.`sortnumber` ASC ") 
		
		or die($db->error);
		
		
		$jumpage = $query->num_rows;
		if($jumpage>0):		
			$row = $query->fetch_assoc();
			return $row['pid'];
		else:
			return 0;
		endif;	
		 
	}
		
	function productcolor($idprod){
		global $db;
		$query = $db->query("SELECT * FROM `product_detail` WHERE `id_product`='$idprod' and `publish`=1 ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):		
			while($row = $query->fetch_assoc()):
				  $iddetailada_stock = statuscolorselected($idprod);
				  if($iddetailada_stock == $row['id']): $Klass = 'checked="checked"'; else: $Klass = ''; endif;	
				  	
                  echo'<div class="color-opt">';
                      echo'<input type="radio" id="color_prod-'.$row['id'].'" name="prod-color" class="col-radio product_color" '.$Klass.' />';
                      echo'<label for="color_prod-'.$row['id'].'" class="col-label"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['thumb_image'].'" title="'.$row['title'].'" /></label>';
                  echo'</div><!-- .color-opt -->';                 		
			endwhile;	
		else:
	         echo'<span class="notfound2">Not Available</span>';	
		endif;		
	}		

	function productsizeproduct($iddetail){
		global $db;
		$query = $db->query("SELECT * FROM `product_detail_size` WHERE `id_product_detail`='$iddetail' ORDER BY `size` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):		
			while($row = $query->fetch_assoc()):
				if($row['stock']==0): $Klass = 'disabled="disabled"'; $KlassGeneral = ''; else: $Klass = ''; $KlassGeneral = 'product_sizelist'; endif;
				echo'<div class="size-opt">';
					   echo'<input type="radio" id="colsize_prod-'.$row['id'].'" value="'.$row['id'].'" name="prod-size" class="col-radio '.$KlassGeneral.'" '.$Klass.' />';
					   echo'<label for="colsize_prod-'.$row['id'].'" class="col-label">'.$row['size'].'</label>';
				echo'</div><!-- .color-opt -->';				               		
			endwhile;	
		else:
	         echo'<span class="color-select-please">Please select product color!</span>';	
		endif;			
	}
	
	function getmasterattribute($idprod){
		global $db;
		$query = $db->query("SELECT * FROM `attribute_product` WHERE `product_id`='$idprod' and `publish`=1 ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):		
			while($row = $query->fetch_assoc()):
                 echo'<tr>';
                    echo'<td>'.getmsattname($row['attribute_id']).'</td>';
                    echo'<td class="td">:</td>';
                    echo' <td>'.$row['attribute_value'].'</td>';
                echo'</tr>';			
			endwhile;	
		else:
	         echo'<tr><td colspan="3"><span class="notfound2">Not Available</span></td></tr>';	
		endif;		
	}
			
	function getmsattname($id){
		global $db;
		$query2 = $db->query("SELECT `attribute_title` FROM `maste_attribute_product` WHERE `id`='$id' ") or die($db->error);
		$data = $query2->fetch_assoc();		
		return $data['attribute_title'];
	} 

	function getbrudcruhmbsubproddetail($idkat,$idsubkat){
		global $db;
		$query2 = $db->query("SELECT * FROM `subcategory` WHERE `id`='$idsubkat' and `publish`=1") or die($db->error);
		$data = $query2->fetch_assoc();
		
		$query = $db->query("SELECT * FROM `category` WHERE `id`='$idkat' and `publish`=1") or die($db->error);
		$row = $query->fetch_assoc();
		
		echo'<li><a href="'.$GLOBALS['SITE_URL'].'product">'.labelmenu2(59).'</a></li>';
		if($row['id']>0): echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-category/'.$row['id'].'/'.replace($row['name']).'">'.$row['name'].'</a></li>'; endif;
		if($data['id']>0): echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-subcategory/'.$data['id'].'/'.replace($data['name']).'">'.$data['name'].'</a></li>'; endif;
	}




			
	function bannerlistallcate($idkat){
		global $db;
		$query = $db->query("SELECT * FROM `product_category_banner` WHERE `idkat_banner`='$idkat' ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				 echo'<li><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" class="flexImages dark-image"  /></li>';
			endwhile;	
		else:
			echo'<li>'.bannerpage2(14).'</li>';
		endif;	
	}

	function bannerlistallsubcate($idsubkat){
		global $db;
		$query = $db->query("SELECT * FROM `product_subcategory_banner` WHERE `idsubkat_banner`='$idsubkat' ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				 echo'<li><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" class="flexImages dark-image"  /></li>';
			endwhile;	
		else:
			echo'<li>'.bannerpage2(14).'</li>';
		endif;	
	}		
	
	function getbrudcruhmbsub($idsubkat){
		global $db;
		$query2 = $db->query("SELECT * FROM `subcategory` WHERE `id`='$idsubkat' and `publish`=1") or die($db->error);
		$data = $query2->fetch_assoc();
		
		$query = $db->query("SELECT * FROM `category` WHERE `id`='".$data['idkat']."' and `publish`=1") or die($db->error);
		$row = $query->fetch_assoc();
		
		echo'<li><a href="'.$GLOBALS['SITE_URL'].'product">'.labelmenu2(59).'</a></li>';
		echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-category/'.$row['id'].'/'.replace($row['name']).'">'.$row['name'].'</a></li>';
		echo'<li class="f-dm last-child">'.$data['name'].'</li>';	
	}

	function getbrudcruhmbcate($idkat){
		global $db;
		$query = $db->query("SELECT * FROM `category` WHERE `id`='$idkat' and `publish`=1") or die($db->error);
		$row = $query->fetch_assoc();
		echo'<li><a href="'.$GLOBALS['SITE_URL'].'product">'.labelmenu2(59).'</a></li>';
		echo'<li class="f-dm last-child">'.$row['name'].'</li>';	
	}
	
	function getproductlistdetail($id,$act){
		global $db;
		$query = $db->query("SELECT * FROM `".$act."` WHERE `id`='$id' and `publish`=1") or die($db->error);
		$row = $query->fetch_assoc();
        echo'<h1 class="zig-border txt-up">'.$row['name'].'</h1>';
        echo'<div class="nuke-wysiwyg">';
          if($_SESSION['lang']=="eng"): echo $row['description_eng']; else: echo $row['description']; endif;	
        echo'</div><!-- .nuke-wysiwyg -->';				
	}
	
	function getproductlistdetailallprod(){
		global $db;
        echo'<h1 class="zig-border txt-up">'.labelmenu2(59).'</h1>';
        echo'<div class="nuke-wysiwyg">';
          pages(10);
        echo'</div><!-- .nuke-wysiwyg -->';			
	}
			
	function categorylist(){
		global $db;
		$query = $db->query("SELECT * FROM `category` WHERE `publish`=1 ORDER BY `sortnumber` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):
			
						$query2 = $db->query("SELECT * FROM `subcategory` WHERE `idkat`='".$row['id']."' and `publish`=1 ORDER BY `sortnumber` ASC") or die($db->error);
						$jumpage2 = $query2->num_rows;
						if($jumpage2>0):
							echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-category/'.$row['id'].'/'.replace($row['name']).'" class="has-sub"><span>'.$row['name'].'</span></a>';
								echo'<ul class="subnav f-dr">';
									while($data = $query2->fetch_assoc()):	
											echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-subcategory/'.$data['id'].'/'.replace($data['name']).'">'.$data['name'].'</a></li>';
									endwhile;
								echo'</ul>';
							echo'</li>';	
						else:
							echo'<li><a href="'.$GLOBALS['SITE_URL'].'product-category/'.$row['id'].'/'.replace($row['name']).'">'.$row['name'].'</a></li>';	
						endif;
		
			endwhile;	
		endif;	
			
	}	

	function getjumlahaddbook($idmember){
		global $db;
		$query = $db->query("SELECT `id` FROM `address_book` WHERE `member_id`='$idmember'");
		$jumdata = $query->num_rows;
		if($jumdata < 1 ): echo'<p>You haven\'t add any address book.</p><br>'; endif;		
	}

	function addressbook($idmember){
		global $db;
		$output = '';
		$que = $db->query("SELECT * FROM `address_book` WHERE `member_id`='$idmember' ORDER BY `id` ASC") or die($db->error);
		while($row = $que->fetch_assoc()):
				if($row['mobile_phone']<>''): $nope = '('.$row['mobile_phone'].')'; else: $nope = ''; endif;	
				
				 $output.='<div class="da-child">
                                <p><strong>'.ucwords($row['name']).' '.ucwords($row['lastname']).'</strong> '.$nope.'<br />
                                    '.nl2br($row['address']).'<br />
                                    '.getnamakota($row['idcity']).' - '.ucwords($row['kabupaten']).' - '.ucwords($row['provinsi']).'
                                </p>
                                <div class="btn-area clearfix">
                                    <a href="'.$GLOBALS['SITE_URL'].'edit-address/'.$row['id'].'" class="btn-edit f-rcb nuke-fancied2">EDIT</a>
                                    <a href="'.$GLOBALS['SITE_URL'].'delete-address/'.$row['id'].'" onclick="return confirm(\'Are you sure to delete this record?\')" class="btn-hapus f-rcb">DELETE</a>
                                </div><!-- .btn-area -->
                        </div><!-- .da-child -->';
		endwhile;
		echo $output;	
	}	

	function addressbookcorporate($idmember,$superuserstatus){
		global $db;
		$output = '';
		$que = $db->query("SELECT * FROM `address_book` WHERE `member_id`='$idmember' ORDER BY `id` ASC") or die($db->error);
		while($row = $que->fetch_assoc()):
				if($row['mobile_phone']<>''): $nope = '('.$row['mobile_phone'].')'; else: $nope = ''; endif;	
				
				 $output.='<div class="da-child">
                                <p><strong>'.ucwords($row['name']).' '.ucwords($row['lastname']).'</strong> '.$nope.'<br />
                                    '.nl2br($row['address']).'<br />
                                    '.getnamakota($row['idcity']).' - '.ucwords($row['kabupaten']).' - '.ucwords($row['provinsi']).'
                                </p>
                                <div class="btn-area clearfix">
                                    <a href="'.$GLOBALS['SITE_URL'].'edit-address-corporate/'.$row['id'].'" class="btn-edit f-rcb nuke-fancied2">EDIT</a>';
									if($superuserstatus==1):
                                    	 $output.='<a href="'.$GLOBALS['SITE_URL'].'delete-address-corporate/'.$row['id'].'" onclick="return confirm(\'Are you sure to delete this record?\')" class="btn-hapus f-rcb">DELETE</a>';
									endif;									
                                $output.='</div><!-- .btn-area -->
                        </div><!-- .da-child -->';
		endwhile;
		echo $output;	
	}	
		
	function getnamakota($id){
		global $db;
		$query = $db->query("SELECT `nama_kota` FROM `ongkir` WHERE `id`='$id'");
		$res = $query->fetch_assoc();
		return ucwords($res['nama_kota']);
	}	
	
	function getpropinsilist(){
		global $db;
		$query = $db->query("SELECT DISTINCT(`provinsi`) FROM `ongkir` ORDER BY `provinsi` ASC");
		while($row = $query->fetch_assoc()):
			echo'<option value="'.$row['provinsi'].'">'.ucwords($row['provinsi']).'</option>';		
		endwhile;
	}	

	function getpropinsilist_kabu($propinsi){
		global $db;
		$query = $db->query("SELECT DISTINCT(`kabupaten`) FROM `ongkir` WHERE `provinsi`='$propinsi' ORDER BY `kabupaten` ASC");
		while($row = $query->fetch_assoc()):
			echo '<option value="'.$row['kabupaten'].'">'.ucwords($row['kabupaten']).'</option>';
		endwhile;	
	}	
	
	function getpropinsilist_kota($kabupaten){
		global $db;
		$query = $db->query("SELECT `id`,`nama_kota` FROM `ongkir` WHERE `kabupaten`='$kabupaten' ORDER BY `nama_kota` ASC");
		while($row = $query->fetch_assoc()):
			echo '<option value="'.$row['id'].'">'.ucwords($row['nama_kota']).'</option>';
		endwhile;	
	}
			

			




	function homebanner(){
		global $db;
		$query = $db->query("SELECT * FROM `banner_ads_home` WHERE `publish`=1 ORDER BY `sortnumber` ASC");
		while($row = $query->fetch_assoc()):
			  echo'<div class="hsb-child">';
    				if($row['custom_link']==""):
						echo'<a href="#" title=""><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" class="lazyload" alt="'.$row['title'].'" /></a>';
					else:
						echo'<a href="'.$row['custom_link'].'" title="" target="_blank"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" class="lazyload" alt="'.$row['title'].'" /></a>';
					endif;
  			  echo'</div><!-- .hsb-child -->';	
		endwhile;
	}		

	function bannerpage($idpage){
		global $db;
		$query = $db->query("SELECT `image` FROM `banner_page` WHERE `id`='$idpage'");
		$row = $query->fetch_assoc();
	    echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" />';
	}	

	function bannerpage2($idpage){
		global $db;
		$query = $db->query("SELECT `image` FROM `banner_page` WHERE `id`='$idpage'");
		$row = $query->fetch_assoc();
		return '<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" />';
	}	

	function bannerpageloop($page_name){
		global $db;
		$query = $db->query("SELECT `image` FROM `banner_page` WHERE `page_name`='$page_name'");
		while($row = $query->fetch_assoc()):
	  	  echo'<li><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" class="flexImages dark-image" /></li>';
		endwhile;  
	}		
			
	function labelmenu($id){
		global $db;
		$query = $db->query("SELECT * FROM `label_menu` WHERE `id`='$id'");
		$row = $query->fetch_assoc();
		if($_SESSION['lang']=="eng"): echo $row['title_eng']; else: echo $row['title_indo']; endif;
	}		

	function labelmenu2($id){
		global $db;
		$query = $db->query("SELECT * FROM `label_menu` WHERE `id`='$id'");
		$row = $query->fetch_assoc();
		if($_SESSION['lang']=="eng"): return $row['title_eng']; else: return $row['title_indo']; endif;
	}	
	

	

	
	function history_about(){
		global $db;
		$query = $db->query("SELECT * FROM `history_about` ORDER BY `year` ASC");
		while($row = $query->fetch_assoc()):
			if($_SESSION['lang']=="eng"):
                   echo'<div class="hl-child">';
                       echo'<h2 class="blue-skew f-dm"><span class="skew"><span class="unskew">'.$row['year'].'</span></span></h2>';
                           echo'<div class="hl-content lazyload">';
                           	if($row['image']<>''): echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" />'; endif;
                            echo $row['description_eng'];
                          echo'</div><!-- .hl-content -->';
                   echo'</div><!-- .hl-child -->';			
			else: 
                   echo'<div class="hl-child">';
                       echo'<h2 class="blue-skew f-dm"><span class="skew"><span class="unskew">'.$row['year'].'</span></span></h2>';
                           echo'<div class="hl-content lazyload">';
                           	if($row['image']<>''): echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" />'; endif;
                            echo $row['description'];
                          echo'</div><!-- .hl-content -->';
                   echo'</div><!-- .hl-child -->';				
			endif;		
		endwhile;
	}	

	function technologies(){
		global $db;
		$query = $db->query("SELECT * FROM `technologies_list` ORDER BY `sortnumber` ASC");
		while($row = $query->fetch_assoc()):
			echo'<li><div class="img-wrap"><a href="'.$GLOBALS['SITE_URL'].'tech-detail/'.$row['id'].'/'.$row['title'].'" class="nuke-fancied2"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['thumb_image'].'" /></a></div></li>';	
		endwhile;
	}
		

	
	function generateFormToken($form) {
			$timeTg = date("YmdHms");
			$tokenIdFormall = $timeTg;
			$tokenIdForm = sha1($tokenIdFormall);
			$tokenIdForm2 = sha1($tokenIdForm);
			$tokenIdForm3 = sha1($tokenIdForm2);
			$tokenIdForm4 = md5($tokenIdForm3);
			$_SESSION[$form.'_token'] = $tokenIdForm4; 
			return $tokenIdForm4;
	}

	function getberatbarangTunas($idprod,$qtyprodall){
		global $db;
		$queryprd = $db->query("SELECT `weight` FROM `product_detail` WHERE `id`='$idprod' ");
		$data = $queryprd->fetch_assoc();
		$totalberat = $qtyprodall*$data['weight'];
		return $totalberat; 
	}	
	
	function getstockitemprod($idd,$idprod){
		global $db;
		$que = $db->query("SELECT `stock` FROM `product_detail` WHERE `id` = '$idd' and `idproduct_header` = '$idprod' ");
		$row = $que->fetch_assoc();
		return $row['stock'];
	}	

	function getidcitymember($idmember){
	  global $db;	
	  $que = $db->query("SELECT `idcity` FROM `billing_address` WHERE `idmember`='$idmember'");	
	  $row = $que->fetch_assoc();
	  return $row['idcity'];
	}

	function getcodecityabook($idabook,$idmember){
		global $db;	
		$que = $db->query("SELECT `idcity` FROM `address_book` WHERE `id`='$idabook' and `member_id`='$idmember'");
		$row = $que->fetch_assoc();
		return $row['idcity'];
	}	

	function getongkirdatalist($idcitymember,$JumlahBeratbrgProd,$kurir,$grandTotal){
	  global $db;	
	  
	  //select gratis ongkir
	  $queery_ongkir = $db->query("SELECT * FROM `free_ongkir_list` WHERE `idcity_list` = '$idcitymember' ");	
	  $jumpage_freeongkir = $queery_ongkir->num_rows;
	  if($jumpage_freeongkir>0):
	  		$data_free = $queery_ongkir->fetch_assoc();
			if($grandTotal >= $data_free['min_order_price']):
				return 0;
			else:
				  
				  $que = $db->query("SELECT `id` FROM `kurir_list` WHERE `kurir_name` = '$kurir' ");	
				  $row = $que->fetch_assoc();
				  $idkurir = $row['id'];
				  
				  $quepp = $db->query("SELECT * FROM `tarif_pengiriman` WHERE `kurir_name_id` = '$idkurir' and `nama_kota_id` = '$idcitymember' ");	
				  $data = $quepp->fetch_assoc();	
				  		  
				  if($JumlahBeratbrgProd > $data['min_weight']):
				  	 $totalberat = $JumlahBeratbrgProd;
				  else:
				  	  $totalberat = $data['min_weight'];
				  endif; 	 
				  
				  $totalongkir = (($data['rates_price']*$totalberat)+$data['adm_price']);
				  return $totalongkir;
				  
			endif;
	  else:
	  
				  $que = $db->query("SELECT `id` FROM `kurir_list` WHERE `kurir_name` = '$kurir' ");	
				  $row = $que->fetch_assoc();
				  $idkurir = $row['id'];
				  
				  $quepp = $db->query("SELECT * FROM `tarif_pengiriman` WHERE `kurir_name_id` = '$idkurir' and `nama_kota_id` = '$idcitymember' ");	
				  $data = $quepp->fetch_assoc();	
				  		  
				  if($JumlahBeratbrgProd > $data['min_weight']):
				  	 $totalberat = $JumlahBeratbrgProd;
				  else:
				  	  $totalberat = $data['min_weight'];
				  endif; 	 
				  
				  $totalongkir = (($data['rates_price']*$totalberat)+$data['adm_price']);
				  return $totalongkir;
				  	  
	  
	  endif;
	}
	
	function getmemberaddress($idmember){
		global $db;
	
		$output = ''; 
		$queery = $db->query("SELECT * FROM `member` WHERE `id`='$idmember' ");
		$data = $queery->fetch_assoc();
				
		$que = $db->query("SELECT * FROM `billing_address` WHERE `idmember`='$idmember' ");
		$row = $que->fetch_assoc();
		
        if($data['mobile_phone']<>''): $nope = '('.$data['mobile_phone'].')'; else: $nope = ''; endif;	
		$output.='<p><strong>'.ucwords($data['name']).' '.ucwords($data['lastname']).'</strong> '.$nope.'<br />';
        $output.=''.$row['address'].'<br />';
		$output.=''.getnamakota($row['idcity']).' - '.ucwords($row['kabupaten']).' <br /> '.ucwords($row['provinsi']).' - '.$row['kodepos'].' <a href="'.$GLOBALS['SITE_URL'].'my-account/finishorder" title="">(edit)</a>';
        $output.='</p>';
		echo $output;	
	}

	function getmemberaddress_corporate($idmember){
		global $db;
	
		$output = ''; 
		$queery = $db->query("SELECT * FROM `member` WHERE `id`='$idmember' ");
		$data = $queery->fetch_assoc();
				
		$que = $db->query("SELECT * FROM `billing_address` WHERE `idmember`='$idmember' ");
		$row = $que->fetch_assoc();
		
        if($data['mobile_phone']<>''): $nope = '('.$data['mobile_phone'].')'; else: $nope = ''; endif;	
		$output.='<p><strong>'.ucwords($data['name']).' '.ucwords($data['lastname']).'</strong> '.$nope.'<br />';
        $output.=''.$row['address'].'<br />';
		$output.=''.getnamakota($row['idcity']).' - '.ucwords($row['kabupaten']).' <br /> '.ucwords($row['provinsi']).' - '.$row['kodepos'].' <a href="'.$GLOBALS['SITE_URL'].'my-account-corporate/finishorder" title="">(edit)</a>';
        $output.='</p>';
		echo $output;	
	}
	
	function getmemberaddressredeem($idmember){
		global $db;
	
		$output = ''; 
		$queery = $db->query("SELECT * FROM `member` WHERE `id`='$idmember' ");
		$data = $queery->fetch_assoc();
				
		$que = $db->query("SELECT * FROM `billing_address` WHERE `idmember`='$idmember' ");
		$row = $que->fetch_assoc();
		
        if($data['mobile_phone']<>''): $nope = '('.$data['mobile_phone'].')'; else: $nope = ''; endif;	
		$output.='<p><strong>'.ucwords($data['name']).' '.ucwords($data['lastname']).'</strong> '.$nope.'<br />';
        $output.=''.$row['address'].'<br />';
		$output.=''.getnamakota($row['idcity']).' - '.ucwords($row['kabupaten']).' <br /> '.ucwords($row['provinsi']).' - '.$row['kodepos'].'';
        $output.='</p>';
		echo $output;	
	}
	
	function getaddressbookdetail($idmember,$idabbook){
		global $db;
		
		$output = ''; $pagexx = 0;
		$que = $db->query("SELECT * FROM `address_book` WHERE `member_id`='$idmember' ORDER BY `id` ASC");
		while($row = $que->fetch_assoc()):
				if($row['mobile_phone']<>''): $nope = '('.$row['mobile_phone'].')'; else: $nope = ''; endif;	
				if($idabbook==$row['id']): $rtext = 'checked="checked"'; else: $rtext =''; endif;
				
				$output.='<div class="aacc">
                            <input type="radio" id="alp2b-'.$row['id'].'" name="alp" class="rme-radio addressmember-listabook" '.$rtext.' />
                				<label for="alp2b-'.$row['id'].'" class="rme-label">
                               	<p><strong>'.ucwords($row['name']).' '.ucwords($row['lastname']).'</strong> '.$nope.'<br />
                                  	'.$row['address'].'<br />'.getnamakota($row['idcity']).'  - '.ucwords($row['kabupaten']).' <br /> '.ucwords($row['provinsi']).' - '.$row['kodepos'].' 
									<a href="'.$GLOBALS['SITE_URL'].'edit-address-checkout/'.$row['id'].'"  class="nuke-fancied2">(edit)</a>
                                </p>
                             </label>
                        </div><!-- .aacc -->';
						
			$pagexx++;	
		endwhile;
		echo $output;
		
		if($pagexx==0): echo'<p<span class="notfound2">Maaf, Anda tidak memiliki alamat tersimpan.</span><br /><br /></p>'; endif;			
	}		

	function getaddressbookdetail_corporate($idmember,$idabbook){
		global $db;
		
		$output = ''; $pagexx = 0;
		$que = $db->query("SELECT * FROM `address_book` WHERE `member_id`='$idmember' ORDER BY `id` ASC");
		while($row = $que->fetch_assoc()):
				if($row['mobile_phone']<>''): $nope = '('.$row['mobile_phone'].')'; else: $nope = ''; endif;	
				if($idabbook==$row['id']): $rtext = 'checked="checked"'; else: $rtext =''; endif;
				
				$output.='<div class="aacc">
                            <input type="radio" id="alp2b-'.$row['id'].'" name="alp" class="rme-radio addressmember-listabook" '.$rtext.' />
                				<label for="alp2b-'.$row['id'].'" class="rme-label">
                               	<p><strong>'.ucwords($row['name']).' '.ucwords($row['lastname']).'</strong> '.$nope.'<br />
                                  	'.$row['address'].'<br />'.getnamakota($row['idcity']).'  - '.ucwords($row['kabupaten']).' <br /> '.ucwords($row['provinsi']).' - '.$row['kodepos'].' 
									<a href="'.$GLOBALS['SITE_URL'].'edit-address-checkout-corporate/'.$row['id'].'"  class="nuke-fancied2">(edit)</a>
                                </p>
                             </label>
                        </div><!-- .aacc -->';
						
			$pagexx++;	
		endwhile;
		echo $output;
		
		if($pagexx==0): echo'<p<span class="notfound2">Maaf, Anda tidak memiliki alamat tersimpan.</span><br /><br /></p>'; endif;			
	}
	
	function getaddressbookdetailredeem($idmember,$idabbook){
		global $db;
		
		$output = ''; $pagexx = 0;
		$que = $db->query("SELECT * FROM `address_book` WHERE `member_id`='$idmember' ORDER BY `id` ASC");
		while($row = $que->fetch_assoc()):
				if($row['mobile_phone']<>''): $nope = '('.$row['mobile_phone'].')'; else: $nope = ''; endif;	
				if($idabbook==$row['id']): $rtext = 'checked="checked"'; else: $rtext =''; endif;
				
				$output.='<div class="aacc">
                            <input type="radio" id="alp2b-'.$row['id'].'" name="alp" class="rme-radio addressmember-listabook" '.$rtext.' />
                				<label for="alp2b-'.$row['id'].'" class="rme-label">
                               	<p><strong>'.ucwords($row['name']).' '.ucwords($row['lastname']).'</strong> '.$nope.'<br />
                                  	'.$row['address'].'<br />'.getnamakota($row['idcity']).'  - '.ucwords($row['kabupaten']).' <br /> '.ucwords($row['provinsi']).' - '.$row['kodepos'].'
                                </p>
                             </label>
                        </div><!-- .aacc -->';
						
			$pagexx++;	
		endwhile;
		echo $output;
		
		if($pagexx==0): echo'<p<span class="notfound2">Maaf, Anda tidak memiliki alamat tersimpan.</span><br /><br /></p>'; endif;			
	}	
		
	function bannklist(){
		global $db;
		$output = ''; 
		$que = $db->query("SELECT * FROM `bank_account` ORDER BY `id` ASC");
		while($row = $que->fetch_assoc()):
           $output.='<div class="pacc">';
              $output.='<input type="radio" id="rme'.$row['id'].'" name="banktransfer" class="rme-radio banktransfer" value="'.$row['id'].'" />';
              	$output.='<label for="rme'.$row['id'].'" class="rme-label">';
                  	$output.='<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['logo_image'].'" alt="" />';
                    $output.='<p class="bank_listcart">No. Rek. '.$row['account_number'].'';
					$output.='<br />a/n. '.ucwords($row['account_holder']).'</p>';
                    $output.='</label>';
         $output.='</div><!-- .pacc -->';
		endwhile;
		echo $output;		
	}

	function verifyFormToken($form) {
		if(!isset($_SESSION[$form.'_token'])) { 
			$statusform2 = 0;
			return $statusform2;
		}
		
		if(!isset($_POST['kodetokenid'])) {
			$statusform2 = 0;
			return $statusform2;
		}
		
		if ($_SESSION[$form.'_token']!== $_POST['kodetokenid']) {
			$statusform2 = 0;
			return $statusform2;
		}
		
		$statusform2 = 1;
		return $statusform2;
	}	

	function getalamatkirim($idmember){
		global $db;
	
		$output = ''; 
		$queery = $db->query("SELECT * FROM `member` WHERE `id`='$idmember' ");
		$data = $queery->fetch_assoc();
				
		$que = $db->query("SELECT * FROM `billing_address` WHERE `idmember`='$idmember' ");
		$row = $que->fetch_assoc();
		
        if($data['mobile_phone']<>''): $nope = '('.$data['mobile_phone'].')'; else: $nope = ''; endif;	
		$output.='<p><strong>'.ucwords($data['name']).' '.ucwords($data['lastname']).'</strong> '.$nope.'<br />';
        $output.=''.$row['address'].'<br />';
		$output.=''.getnamakota($row['idcity']).' - '.ucwords($row['kabupaten']).' <br /> '.ucwords($row['provinsi']).' - '.$row['kodepos'];
        $output.='</p>';
		return $output;		
	}	

	function getalamatkirimbook($idabook,$idmember){
		global $db;
		$textlabel = ''; 
		$que = $db->query("SELECT * FROM `address_book` WHERE `id`='$idabook' and `member_id`='$idmember'");
		$row = $que->fetch_assoc();

           if($row['mobile_phone']<>''): $nope = '('.$row['mobile_phone'].')'; else: $nope = ''; endif;	
		   $textlabel.='<p><strong>'.ucwords($row['name']).' '.ucwords($row['lastname']).'</strong> '.$nope.'<br />';
                 $textlabel.=''.$row['address'].'<br />';
				 $textlabel.=''.getnamakota($row['idcity']).' - '.ucwords($row['kabupaten']).'';
				 $textlabel.='<br />'.ucwords($row['provinsi']).' - '.$row['kodepos'].'';
           $textlabel.='</p>';
		   
		return $textlabel;				
	}		

	function getorderidmember($token){
		global $db;
		$que = $db->query("SELECT `id` FROM `order_header` WHERE `tokenpay`='$token'");
		$data = $que->fetch_assoc();	
	    if($data['id']>0): echo'Order ID: <strong>#'.sprintf('%06d',$data['id']).'</strong><br />'; endif;
	}

	function getorderidmemberredeem($token){
		global $db;
		$que = $db->query("SELECT `id` FROM `order_header_redeemlist` WHERE `tokenpay`='$token'");
		$data = $que->fetch_assoc();	
	    if($data['id']>0): echo'Redeem ID: <strong>#'.sprintf('%06d',$data['id']).'</strong><br />'; endif;
	}
	
	function getbankaccountjcart($id){
		global $db;
		$query = $db->query("SELECT * FROM `bank_account` WHERE `id`='$id'");
		$data = $query->fetch_assoc();	
		echo'Bank Name: <strong>'.$data['bank_name'].'</strong><br />';
		echo'Account Holder: <strong>'.ucwords($data['account_holder']).'</strong><br />';
		echo'Account No: <strong>'.$data['account_number'].'</strong>';		
	}	

	function getbankaccount($id){
		global $db;
		$query = $db->query("SELECT * FROM `bank_account` WHERE `id`='$id'");
		$data = $query->fetch_assoc();	
		echo'<strong>'.$data['bank_name'].' No. Rek. '.$data['account_number'].'</strong><br>a/n '.$data['account_holder'].'';				
	}	

	function getkodeunikmemberList(){
		global $db;
		$datenow = date("Y-m-d");
		$quep = $db->query("SELECT MAX(`kode_unik`) as kodeuniklast FROM `order_header` WHERE DATE_FORMAT(`date`,'%Y-%m-%d') = '$datenow' ");
		$res = $quep->fetch_assoc();	
		$numberunik = $res['kodeuniklast']+1;
		return $numberunik;
	}

	function bannklistForm(){
		global $db;
		$output = ''; 
		$que = $db->query("SELECT * FROM `bank_account` ORDER BY `id` ASC");
		while($row = $que->fetch_assoc()):
           $output.='<option value="'.$row['bank_name'].' - '.$row['account_number'].' a/n '.$row['account_holder'].'">'.$row['bank_name'].' - '.$row['account_number'].' a/n '.$row['account_holder'].'</option>';
		endwhile;
		echo $output;		
	}	
	
	function athlete_list_detail($id,$status){
		global $db;
		$que = $db->query("SELECT * FROM `athlete_list_detail` WHERE `athlete_id` = '$id' and `category` = '$status' ORDER BY `sortnumber` ASC");
		$jumpage = $que->num_rows;
		if($jumpage>0):
			while($row = $que->fetch_assoc()):
            	echo'<tr>';
                   echo'<td>'.$row['title'].'</td>';
                   echo'<td class="td">:</td>';
                   echo'<td>'.$row['detail'].'</td>';
                echo'</tr>';
			endwhile;		
		else:
             echo'<tr>';
                echo'<td><span class="notfound2">Not Available.</span></td>';
             echo'</tr>';		
		endif;
	}

	function athlete_list_detail2($id,$status){
		global $db;
		$que = $db->query("SELECT * FROM `athlete_list_detail` WHERE `athlete_id` = '$id' and `category` = '$status' ORDER BY `sortnumber` ASC");
		$jumpage = $que->num_rows;
		if($jumpage>0):
			while($row = $que->fetch_assoc()):
            	echo'<li>'.$row['title'].'</li>';
			endwhile;		
		else:
             echo'<span class="notfound2">Not Available.</span>';
		endif;
	}

	function store_location($idprovince,$idcity){
		global $db;
		if($idprovince>0 and $idcity>0):
			$que = $db->query("SELECT * FROM `store_location` WHERE `id_province` = '$idprovince' and `city_store` = '$idcity' and `publish` = 1  ORDER BY `sortnumber` ASC");
		elseif($idprovince>0):
			$que = $db->query("SELECT * FROM `store_location` WHERE `id_province` = '$idprovince'and `publish` = 1 ORDER BY `sortnumber` ASC");
		else:
			$que = $db->query("SELECT * FROM `store_location` WHERE `publish` = 1 ORDER BY `sortnumber` ASC");
		endif;
		$jumpage = $que->num_rows;
		if($jumpage>0):
			while($row = $que->fetch_assoc()):
            		echo'<div class="slc">';
                    	echo'<div class="slc-content">';
                        	echo'<h2>'.$row['store_name'].'</h2>';
                            echo $row['store_address'];
                        echo'</div><!-- .slc-content -->';
                   echo'</div><!-- .slc -->';
			endwhile;		
		else:
             echo'<span class="notfound">Not Available.</span>';
		endif;
	}	

	function provincelist($idprovince){
		global $db;
		$que = $db->query("SELECT * FROM `store_province` WHERE `publish` = 1 ORDER BY `province` ASC");
		$jumpage = $que->num_rows;
		if($jumpage>0):
			while($row = $que->fetch_assoc()):
				if($idprovince == $row['id']):
					echo'<option value="'.$row['id'].'" selected="selected">'.$row['province'].'</option>';
				else:		
	            	echo'<option value="'.$row['id'].'">'.$row['province'].'</option>';
				endif;		
			endwhile;		
		endif;
	}				

	function citylist($idcity,$idprovince){
		global $db;
		$que = $db->query("SELECT * FROM `store_city` WHERE `id_province_city` = '$idprovince' and `publish` = 1 ORDER BY `city_name` ASC");
		$jumpage = $que->num_rows;
		if($jumpage>0):
			while($row = $que->fetch_assoc()):
				if($idcity == $row['id']):
					echo'<option value="'.$row['id'].'" selected="selected">'.$row['city_name'].'</option>';
				else:		
	            	echo'<option value="'.$row['id'].'">'.$row['city_name'].'</option>';
				endif;		
			endwhile;		
		endif;
	}

	function whyfixcomart(){
		global $db;
		$output = "";
		$query = $db->query("SELECT `header`,`description`,`logo` FROM product_detail_information_config WHERE publish = 1 ORDER BY `id` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				$output .= '<div class="child">';
                    $output .= '<span class="'.$row['logo'].'"></span>';
                        $output .= '<span class="text">'.$row['header'];
                            if($row['description'] != ""):
                            	$output .= '<span class="small">'.$row['description'].'</span>';
                        	endif;
                        $output .= '</span><!-- .text -->';
                $output .= '</div><!-- .child -->';
			endwhile;
		else:
			$output .= '<div class="item">Record not found.</div>';
		endif;

		return $output;
	}

	function getblogbreadcrumb(){
		global $db;
		$query = $db->query("SELECT `category_name` FROM `blog_category` WHERE `publish` = 1 ORDER BY `sortnumber` ASC LIMIT 1") or die($db->error);
		$jumpage = $query->num_rows;

		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				echo $row['category_name'];
			endwhile;
		endif;
	}

	function bloglist($category,$page){
		global $db;
		$id_selected_cat = "";
		$category_name = "";
		if($category == NULL){
			$query_cat = $db->query("SELECT `id`,`category_name` FROM `blog_category` WHERE `publish` = 1 ORDER BY `sortnumber` ASC LIMIT 1") or die($db->error);
		}else{
			$query_cat = $db->query("SELECT `id`,`category_name` FROM `blog_category` WHERE `publish` = 1 AND `category_name` LIKE '%$category%'") or die($db->error);
		}

		$query_all_cat = $db->query("SELECT `id`,`category_name` FROM `blog_category` WHERE `publish` = 1 ORDER BY `sortnumber` ASC") or die($db->error);
		
		$jumpage_cat = $query_cat->num_rows;
		$jumpage_all_cat = $query_cat->num_rows;

		$content_per_page = 8;
		$total_page = ceil($jumpage_all_cat/$content_per_page);
		$last_page = $total_page;
		$current_page = $page;
		$offset_page = $content_per_page * ($current_page-1);

		if($jumpage_cat > 0):
			while($row_cat = $query_cat->fetch_assoc()):
				$id_selected_cat = $row_cat['id'];
				$category_name = $row_cat['category_name'];
				echo '<div class="tabbing-link-style">';
				if($jumpage_all_cat > 0):
					while($row_all_cat = $query_all_cat->fetch_assoc()):
						if($row_all_cat['category_name'] == $row_cat['category_name']){
							echo '<div class="item active">
				                    <h1>'.$row_all_cat['category_name'].'</h1>
				                  </div><!-- .item -->';
			              }else{
			              	echo '<div class="item">
				                    <a href="'.$GLOBALS['SITE_URL'].'blog/'.$row_all_cat['category_name'].'">'.$row_all_cat['category_name'].'</a>
				                  </div><!-- .item -->';
			              }
						
					endwhile;
				endif;
				echo '</div><!-- .tabbing-link-style -->';
			endwhile;
		endif;

		echo '<div class="blog-list">
            	<div class="row same-height medium-gutter">';

		$query = $db->query("SELECT `header`,`description`,`post_date`,`image`,`blog_url` FROM `blog_detail` WHERE `publish` = 1 AND id_blog_category = $id_selected_cat ORDER BY `post_date` DESC LIMIT $content_per_page OFFSET $offset_page") or die($db->error);
		$jumpage = $query->num_rows;

		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				$temp_desc = explode(".",$row['description']);
				echo '<div class="grid-child n-768-1per2">
                        <div class="blog-child">
                            <div class="ngc-media">
                                <a href="'.$GLOBALS['SITE_URL'].'blog-detail/'.$category_name.'/'.$row['blog_url'].'">
                                    <img src="'.$GLOBALS['UPLOAD_FOLDER'].$row['image'].'" alt="'.$row['header'].'" class="lazyload" data-expand="-10">
                                    <span class="blog-date">
                                        <span class="large">'.date("d",strtotime($row['post_date'])).'</span>
                                        '.date("M",strtotime($row['post_date'])).'
                                    </span>
                                </a>
                            </div><!-- .ngc-media -->
                            <div class="ngc-text">
                                <h3 class="ngc-title">
                                    <a href="'.$GLOBALS['SITE_URL'].'blog-detail/'.$category_name.'/'.$row['blog_url'].'">
                                        '.$row['header'].'
                                    </a>
                                </h3>';
                        echo '<p>';
                        for ($i=0; $i < 3; $i++) { 
                        	echo $temp_desc[$i] . ". ";
                        }
                        echo '</p>';
                        echo   '<a href="'.$GLOBALS['SITE_URL'].'blog-detail/'.$category_name.'/'.$row['blog_url'].'" class="read-more">Read More <span class="fa fa-angle-right"></span></a>
                            </div><!-- .ngc-text -->
                        </div><!-- .blog-child -->
                    </div><!-- .grid-child -->';
			endwhile;
		endif;

		echo '</div><!-- .row -->
                <div class="product-list-bottom">
                    <div class="nuke-pagination">';

            if(($current_page >= 3 && $current_page <= $total_page-2) && $total_page >= 5){
            	$start = $current_page - 2;
        		$end = $current_page +2;
            }else if($total_page < 5){
            	$start = 1;
            	$end = $total_page;
            }else if($current_page < 3){
            	$start = 1;
            	$end = 5;
            }else if($current_page > $total_page-2){
            	$start = $total_page - 4;
            	$end = $total_page;
            }else{
            	$start = $current_page;
            	$end = 5;
            }

            for ($i=$start; $i <= $end; $i++) { 
            	if($i == 1){
            		if($current_page == $i){
            			echo '<span class="np-first np-bigger">&laquo;</span>
            				  <span class="np-active">'.$i.'</span>';
            		}else{
            			echo '<a href="'.$GLOBALS['SITE_URL'].'blog/'.$category_name.'/'.($current_page-1).'" class="np-first np-bigger">&laquo;</a>
			              	  <a href="'.$GLOBALS['SITE_URL'].'blog/'.$category_name.'/'.$i.'">'.$i.'</a>';
            		}

            		if($total_page == 1){
            			echo '<span class="np-last np-bigger">&raquo;</span>';
            		}
	            }else if($i == $last_page){
	            	if($current_page == $last_page){
	            		echo '<span class="np-active">'.$i.'</span>
	            			  <span class="np-last np-bigger">&raquo;</span>';
	            	}else{
	            		echo '<a href="'.$GLOBALS['SITE_URL'].'blog/'.$category_name.'/'.$i.'">'.$i.'</a>
	            			  <a href="'.$GLOBALS['SITE_URL'].'blog/'.$category_name.'/'.($current_page+1).'" class="np-last np-bigger">&raquo;</a>';
	            	}
	            }else{
	            	if($current_page == $i){
	            		echo '<span class="np-active">'.$i.'</span>';
	            	}else if($i == $end){
	            		echo '<a href="'.$GLOBALS['SITE_URL'].'blog/'.$category_name.'/'.$i.'">'.$i.'</a>
	            			  <a href="'.$GLOBALS['SITE_URL'].'blog/'.$category_name.'/'.($current_page+1).'" class="np-last np-bigger">&raquo;</a>';
	            	}else if($i == $start){
	            		echo '<a href="'.$GLOBALS['SITE_URL'].'blog/'.$category_name.'/'.($current_page-1).'" class="np-first np-bigger">&laquo;</a>
	            			  <a href="'.$GLOBALS['SITE_URL'].'blog/'.$category_name.'/'.$i.'">'.$i.'</a>';
	            	}else{
	            		echo '<a href="'.$GLOBALS['SITE_URL'].'blog/'.$category_name.'/'.$i.'">'.$i.'</a>';
	            	}
	            }
            }

        echo        '</div><!-- .nuke-pagination -->
                </div><!-- .product-list-bottom -->
            </div><!-- .blog-list -->';
	}

	function getblogbanner($category){
		global $db;
		if($category == NULL){
			$query = $db->query("SELECT `category_name`,`image` FROM `blog_category` WHERE `publish` = 1 ORDER BY `sortnumber` ASC LIMIT 1") or die($db->error);
		}else{
			$query = $db->query("SELECT `category_name`,`image` FROM `blog_category` WHERE `publish` = 1 AND `category_name` LIKE '%$category%'") or die($db->error);
		}

		$jumpage = $query->num_rows;

		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				echo '<img src="'.$GLOBALS['UPLOAD_FOLDER'].$row['image'].'" alt="'.$row['category_name'].'">';	
			endwhile;
		endif;
	}

	function blogdetail($category,$url){
		global $db;
		$query = $db->query("SELECT `header`,`description`,`image_header`,`post_date`,`youtube_link` FROM `blog_detail` WHERE `publish` = 1 AND `blog_url` = '$url'") or die($db->error);
		$jumpage = $query->num_rows;

		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				echo '<div class="max-970 blog-detail">
		                <div class="ngc-media">
		                    <img src="'.$GLOBALS['UPLOAD_FOLDER'].$row['image_header'].'" alt="'.$row['header'].'">
		                </div><!-- .ngc-media -->
		                <h1 class="ngc-maintitle">'.$row['header'].'</h1>
		                <div class="blog-meta">
		                    <div class="item">
		                        <span class="fa fa-calendar icon"></span><span class="text">'.date("d M Y",strtotime($row['post_date'])).'</span>
		                    </div><!-- .item -->
		                    <div class="item">
		                        <span class="fa fa-tag icon"></span><span class="text">'.$category.'</span>
		                    </div><!-- .item -->
		                    <div class="item">
		                        <div id="share"></div>
		                    </div><!-- .item -->
		                </div><!-- .blog-meta -->
		                <div class="nuke-wysiwyg">
		                    '.$row['description'].'
		                <br />';
		                if($row['youtube_link'] != ""):
		                echo '<div class="video-container">
		                        <iframe width="560" height="315" src="'.$row['youtube_link'].'" frameborder="0" allowfullscreen></iframe>
		                    </div><!-- .video-container -->';
		                endif;
		            echo '</div><!-- .news-detail -->
		            </div><!-- .max-970 -->';
			endwhile;
		endif;
	}

	function relatedblog($category,$url){
		global $db;
		$id_blog_cat;
		$description = Array();
		$query_cat = $db->query("SELECT `id` FROM `blog_category` WHERE `publish` = 1 AND `category_name` = '$category'") or die($db->error);
		$jumpage_cat = $query_cat->num_rows;

		if($jumpage_cat>0):
			while($row = $query_cat->fetch_assoc()):
				$id_blog_cat = $row['id'];
			endwhile;
		endif;

		$query = $db->query("SELECT `header`,`description`,`image`,`post_date`,`blog_url` FROM `blog_detail` WHERE `publish` = 1 AND `blog_url` != '$url' AND `id_blog_category` = $id_blog_cat") or die($db->error);
		$jumpage = $query->num_rows;

		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				$description = explode(".", $row['description']);
		        echo '<div class="item">
                            <div class="blog-child">
                                <div class="ngc-media">
                                    <a href="'.$GLOBALS['SITE_URL'].'/blog-detail/'.$category.'/'.$row['blog_url'].'">
                                        <img src="'.$GLOBALS['UPLOAD_FOLDER'].'/'.$row['image'].'" alt="BLOG TITLE HERE" class="lazyload" data-expand="-10">
                                        <span class="blog-date">
                                            <span class="large">'.date("d",strtotime($row['post_date'])).'</span>
                                            '.date("M",strtotime($row['post_date'])).'
                                        </span>
                                    </a>
                                </div><!-- .ngc-media -->
                                <div class="ngc-text">
                                    <h3 class="ngc-title">
                                        <a href="'.$GLOBALS['SITE_URL'].'/blog-detail/'.$category.'/'.$row['blog_url'].'">
                                            '.$row['header'].'
                                        </a>
                                    </h3>
                                    <p>';
                                    for ($i=0; $i < 3 ; $i++) { 
                                    	echo $description[$i];
                                    }
                            echo 	'</p>
                                    <a href="'.$GLOBALS['SITE_URL'].'/blog-detail/'.$category.'/'.$row['blog_url'].'" class="read-more">Read More <span class="fa fa-angle-right"></span></a>
                                </div><!-- .ngc-text -->
                            </div><!-- .blog-child -->
                        </div><!-- .item -->';
			endwhile;
		endif;
	}

	function topads(){
		global $db;
		$query = $db->query("SELECT `name`,`link`,`image_ads` FROM `ads_top` WHERE `publish` = 1 ORDER BY `sortnumber`") or die($db->error);
		$jumpage = $query->num_rows;

		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				echo '<div class="grid-child n-1-1per1 n-1-1per2 n-768-1per4 n-768-no-margin-bottom">
                        <div class="ads-banner">
                            <div class="ngc-media">
                                <a href="'.$row['link'].'"><img src="'.$GLOBALS['UPLOAD_FOLDER'].$row['image_ads'].'" alt="'.$row['name'].'" class="lazyload" data-expand="-10"></a>
                            </div><!-- .ngc-media -->
                        </div><!-- .ads-banner -->
                    </div><!-- .grid-child -->';
			endwhile;
		endif;
	}

	function bottomads(){
		global $db;
		$column_size = Array();
		$id_row = Array();
		$query_row = $db->query("SELECT `id`,`column_size` FROM `ads_row` WHERE `publish` = 1 ORDER BY `sortnumber`") or die($db->error);
		$jumpage_row = $query_row->num_rows;

		if($jumpage_row>0):
			while($row = $query_row->fetch_assoc()):
				$column_size[$row['id']] = $row['column_size'];
				array_push($id_row,$row['id']);
			endwhile;
		endif;

		for ($i=0; $i < sizeof($id_row); $i++) { 
			$query_col = $db->query("SELECT `id_row`,`name`,`link`,`image_ads` FROM `ads_column` WHERE `publish` = 1 AND `id_row` = '$id_row[$i]' ORDER BY `sortnumber`") or die($db->error);
			$jumpage_col = $query_col->num_rows;
			echo '<div class="row small-gutter">';
			if($jumpage_col>0):
				while($row = $query_col->fetch_assoc()):
					echo '<div class="grid-child n-1-1per1 n-540-1per'.$column_size[$row['id_row']].'">
	                        <div class="ads-banner">
	                            <div class="ngc-media">
	                                <a href="'.$row['link'].'"><img src="'.$GLOBALS['UPLOAD_FOLDER'].$row['image_ads'].'" alt="'.$row['name'].'" class="lazyload" data-expand="-10"></a>
	                            </div><!-- .ngc-media -->
	                        </div><!-- .ads-banner -->
	                    </div><!-- .grid-child -->';
				endwhile;
			endif;
			echo '</div><!-- .row -->';
		}
	}

	function satuanquotation(){
		global $db;
		$query = $db->query("SELECT `id`,`satuan` FROM `satuan_quotation` WHERE `publish` = 1 ORDER BY `sortnumber`") or die($db->error);
		$jumpage = $query->num_rows;

		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				echo '<option value="'.$row['id'].'">'.$row['satuan'].'</option>';
			endwhile;
		endif;
	}

	function setsatuanquotation($satuan){
		global $db;
		$query = $db->query("SELECT `id`,`satuan` FROM `satuan_quotation` WHERE `publish` = 1 ORDER BY `sortnumber`") or die($db->error);
		$jumpage = $query->num_rows;

		if($jumpage>0):
			while($row = $query->fetch_assoc()):
				if($row['id'] == $satuan){
					echo '<option value="'.$row['id'].'" selected="selected">'.$row['satuan'].'</option>';
				}else{
					echo '<option value="'.$row['id'].'">'.$row['satuan'].'</option>';
				}
			endwhile;
		endif;
	}

	function getflashsaleproduct(){
		global $db;
		$id_header="";
		$id_product = Array();
		$query_header = $db->query("SELECT `id` FROM `flash_sale_schedule` WHERE CURRENT_TIMESTAMP BETWEEN `start_date` AND `end_date`");
		$jumpage_header = $query_header->num_rows;

		if($jumpage_header>0):
			while($row = $query_header->fetch_assoc()):
				$id_header = $row['id'];
			endwhile;
		endif;

		$query_product = $db->query("SELECT `id_product` FROM `flash_sale_product` WHERE `id_flash_sale_schedule` = $id_header");
		$jumpage_product = $query_product->num_rows;

		if($jumpage_product>0):
			while($row = $query_product->fetch_assoc()):
				array_push($id_product, $row['id_product']);
			endwhile;
		endif;

		for ($i=0; $i < sizeof($id_product); $i++) { 
			$query = $db->query("SELECT * FROM `product` WHERE `publish` = 1 and `id` = '$id_product[$i]' ") or die($db->error);
			$jumpage = $query->num_rows;
			if($jumpage>0):
				$row = $query->fetch_assoc();
				echo '<div class="item">';
					echo '<div class="ctab-content smaller">';
		                  echo '<div class="ctab-badge-wrap">';
		                  	if($row['discount_value']>0):
		                  		echo '<span class="ctab-badge f-psb">'.$row['discount_value'].'% OFF</span>';
		                  	endif;
		                      echo '</div><!-- .ctab-badge-wrap -->';
		                                  echo '<div class="img-wrap"><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($row['name']).'/'.$row['id'].'">';
										  			echo '<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['name'].'" class="lazyload" data-expand="-10" /></a></div>';
		                                  echo'<div class="ctab-content-prod">';
										  
		                                  echo '<span class="prod-brand f-yellow f-psb">'.generalselect("category","name"," `id` = '".$row['idkat']."' ").'</span>';
		                                  echo '<h3 class="f-psb"><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($row['name']).'/'.$row['id'].'" title="'.$row['name'].'">'.substr($row['name'],0,50).'</a></h3>';
		                                  echo '<div class="prod-price-wrap">';
		                              
									                   	if($row['discount_value']>0):
										
															$diskonval1 = ($row['price']*$row['discount_value'])/100;
															$diskonval2 = round($diskonval1);
															$diskonval3 = $row['price']-$diskonval1;
										
															echo '<span class="old-price">Rp '.number_format($row['price']).',-</span>';
		               										echo '<span class="prod-price f-pb">Rp '.number_format($diskonval3).',-</span>';	
														else:
															echo '<span class="prod-price f-pb">Rp '.number_format($row['price']).',-</span>';	
														endif;
														
		                                  echo'</div><!-- .prod-price-wrap -->';
		                            echo'</div><!-- .ctab-content-prod -->';

		                    	echo'<div class="btn-wrap">';
							echo getbuttonbeliprod($row['id']);
						echo'</div><!-- .btn-wrap -->';

		            echo'</div><!-- .ctab-content -->';
		        echo '</div><!-- .item -->';
			endif; 	
		}
	}

	function flashsaleenddate(){
		global $db;
		$end_date = "";
		$query_header = $db->query("SELECT `end_date` FROM `flash_sale_schedule` WHERE CURRENT_TIMESTAMP BETWEEN `start_date` AND `end_date`");
		$jumpage_header = $query_header->num_rows;

		if($jumpage_header>0):
			while($row = $query_header->fetch_assoc()):
				$end_date = $row['end_date'];
			endwhile;
		endif;

		return $end_date;
	}

	
?>