<?php include("header.php"); 

	if(isset($_SESSION['user_token'])==''):
		$_SESSION['error_msg']='login-first';
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;

	//member data
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
	$query = $db->query("SELECT `id` FROM `member` WHERE `tokenmember`='$Usertokenid'");
	$res = $query->fetch_assoc();
	$idmember = $res['id']		
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Alamat Pengiriman</li>
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
                <h1 class="f-pb">Alamat Pengiriman</h1>   
            	<?php getjumlahaddbook($idmember);?>
                <div class="address-book-wrap">
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>add-new-addres" class="nuke-fancied2 btn btn-red no-margin f-psb">TAMBAH ALAMAT</a>
                    <div class="da-wrap">
                        <?php addressbook($idmember);?>
                    </div><!-- .da-wrap -->
                </div><!-- .address-book-wrap -->
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".menu3").addClass("active");	
	});	
</script>

</body>
</html>