<?php include("header.php"); 

	if(isset($_SESSION['user_token'])==''):
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;

	//member data
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
	$qummep = $db->query("SELECT `id`,`idmember_list`,`status` FROM `corporate_user` WHERE `id`='$Usertokenid'");
	$ros = $qummep->fetch_assoc();	
	$idmember = $ros['idmember_list'];
	$idusr = $ros['id'];
	
	if(isset($_GET['token'])): $token = replaceUrel($_GET['token']); else: $token = ''; endif;
	
	if($ros['status']==1):
		$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d %M %Y, %H:%i:%s') as tgl, DATE_FORMAT(`expiry_date`,'%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' and `tokenpay`='$token'");
	else:
		$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d %M %Y, %H:%i:%s') as tgl, DATE_FORMAT(`expiry_date`,'%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' and `id_user` = '$idusr' and `tokenpay`='$token'");
	endif;
	
	$row = $que->fetch_assoc();
	$totalamount = $row['total_order']+$row['shippingcost']+$row['handling_fee'];
	if($row['phone_penerima']<>''): $Phonelabel = '('.$row['phone_penerima'].')'; else: $Phonelabel = ''; endif;	

	//CEK DATE
	$waktu_sekarang	= date("Y-m-d");
						
	//date promo berakhir
	$pnt3=explode("-",$waktu_sekarang);	
	$tg_now=mktime(0,0,0,$pnt3[1],$pnt3[2],$pnt3[0]);
	
	if($row['expiry_date']!=""):																	
		$pnt2=explode("-",$row['expiry_date']); 
		$tg_akhir=mktime(0,0,0,$pnt2[1],$pnt2[2],$pnt2[0]); 
		$hitung_akhir = (($tg_akhir-$tg_now)/86400);	
	else:
		$hitung_akhir = -1;	
	endif;
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Draft Quotation</li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section">
	<div class="container">
    	<div class="row">
        	<aside id="template-sidebar" class="ts-ads-wrap">
                <div class="ts-child">
                	<h3 class="f-pb">AKUN SAYA</h3>
                    <ul class="ts-menu">
                        <?php include("side_member_corporate.php");?>
                    </ul><!-- .ts-menu -->
                </div><!-- .ts-child -->
            </aside><!-- #product-sidebar -->
            <div class="template-wrap">
                <h1 class="f-pb">Quotation for Order <?php echo '#DQ-'.sprintf('%06d',$row['id']).'';?></h1>
                <a href="<?php echo $GLOBALS['SITE_URL'];?>draft-quotation" class="f-1200-14px">&laquo; Kembali ke Draft Quotation</a><br><br>
                <table cellspacing="0" cellpadding="0" class="blue-table">
                	<tbody>
                    	<tr>
                        	<td>
                            	<h2 class="f-pb" style="font-size:1.4em; line-height:1.3em;"><?php echo getnamegeneral($row['id_user'],"corporate_user","name").' '.getnamegeneral($row['id_user'],"corporate_user","lastname");?></h2>
                                <p><?php echo $row['tgl'];?></p>
                                <p style="padding-top:10px;"><?php echo'<span class="bg-expiry2">Expiry : '.$row['expiry_datetgl'].'</span>';?></p>
                                <p><br /><strong>Kurir : <?php echo $row['kurir'];?></strong></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br><br>
                <table cellspacing="0" cellpadding="0" class="blue-table">
                	<thead>
                    	<tr>
                        	<th>Alamat Pengiriman</th>
                        </tr>
                    </thead>
                	<tbody>
                    	<tr>
                        	<td>
                            	<p><?php echo'<strong>'.$row['nama_penerima'].'</strong> '.$Phonelabel.'<br />';?>
                                        <?php
                                                echo $row['address_penerima'].'<br />';
                                                echo $row['kota_penerima'].', '. $row['kabupaten_penerima'].'<br />';
                                                echo $row['provinsi_penerima'].' - '.$row['country_penerima'].' '.$row['kodepos'];				
                                    ?> 
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br><br>
                <table cellspacing="0" cellpadding="0" class="blue-table">
                	<thead>
                    	<tr>
                        	<th colspan="2">Ringkasan Pembelanjaan</th>
                        </tr>
                    </thead>
                	<tbody>
                    	
                        	<?php
                                $totaloo = 0; $grtotal = 0; $satuanprod = '';
                                $que33 = $db->query("SELECT * FROM `draft_quotation_detail` WHERE `tokenpay`='".$row['tokenpay']."' ORDER BY `id` ASC");
                                while($data = $que33->fetch_assoc()):
                                                $imgname = getimagesdetail($data['idproduct']);
                                                $totaloo = $data['price'];
                                                $grtotal = $totaloo*$data['qty'];
                
                                                $itemwarnalist = explode("#",$data['nama_detail']);
                                                $warnaprod = $itemwarnalist[0];
                                        
                                                    echo'<tr>
                                                        <td colspan="2">
                                                            <div class="ringkasan-wrap">
                                                                <div class="rw-1"><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($data['name']).'/'.$data['idproduct'].'" target="_blank">
                                                                                  <img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$imgname.'" /></a></div>
                                                                <div class="rw-2-wrap">
                                                                    <div class="rw-2 rntprodList">
                                                                        <h3><a href="'.$GLOBALS['SITE_URL'].'product-detail/'.replace($data['name']).'/'.$data['idproduct'].'" target="_blank">'.$data['name'].'<br />
                                                                                '.$data['sku'].' - '.$warnaprod.'</a>
                                                                        </h3>
                                                                        <p class="f-1200-14px">Qty. '.$data['qty'].' item<br>
                                                                                @ Rp '.number_format($totaloo).'<br>
                                                                                Sub Total Rp '.number_format($grtotal).'</p>
                                                                    </div><!-- .rw-2 -->
                                                                    <div class="rw-3 f-pb">
                                                                        Rp '.number_format($grtotal).',-
                                                                    </div><!-- .rw-3 -->
                                                                </div><!-- .rw-2-wrap -->
                                                            </div><!-- .ringkasan-wrap -->
                                                        </td>
                                                    </tr>';			
                
                                endwhile;			
                            ?>  
                        <tr>
                        	<td><strong style="letter-spacing:0.04em;">SUB. TOTAL</strong></td>
                            <td style="text-align:right;"><strong style="font-size:1.3em;">Rp <?php echo number_format($row['total_order']);?></strong>
                        </tr>

                       <tr>
                      	     <td><strong style="letter-spacing:0.04em;">Handling Fee</strong></td>
                             <td style="text-align:right;"><strong style="font-size:1.3em;">Rp. <?php echo number_format($row['handling_fee']);?></strong></td>
                        </tr>   
                                                    
                        <tr>
                        	<td><strong style="letter-spacing:0.04em;">BIAYA PENGIRIMAN</strong></td>
                            <td style="text-align:right;"><strong style="font-size:1.3em;">Rp <?php echo number_format($row['shippingcost']);?></strong>
                        </tr>
                        
                        <tr>
                        	<td><strong style="letter-spacing:0.04em;">GRAND TOTAL</strong></td>
                            <td style="text-align:right;"><strong class="f-red" style="font-size:1.3em;">Rp <?php echo number_format($totalamount);?></strong>
                        </tr>
                    </tbody>
                </table>
                
                <br><br>
			 <?php
				if($row['status_order']==1): echo''; else:?>
                
                <a href="" onClick="javascript:window.open('<?php echo $GLOBALS['SITE_URL'].'print-quotation-list/'.$row['tokenpay'];?>', '', 'toolbar=,location=no,status=no,menubar=yes,scroll bars=yes, resizable=no,width=800,height=650'); return false" 
                class="btn btn-yellow f-psb no-margin btn-checkout">PRINT</a>
                
                <?php
					if($hitung_akhir>=0):
						echo'&nbsp;&nbsp;&nbsp;<a href="'.$GLOBALS['SITE_URL'].'edit-quotation-list/'.$row['tokenpay'].'" class="btn btn-yellow f-psb no-margin btn-checkout nuke-fancied2">EDIT</a>';
						echo'&nbsp;&nbsp;&nbsp;<a href="'.$GLOBALS['SITE_URL'].'checkout-quotation-list/'.$row['tokenpay'].'" class="btn btn-red f-psb no-margin btn-checkout">CHECK OUT</a>';
					else:
						echo'&nbsp;&nbsp;&nbsp;<span class="notfound">Expired Draft Quotation!</span>';	
					endif;
				endif;	
			?>					
                
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".menu99").addClass("active");	
	});	
</script>

</body>
</html>