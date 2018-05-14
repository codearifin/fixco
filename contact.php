<?php include("header.php"); 
//setup captcha
$cryptinstall = "js/crypt/cryptographp.fct.php";
include $cryptinstall; 	
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Hubungi Kami</li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section one-column">
	<div class="container">
    	<div class="row">
        	<div class="template-wrap">
                <h1 class="f-pb">Hubungi Kami</h1>
                <div class="nuke-wysiwyg">
                    <?php pages(8);?>
                </div><!-- .nuke-wysiwyg -->
                <div class="contact-page">
                	
                    <form action="<?php echo $GLOBALS['SITE_URL'];?>do-contact" method="post" class="general-form contact-form" id="contact_formid">
                    	<div class="form-group">
                        	<label class="f-pb">Email</label>
                            <div class="input-wrap">
                            	<input type="text" name="email" maxlength="200" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                        	<label class="f-pb">Nama Anda</label>
                            <div class="input-wrap">
                            	<input type="text" name="name" maxlength="200" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                        	<label class="f-pb">Telepon</label>
                            <div class="input-wrap">
                            	<input type="text" name="phone" maxlength="120" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                        	<label class="f-pb">Pesan Anda</label>
                            <div class="input-wrap">
                            	<textarea name="pesanmember"></textarea>
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="tc-form-group tc-captcha">
                            <div class="form-group">
                                <label class="f-pb">Captcha</label>
                                <div class="input-wrap">
                                    <input type="text" placeholder="Ketik kode captcha" name="kodevalidasi" maxlength="10" />
                                </div><!-- .input-wrap -->
                            </div><!-- .form-group -->
                            <div class="form-group">
                                <div class="captcha-wrap list-capcay">
                                    <?php dsp_crypt(0,1);?>
                                </div><!-- .captcha-wrap -->
                            </div><!-- .form-group -->
                        </div><!-- .tc-form-group -->
                        <div class="form-button">
                        	<input type="submit" class="btn btn-red f-psb no-margin" value="KIRIM PESAN" name="submit" />
                        </div><!-- .form-button -->
                    </form>
                    
                    <aside>
                    	<div class="ft-child">
                        	<h3>FIXCO MART</h3>
                            <ul class="footer-info">
                            	<li class="fi-address"><?php echo nl2br($company_address);?></li>
								<li class="fi-phone"><?php echo $phonepig;?></li>
                                <li class="fi-email"><a href="mailto:<?php echo $emailconfig;?>"><?php echo $emailconfig;?></a></li>
                            </ul><!-- .footer-info -->
                        </div><!-- .ft-child -->
                        <div class="ft-child" style="margin-bottom:0;">
                        	<h3>LIHAT MEDIA SOSIAL KAMI</h3>
                        	<nav class="soc-nav">
                            	<a href="<?php echo $facebook_link;?>" target="_blank"><i class="fa fa-facebook"></i></a>
                                <a href="<?php echo $twitter_link;?>" target="_blank"><i class="fa fa-twitter"></i></a>
                                <a href="<?php echo $instagram_link;?>" target="_blank"><i class="fa fa-instagram"></i></a>
                                <a href="<?php echo $youtube_link;?>" target="_blank"><i class="fa fa-youtube"></i></a>
                            </nav>
                        </div><!-- .ft-child -->
                    </aside>
                </div><!-- .contact-page -->
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<section class="contact-map">
    <div class="flexible-map">
     <iframe src="<?php echo $googleplusmapcode;?>" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div><!-- .flexible-map -->
</section><!-- .contact-map -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>


</body>
</html>