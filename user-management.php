<?php include("header.php"); 
	if(isset($_SESSION['user_token'])==''):
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;

		//member data
		if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		$qummep = $db->query("SELECT `idmember_list`,`status` FROM `corporate_user` WHERE `id`='$Usertokenid'");
		$ros = $qummep->fetch_assoc();	
		$idmember = $ros['idmember_list'];
		$superuserstatus = $ros['status'];
		
		if($superuserstatus==1):
			//no action
		else:
			echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'my-account-corporate"</script>';
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
                <li class="f-pb">User Management</li>
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
                <h1 class="f-pb">User Management</h1>
                <div class="address-book-wrap">
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>popup-add-new-user" class="nuke-fancied2 btn btn-red no-margin f-psb">TAMBAH USER</a>
                    <div class="da-wrap">
                    	<?php getmembermanagement($idmember);?>                      
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
		$(".menu11").addClass("active");	
	});	
</script>

</body>
</html>