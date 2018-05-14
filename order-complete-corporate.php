<?php include("header.php"); 

	 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else:  $Usertokenid = ''; endif;
	 if(isset($_GET['tokenpay'])): $code = replaceUrel($_GET['tokenpay']); else: $code = ''; endif;

	 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
	 $qummep = $db->query("SELECT `idmember_list` FROM `corporate_user` WHERE `id`='$Usertokenid'");
	 $ros = $qummep->fetch_assoc();	
	 $idmember = $ros['idmember_list'];
	
	 //select order
	 $que = $db->query("SELECT *,DATE_FORMAT(`date`,'%W, %d %M %Y') as tgl FROM `order_header` WHERE `idmember`='$idmember' and `tokenpay`='$code'");
	 $data = $que->fetch_assoc();
	 if($data['status_payment']=="Confirmed"):
	 	$totalorder = (($data['orderamount']+$data['shippingcost']+$data['kode_unik']+$data['handling_fee'])-$data['discountamount']);
	 else:
		 $totalorder = (($data['orderamount']+$data['shippingcost']+$data['kode_unik']+$data['handling_fee'])-$data['discountamount'])-$data['deposit_amount'];	
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
                <li>Shopping Cart</li>
                <li>Checkout</li>
                <li class="f-pb">Order Complete</li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section one-column">
	<div class="container">
    	<div class="row">
        	<div class="template-wrap">
            	<div class="max-700 centered">
                	<h1 class="f-pb">Terima Kasih Telah Berbelanja di FixcoMart!</h1>
                    <div class="nuke-wysiwyg">
                    	<p>Berikut adalah nomor pesanan Anda:<br>
							<strong style="font-size:1.5em;" class="f-red">#<?php echo sprintf('%06d',$data['id']);?></strong>
						</p>
                        <?php if($data['status_payment']=="Confirmed"):
								echo'<p>Total pembelian Anda sejumlah <strong>Rp '. number_format($totalorder).'</strong> <br />Untuk pembayaran pembelian ini menggunakan deposit Anda.</p>';
							  else:
							  	echo'<p>Silakan transfer sejumlah <strong>Rp '. number_format($totalorder).'</strong> <br />Jangan lupa konfirmasi pembayaran Anda untuk mempercepat proses pesanan Anda.</p>';
							  endif;	
						?>

                       <?php if( $data['payment_metod']=="BANK TRANSFER" ):?>

                            <p>
                            	 Kami tidak akan memproses pesanan apapun tanpa konfirmasi pembayaran.<br />
                                 Transfer pembayaran Anda ke:<br>
                            	<?php getbankaccount($data['idbank']);?>
                            </p>

                      <?php endif;?>
                    </div><!-- .nuke-wysiwyg -->
                </div><!-- .max-700 -->
                <table cellspacing="0" cellpadding="0" class="done-table">
                	<tr>
                    	<td><strong>Tanggal</strong></td>
                        <td class="td">:</td>
                        <td><?php echo $data['tgl'];?></td>
                    </tr>
                    <tr>
                    	<td><strong>Total Order</strong></td>
                        <td class="td">:</td>
                        <td>Rp <?php echo number_format($totalorder);?></td>
                    </tr>
                    <tr>
                    	<td><strong>Nomor Order</strong></td>
                        <td class="td">:</td>
                        <td>#<?php echo sprintf('%06d',$data['id']);?></td>
                    </tr>
                    <tr>
                    	<td><strong>Kurir</strong></td>
                        <td class="td">:</td>
                        <td><?php echo $data['kurir'];?></td>
                    </tr>                     
                    <tr>
                    	<td><strong>Nama Penerima</strong></td>
                        <td class="td">:</td>
                        <td><?php echo $data['nama_penerima'];?></td>
                    </tr>
                    <tr>
                    	<td><strong>Alamat Pengiriman</strong></td>
                        <td class="td">:</td>
                        <td>
							<?php
                                    echo $data['address_penerima'].'<br />';
                                    echo $data['kota_penerima'].', '. $data['kabupaten_penerima'].'<br />';
                                    echo $data['provinsi_penerima'].' - '.$data['country_penerima'].' '.$data['kodepos'];				
                            ?>                         	
                        </td>
                    </tr>
                    <tr>
                    	<td><strong>Telepon</strong></td>
                        <td class="td">:</td>
                        <td><?php echo $data['phone_penerima'];?></td>
                    </tr>

                    <tr>
                    	<td><strong>Pesan Pengiriman</strong></td>
                        <td class="td">:</td>
                        <td><?php if($data['note']==""): echo'-'; else: echo $data['note']; endif;?></td>
                    </tr>
                </table>
                <div class="done-button centered">
                	<?php if($data['status_payment']=="Confirmed"):?>
                    	<a href="<?php echo $GLOBALS['SITE_URL'];?>order-history-corporate" class="btn btn-yellow f-psb">HISTORY PEMBELIAN</a>
                    <?php else:?>
                		<a href="<?php echo $GLOBALS['SITE_URL'];?>payment-confirmation-popup" class="nuke-fancied2 btn btn-yellow f-psb">KONFIRMASI PEMBAYARAN</a>
                    <?php endif;?>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>product" class="btn btn-red f-mr">LANJUT BELANJA</a>
                </div><!-- .done-button -->
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>


</body>
</html>