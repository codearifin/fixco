<?php include("header.php"); 

	if(isset($_SESSION['user_token'])==''):
		$_SESSION['error_msg']='login-first';
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
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
                <li class="f-pb">Ganti Password</li>
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
                <h1 class="f-pb">Ganti Password</h1>
                
                <form action="<?php echo $GLOBALS['SITE_URL'];?>do-change-password" method="post" class="general-form profile-form" id="register_form">
                	   
                    <div class="boxed-form">
                    	<h2 class="boxed-heading f-pb">GANTI PASSWORD</h2>
                        <div class="boxed-content">

                            <div class="tc-form-group">
                                <div class="form-group">
                                    <label class="f-pb">Password Baru</label>
                                    <div class="input-wrap">
                                        <input type="password" placeholder="Ketik password baru Anda" name="password" id="password" maxlength="20" />
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                                <div class="form-group">
                                    <label class="f-pb">Konfirmasi Password Baru</label>
                                    <div class="input-wrap">
                                        <input type="password" placeholder="Ketik ulang password baru Anda" name="repassword" maxlength="20" />
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                            </div><!-- .tc-form-group -->
                        </div><!-- .boxed-content -->
                    </div><!-- .boxed-form -->
                    
                    <div class="form-button">
                    	<input type="submit" class="btn btn-red no-margin f-psb btn-checkout" value="SIMPAN" name="submit" />
                    </div><!-- .form-button -->
                </form>
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".menu8").addClass("active");	
	});	
</script>

</body>
</html>