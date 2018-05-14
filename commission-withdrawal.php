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
	$totalkomisi = getkomisimemberlistTotal($idmember);
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Affiliate</li>
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
                <h1 class="f-pb">Affiliate</h1>
                <div class="red-tabbing">
                	<a href="<?php echo $GLOBALS['SITE_URL'];?>affiliate">Affiliate Link</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>affiliate-member">Member</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>affiliate-commission">Commission</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>commission-withdrawal" class="active">Commission Withdrawal</a>
                </div><!-- .red-tabbing -->
                <?php pages(18);?>
                
                <form action="<?php echo $GLOBALS['SITE_URL'];?>do-claim-bonus" method="post" class="general-form profile-form" id="form_regis_drawal">
                	<div class="boxed-form">
                    	<h2 class="boxed-heading f-pb">FORM REQUEST WITHDRAWAL</h2>
                        <div class="boxed-content">
                      		<?php if($totalkomisi>0):?>
                                <div class="form-group">
                                    <label class="f-pb">Total Availability Commission <span class="f-pr">(in Rupiah)</span></label>
                                    <div class="input-wrap">
                                        <input type="text" value="<?php echo number_format($totalkomisi);?>" disabled="disabled" />
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                                <div class="form-group">
                                    <label class="f-pb">Total Withdrawal <span class="f-pr">(in Rupiah)</span></label>
                                    <div class="input-wrap">
                                        <input type="text" placeholder="Enter your desired amount" name="totalamount" class="totalamount" onKeyUp="rupiahCekWithdrawal(this);" />
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                                <div class="form-group">
                                    <label class="f-pb">Sent to Account Number</label>
                                    <div class="input-wrap">
                                         <input type="text" name="bank_transfer" placeholder="BCA - No. Rek 670 7460 602 A.n. Jack Sparrow" value="<?php echo $res['bank_account'];?>" readonly="readonly" />
                                    </div><!-- .input-wrap -->
                                    <small class="small-note">Note: You can edit your bank account data in <a href="<?php echo $GLOBALS['SITE_URL'];?>my-account">your account page</a>.</small>
                                </div><!-- .form-group -->
                                <div class="form-group">
                                    <label class="f-pb">Konfirmasi Password</label>
                                    <div class="input-wrap">
                                        <input type="password" name="password" maxlength="20" placeholder="Enter your password to confirm the withdrawal" />
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                                <div class="form-button">
                                    <input type="submit" class="btn btn-red no-margin f-psb" value="WITHDRAW" name="submit" />
                                </div><!-- .form-button -->
                            <?php else:?>
                                 <div class="form-group">
                                    <label class="f-pb">Total Availability Commission <span class="f-pr">(in Rupiah)</span></label>
                                    <div class="input-wrap">
                                        <input type="text" value="<?php echo number_format($totalkomisi);?>" disabled="disabled" />
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->                           
                            <?php endif;?>
                        </div><!-- .boxed-content -->
                    </div><!-- .boxed-form -->
                </form>
                
                <h2 class="f-pb general-h2">Ketentuan Withdrawal</h2>
                <div class="nuke-wysiwyg">
                    <?php pages(19);?>
                </div><!-- .nuke-wysiwyg -->
                
                <div class="history-deposit">
                	<h2 class="f-pb">Need More Info?</h2>
                    <?php pages(15);?>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>contact" class="btn btn-red no-margin f-pb">CONTACT US</a>
                </div><!-- .history-deposit -->
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script>
	$(document).ready(function() {
		$(".menu11").addClass("active");
		new Clipboard('.clip-btn');
	});
</script>

</body>
</html>