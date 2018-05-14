<?php include("header.php"); 
	if(isset($_SESSION['user_token'])==''):
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;

		//member data
		if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		$qummep = $db->query("SELECT `idmember_list` FROM `corporate_user` WHERE `id`='$Usertokenid'");
		$ros = $qummep->fetch_assoc();	
		$idmember = $ros['idmember_list'];
		$totaldeposit = gettotaldepositcorporate($idmember);
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Deposit</li>
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
                <h1 class="f-pb">Deposit</h1>
                <div class="red-tabbing">
                	<a href="<?php echo $GLOBALS['SITE_URL'];?>deposit-corporate" class="active">Deposit Anda</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>history-deposit">History Deposit</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>konfirmasi-deposit">Top Up Deposit</a>
                </div><!-- .red-tabbing -->
                <p>Anda dapat menggunakan deposit sebagai metode pembayaran ketika checkout.</p>
                <div class="status-corporate">
                    Deposit yang Anda miliki:
                    <span class="f-pb f-red">Rp <?php echo number_format($totaldeposit);?></span>
                </div><!-- .status-corporate -->
                <div class="history-deposit">
                	<h2 class="f-pb">Top Up Deposit Anda</h2>
                    <?php pages(26);?>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>konfirmasi-deposit" class="btn btn-red no-margin f-psb">TOP UP DEPOSIT</a>
                </div><!-- .history-deposit -->
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".menu2").addClass("active");	
	});	
</script>

</body>
</html>