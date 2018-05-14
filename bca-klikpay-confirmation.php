<?php include("header.php"); 

	  function cancelorderlistmember($orderid,$idmember){		
			global $db;
			$que = $db->query("SELECT * FROM `order_header` WHERE `id`='$orderid' and `idmember`='$idmember' ");
			$row = $que->fetch_assoc();
			$ordidmm = $row['id'];
			
			$quipst3 = $db->query("UPDATE `order_header` SET `status_payment`='Cancelled By User',`status_delivery`='Cancelled By User' WHERE `id`='$orderid' ");
		   
					   
			$idprod = ''; $totalqty = 0; 
			$que33 = $db->query("SELECT `iddetail`,`qty` FROM `order_detail` WHERE `tokenpay`='".$row['tokenpay']."' ORDER BY `id` ASC");
			while($data = $que33->fetch_assoc()):
				$iddetail = $data['iddetail'];
				$totalqty = $data['qty'];
				$quipst2 = $db->query("UPDATE `product_detail_size` SET `stock` = `stock` + '$totalqty' WHERE `id`='$iddetail'");	
			endwhile;
		
	 }
	 
	 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else:  $Usertokenid = ''; endif;
	 if(isset($_GET['codepay'])): $code = replaceUrel($_GET['codepay']); else: $code = ''; endif;

	 $query = $db->query("SELECT `id` FROM `member` WHERE `tokenmember`='$Usertokenid'");
	 $res = $query->fetch_assoc();
	 $idmember = $res['id']; 
	
	 //select order
	 $que = $db->query("SELECT * FROM `order_header` WHERE `idmember`='$idmember' and `tokenpay`='$code'");
	 $data = $que->fetch_assoc();
	 $totalorder = (($data['orderamount']+$data['shippingcost']+$data['kode_unik'])-$data['discountamount'])-$data['deposit_amount'];	 	
	 	
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
                <li class="f-pb">Done!</li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section one-column">
	<div class="container">
    	<div class="row">
        	<div class="template-wrap">
            	<div class="max-700 centered">
                	<h1 class="f-pb"><?php if($data['status_payment']=="Confirmed"): echo'Payment Transaction Success.'; else: echo'Sorry! Payment Transaction Failed.'; endif;?></h1>
                    <div class="nuke-wysiwyg">

 							<span style="font-size:14px;">Order Summary:</span><br />

                            Order ID: <strong>#<?php echo sprintf('%06d',$data['id']);?></strong><br />

                            Payment Method: <strong><?php echo $data['payment_metod'];?></strong><br />

                            <?php /* Status Payment: <strong><?php echo $data['status_payment'];?></strong><br /> */?>

                            Total Order: <strong>IDR <?php echo number_format($totalorder);?></strong><br />

                            

                              <div style="padding-top:20px;">

                                 <img src="<?php echo $GLOBALS['SITE_URL'];?>images/bcalogo.png" alt="" style="height:60px;"/>

                                 <p><?php if($data['status_payment']=="Confirmed"): 
								 			echo'Transaksi BCA KlikPay Anda Berhasil'; 
										  else: 
										  	echo'Transaksi BCA KlikPay Anda Gagal'; 
											//cancel orderan--
											cancelorderlistmember($data['id'],$idmember);
										  endif;
									?></p>  
                              </div>

                              <div style="clear:both; height:20px;"></div>
                    	
                        
                    </div><!-- .nuke-wysiwyg -->
                </div><!-- .max-700 -->
               

            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

</body>
</html>