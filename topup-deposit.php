<?php include("header.php"); 

	if(isset($_SESSION['user_token'])==''):
		$_SESSION['error_msg']='login-first';
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;
	
	//member data
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
	$query = $db->query("SELECT * FROM `member` WHERE `tokenmember`='$Usertokenid'");
	$res = $query->fetch_assoc();
	$idmember = $res['id'];	

	$quepp = $db->query("SELECT * FROM `member_membership_data` WHERE `idmember`='$idmember' ");
	$jumpage = $quepp->num_rows;
	if($jumpage < 1):	
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'upgrade-membership"</script>';	
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
                <li>Upgrade Membership</li>
                <li class="f-pb">Top Up Deposit</li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section one-column">
	<div class="container">
    	<div class="row">
        	<div class="template-wrap">
            
                <h1 class="f-pb">Top Up Deposit</h1>
                <form action="<?php echo $GLOBALS['SITE_URL'];?>do-topup-membership" method="post" class="general-form checkout-form" id="membership_topup">
                    <div class="cpb-child">
                        <h2 class="f-pb">1. Jumlah Deposit</h2>
                        <div class="form-group">
                            <div class="input-wrap">
                                <div class="select-style">
                                    <select name="membership_price" class="membership_price">
                                       <?php getjumlahmutasilist();?>
                                    </select>
                                </div><!-- .select-style -->
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                    </div><!-- .cpb-child -->
                    <div class="cpb-child last-child">
                        <h2 class="f-pb">2. Metode Pembayaran</h2>
                        <div class="payment-accordion">
							
                             <div class="pa-child first-child">
                                 <a href="#" class="pa-toggle opened">BANK TRANSFER</a>
                                 <div class="pac-child">
                                 	<?php bannklist();?>
                                  </div><!-- .pac-child -->
                             </div><!-- .pa-child -->
                                    
                        </div><!-- .payment-accordion -->
                        
                        <div class="snk-check">
                             <input type="checkbox" id="snk" name="snk" class="rme-check termchecklist" />
                             <label for="snk" class="rme-label">Saya setuju dengan <a href="<?php echo $GLOBALS['SITE_URL'];?>syarat-dan-ketentuan-membership" target="_blank">syarat dan ketentuan</a> yang berlaku.</label>
                        </div><!-- .snk-check -->
                         <input type="hidden" name="bank_transferid" id="bank_transferid" value="0" />
                      	 <input type="hidden" name="termconditionid" id="termconditionid" value="0" />
                        
                    </div><!-- .cpb-child -->
                    <div class="form-button aleft">
                          <?php echo'<input type="submit" value="" class="btn btn-red no-margin btn-checkout" name="submit" id="btn_submitorder" style="display:none;"  />';
                           	    echo'<a href="#" class="btn btn-red no-margin btn-checkout bayar_btndd">BAYAR</a>';?>						   
                            	<img src="<?php echo $GLOBALS['SITE_URL'];?>images/load_page.gif" class="saveorder_load" style="display:none;" />    
							<div style="clear:both; height:1px;"></div>
                    </div><!-- .form-button -->
                </form><!-- .checkout-form -->
                
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".menu9").addClass("active");	
	});	
</script>

</body>
</html>