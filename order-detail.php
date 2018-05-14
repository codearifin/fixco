<?php include("header.php"); 
	if(isset($_SESSION['user_token'])==''):
		$_SESSION['error_msg']='login-first';
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;

	if(isset($_GET['code'])): $code = $_GET['code']; else: $code = ''; endif;
	$itemid = explode("-",$code);
	$idorder = $itemid[0];
	$tokenpay = $itemid[1];
	
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; endif;
	$query = $db->query("SELECT `id` FROM `member` WHERE `tokenmember`='$Usertokenid'");
	$res = $query->fetch_assoc();
	$idmember = $res['id'];
	
	$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d %M %Y, %H:%i:%s') as tgl FROM `order_header` WHERE `id`='$idorder' and `idmember`='$idmember' and `tokenpay`='$tokenpay'");
	$row = $que->fetch_assoc();
	$totalamount = (($row['orderamount']+$row['shippingcost']+$row['kode_unik']+$row['handling_fee'])-$row['discountamount']);	
	if($row['phone_penerima']<>''): $Phonelabel = '('.$row['phone_penerima'].')'; else: $Phonelabel = ''; endif;		
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Status Pesanan</li>
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
                         <?php include("side_member.php");?>
                    </ul><!-- .ts-menu -->
                </div><!-- .ts-child -->
            </aside><!-- #product-sidebar -->
            <div class="template-wrap">
                <h1 class="f-pb">Status Pesanan</h1>
                <a href="<?php echo $GLOBALS['SITE_URL'];?>order-history" class="f-1200-14px">&laquo; Kembali ke History Pembelian</a><br><br>
                <table cellspacing="0" cellpadding="0" class="blue-table">
                	<tbody>
                    	<tr>
                        	<td>
                            	<h2 class="f-pb" style="font-size:1.4em; line-height:1.3em;">Order ID: <?php echo '#'.sprintf('%06d',$row['id']).'';?></h2>
                                <p><?php echo $row['tgl'];?></p>
                                <p><strong>Kurir : <?php echo $row['kurir'];?></strong></p>
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
                            	<p>
									   <?php echo'<strong>'.$row['nama_penerima'].'</strong> '.$Phonelabel.'<br />';?>
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
                                $que33 = $db->query("SELECT * FROM `order_detail` WHERE `tokenpay`='".$row['tokenpay']."' ORDER BY `id` ASC");
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
								
								//bonus
                                $quebns = $db->query("SELECT * FROM `order_detail_bonus_product` WHERE `tokenpay`='".$row['tokenpay']."' ORDER BY `id` ASC");
                                $jumpagebb = $quebns->num_rows;
								while($resb = $quebns->fetch_assoc()):
                                                
												$imgnameList = getimagesdetailbonus($resb['idproduct']);
                                                
                                                   echo'<tr>
                                                        <td colspan="2">
                                                            <div class="ringkasan-wrap">
                                                                <div class="rw-1"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$imgnameList.'" /></div>
                                                                <div class="rw-2-wrap">
                                                                    <div class="rw-2 rntprodList">
                                                                        <h3>'.$resb['sku'].' - '.$resb['name'].'</a>
                                                                        </h3>
                                                                        <p class="f-1200-14px">Qty. 1 item<br>
                                                                         <span class="error_voucherlist">* free item product</span></p>
                                                                    </div><!-- .rw-2 -->
                                                                    <div class="rw-3 f-pb">
                                                                        Rp 0,-
                                                                    </div><!-- .rw-3 -->
                                                                </div><!-- .rw-2-wrap -->
                                                            </div><!-- .ringkasan-wrap -->
                                                        </td>
                                                    </tr>';			
                
                                endwhile;											
                            ?>  

                        	<tr>
                        		<td><strong style="letter-spacing:0.04em;">Sub Total</strong></td>
                                <td style="text-align:right;"><strong style="font-size:1.3em;">Rp. <?php echo number_format($row['orderamount']);?></strong></td>
                            </tr>

                        	<tr>
                        		<td><strong style="letter-spacing:0.04em;">Handling Fee</strong></td>
                                <td style="text-align:right;"><strong style="font-size:1.3em;">Rp. <?php echo number_format($row['handling_fee']);?></strong></td>
                            </tr>                            
                
                            <?php if($row['discountamount']>0 or $jumpagebb>0):?>
                        		
                                <?php if($row['discountamount']>0):?>
                                    <tr>
                                        <td><strong style="letter-spacing:0.04em; color:#ffa827;">Voucher (<?php echo $row['vouchercode'];?>)</strong></td>
                                        <td style="text-align:right;"><strong style="font-size:1.3em; color:#ffa827;">- Rp. <?php echo number_format($row['discountamount']);?></strong></td>
                                    </tr>
                                <?php else:?>
                               		 <tr>
                                        <td><strong style="letter-spacing:0.04em; color:#ffa827; font-size:14px;">Voucher (<?php echo $row['vouchercode'];?>)</strong></td>
                                        <td style="text-align:right;"><strong style="font-size:13px; color:#ffa827;">Get free item product!</td>
                                    </tr>   
                                <?php endif;?>
                            <?php endif;?>
                       
                          	<tr>
                        		<td><strong style="letter-spacing:0.04em;">Ongkos Kirim</strong></td>
                                <td style="text-align:right;"><strong style="font-size:1.3em;">Rp. <?php echo number_format($row['shippingcost']);?></strong></td>
                            </tr>
                                                       
							<tr>
                        		<td><strong style="letter-spacing:0.04em;">Grand Total</strong></td>
                                <td style="text-align:right;"><strong class="f-red" style="font-size:1.3em;">Rp. <?php echo number_format($totalamount);?></strong></td>
                            </tr>

                        
                        
                    </tbody>
                </table>
                <br><br>
                <table cellspacing="0" cellpadding="0" class="blue-table">
                	<thead>
                    	<tr>
                        	<th>Status Terakhir</th>
                        </tr>
                    </thead>
                	<tbody>
						<?php
                                $pagestatus = 0;
                                $quee3 = $db->query("SELECT DATE_FORMAT(`date`,'%d %M %Y, %H:%i:%s') as tgldd,`description` FROM `status_detailorder` WHERE `idorder`='".$row['id']."' ORDER BY `date` DESC ");
                                while($data3 = $quee3->fetch_assoc()):
									echo'<tr>
												<td>
													<strong style="font-size:1.125em">'.$data3['tgldd'].'</strong>
													<p>'.nl2br($data3['description']).'</p>
												</td>
											</tr>';
                                    $pagestatus++;
                                endwhile;
                                if($pagestatus==0): echo'<tr><td><span style="font-size:15px; color:#FF6600;">Record not found.</span></tr></td>'; endif;	
                        ?>
              
                    </tbody>
                </table>
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".menu5").addClass("active");	
	});	
</script>

</body>
</html>