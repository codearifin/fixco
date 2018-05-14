<?php include("header.php"); 

	 if(isset($_SESSION['user_token'])==''):
	 	echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	 endif;
	
	 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else:  $Usertokenid = ''; endif;
	 if(isset($_GET['codepay'])): $code = $_GET['codepay']; endif;
	
	 $query = $db->query("SELECT `id` FROM `member` WHERE `tokenmember`='$Usertokenid'");
	 $res = $query->fetch_assoc();
	 $idmember = $res['id']; 

	 //select order
	 $que = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') as tglbel, DATE_FORMAT(`date`, '%d%m%Y') as tglbeltrx , DATE_FORMAT(`date`, '%Y%m%d') as kodetrx 
	 FROM `order_header` WHERE `idmember`='$idmember' and `status_payment`='Pending On Payment' and `tokenpay`='$code'");
	 $jumdataorder = $que->num_rows;
	 if($jumdataorder < 1):
	 	echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	 endif;
	 
	 $data = $que->fetch_assoc();
	 $totalorder = (($data['orderamount']+$data['shippingcost']+$data['kode_unik']+$data['handling_fee'])-$data['discountamount'])-$data['deposit_amount'];	
	 $shipporder = 0;
	 $bca_klik_pay_cicilan = $data['bca_klik_pay_cicilan'];
	 
	 //form 	
	 $callbackurel = $GLOBALS['SITE_URL'].'bca-klikpay-confirmation/'.$code.'';
	 $itemtrxno_pay = $data['id'].$data['kodetrx'].$data['idmember'];
	 $transactionNo = substr($itemtrxno_pay,0,18);	
	 $tgl_trxodr = $data['tglbeltrx'];

	 //cicilan
	 if($bca_klik_pay_cicilan>0):
	 	$klikpaycode	= "03FIXC0538";
	 	$clearkey 		= "ClearKeyDevFixco";		 
	 	$paytype 		= "02";
		$urlForm		= 'https://202.6.215.230:8081/purchasing/purchase.do?action=loginRequest';
	 else:
	 	$klikpaycode	= "03FIXC0538";
	 	$clearkey 		= "ClearKeyDevFixco";
		$paytype 		= "01";	
		$urlForm		= 'https://202.6.215.230:8081/purchasing/purchase.do?action=loginRequest';
	 endif;
	  		
	 $keyId   		= genKeyId($clearkey);
	 $signatureUid 	= genSignature($klikpaycode, $tgl_trxodr, $transactionNo, $totalorder.".00", "IDR", $keyId);
	  	 
	//set code in order form
	$queppodr = $db->query("UPDATE `order_header` SET `bca_tokenid`='$transactionNo', `signature_bca` = '$signatureUid' WHERE `idmember`='$idmember' and `status_payment`='Pending On Payment' and `tokenpay`='$code' ");
  	
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
                <li class="f-pb">Order Confirmation BCA KlikPay</li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section one-column">
	<div class="container">
    	<div class="row">
        	<div class="template-wrap">
            	<div class="max-700 centered">
                	<h1 class="f-pb">Please Waiting Process...</h1>
                    <div class="nuke-wysiwyg">
                    	<img src="<?php echo $GLOBALS['SITE_URL'];?>images/load_page.gif" class="loadimgbtn" style="display:none;" />
                     	
                      <form method="post" class="register-form" action="<?php echo $urlForm;?>">
                        <input type="hidden" name="klikPayCode" value="<?php echo $klikpaycode;?>" maxlength="10" />
                        <input type="hidden" name="transactionNo" value="<?php echo $transactionNo;?>" maxlength="18" />                       
                        <input type="hidden" name="totalAmount" value="<?php echo $totalorder;?>.00" maxlength="14" />
                        <input type="hidden" name="currency" value="IDR" maxlength="5" />
                        <input type="hidden" name="payType" value="<?php echo $paytype;?>" maxlength="2" />
                        <input type="hidden" name="callback" value="<?php echo $callbackurel;?>" maxlength="100" />
                        <input type="hidden" name="transactionDate" value="<?php echo $data['tglbel'];?>" maxlength="19" />
                        <input type="hidden" name="descp" value="Order in Fixcomart" maxlength="60" /><!-- COMPLEMENT -->
                        <input type="hidden" name="miscFee" value="<?php echo $shipporder;?>.00" maxlength="14" /><!-- COMPLEMENT ONGKIR -->
                        <input type="hidden" name="signature" value="<?php echo $signatureUid;?>" maxlength="10" />              
                     	<input type="submit" value="Process BCA KlikPay" name="proses_submit" class="proses_submit submit-btn f-rcr" />
                     </form>
                        
                    </div><!-- .nuke-wysiwyg -->
                </div><!-- .max-700 -->
               

            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

	<script type="text/javascript">
        $(document).ready(function() {
             //$(".proses_submit").attr("style","display:none;");	
             $(".loadimgbtn").removeAttr("style");	
             $(".proses_submit").click();
        });
    </script>

</body>
</html>