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
	
	//Affiliate Status
	$status_affiliate = getstatusappurel($idmember);
	
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
                	<a href="<?php echo $GLOBALS['SITE_URL'];?>affiliate" class="active">Affiliate Link</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>affiliate-member">Member</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>affiliate-commission">Commission</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>commission-withdrawal">Commission Withdrawal</a>
                </div><!-- .red-tabbing -->
                
               <?php pages(13);?>
               
                <div class="status-corporate status-full">
                    Status affiliate link Anda:
                    <?php if($status_affiliate>0):
                            getstatusappurelPage($idmember);
                    	  else:
                            echo'<span class="f-pb f-red">In Active</span>
                            <div class="affiliate-url-wrap">
                            <!-- Trigger -->
                            <button class="aff-btn aff-not-ok" disabled>
                                Copy URL
                            </button>
                            <!-- Target -->
                            <textarea id="affiliate-url" readonly>Please contact admin for more information</textarea>';
                    	  endif;
                     ?>
                    
                </div><!-- .status-corporate -->
                
                <h2 class="f-pb general-h2">Cara Menggunakan Affiliate Link</h2>
                <div class="nuke-wysiwyg">
					<?php pages(14);?>
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