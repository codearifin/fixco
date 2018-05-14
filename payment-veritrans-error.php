<?php include("header.php"); 

	  function cancelorderlistmember($orderid,$idmember){		
			global $db;
			$que = $db->query("SELECT `id`,`tokenpay` FROM `order_header` WHERE `id`='$orderid' and `idmember`='$idmember' and `status_payment`='Pending On Payment' ");
			$jumpage = $que->num_rows;
			if($jumpage>0):
				$row = $que->fetch_assoc();
				$ordidmm = $row['id'];
				
				$quipst3 = $db->query("UPDATE `order_header` SET `status_payment`='Transaction Fail', `status_delivery`='Transaction Fail' WHERE `id`='$ordidmm' and `idmember`='$idmember' ");
			
				$idprod = ''; $totalqty = 0; 
				$que33 = $db->query("SELECT `iddetail`,`qty` FROM `order_detail` WHERE `tokenpay`='".$row['tokenpay']."' ORDER BY `id` ASC");
				while($data = $que33->fetch_assoc()):
					$iddetail = $data['iddetail'];
					$totalqty = $data['qty'];
					$quipst2 = $db->query("UPDATE `product_detail` SET `stock` = `stock`+'$totalqty' WHERE `id`='$iddetail'");	
				endwhile;	
			endif;		
	 }	
	 
	 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else:  $Usertokenid = ''; endif;
	 if(isset($_SESSION['user_statusmember'])): $user_statusmember = $_SESSION['user_statusmember']; else: $user_statusmember = ''; endif;
	 if(isset($_SESSION['tokenorderidmember'])): $code = $_SESSION['tokenorderidmember']; else: $code = ''; endif;

	 if($user_statusmember=="REGULAR MEMBER"):	
		 $query = $db->query("SELECT `id` FROM `member` WHERE `tokenmember`='$Usertokenid'");
		 $res = $query->fetch_assoc();
		 $idmember = $res['id']; 
	 else:
		$qummep = $db->query("SELECT `idmember_list` FROM `corporate_user` WHERE `id`='$Usertokenid'");
		$ros = $qummep->fetch_assoc();	
		$idmember = $ros['idmember_list'];	 
	 endif
	
	 //select order
	 $que = $db->query("SELECT *,DATE_FORMAT(`date`,'%W, %d %M %Y') as tgl FROM `order_header` WHERE `idmember`='$idmember' and `tokenpay`='$code'");
	 $data = $que->fetch_assoc();
	 $totalorder = (($data['orderamount']+$data['shippingcost']+$data['kode_unik']+$data['handling_fee'])-$data['discountamount']) - $data['deposit_amount'];	

	 //cancel order
	 cancelorderlistmember($data['id'],$idmember);	 
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
                	<h1 class="f-pb">Payment Transaction Fail.</h1>
                    <div class="nuke-wysiwyg">
                    	<p>Berikut adalah nomor pesanan Anda:<br>
							<strong style="font-size:1.5em;" class="f-red">#<?php echo sprintf('%06d',$data['id']);?></strong>
						</p>
                        <p>Total order: <strong>Rp <?php echo number_format($totalorder);?></strong></p>
                        
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
                	<?php if($user_statusmember=="REGULAR MEMBER"):?>
                     		<a href="<?php echo $GLOBALS['SITE_URL'];?>order-history" class="btn btn-yellow f-psb">HISTORY PEMBELIAN</a>               			
                    <?php else:?>
                    		<a href="<?php echo $GLOBALS['SITE_URL'];?>order-history-corporate" class="btn btn-yellow f-psb">HISTORY PEMBELIAN</a>
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