<?php include("header.php"); 

//setup captcha
$cryptinstall = "js/crypt/cryptographp.fct.php";
include $cryptinstall; 	
//token form
$newToken = generateFormTokenorderform('RegisterPIERO2014');	
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Register untuk Corporate Member</li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section one-column">
	<div class="container">
    	<div class="row">
        	<div class="template-wrap">
            	<ul class="top-bc-wrap">
                	<?php include("cart-list-link.php");?>
                </ul><!-- .top-bc-wrap -->
                <h1 class="f-pb">Registrasi Corporate Member</h1>
               	
                <form action="<?php $GLOBALS['SITE_URL'];?>do-register-corporate" method="post" class="general-form register-form" id="register_formcoor" enctype="multipart/form-data">
                        <input type="hidden" name="tokenId" value="<?php echo $newToken;?>" />
                   
                    <div class="form-group">
                        <label class="f-pb">Nama Perusahaan</label>
                        <div class="input-wrap">
                            <input type="text" name="company" maxlength="200"  />
                        </div><!-- .input-wrap -->
                    </div><!-- .form-group -->
                    <div class="form-group">
                        <label class="f-pb">NPWP Perusahaan <em class="f-pr">(optional)</em></label>
                        <div class="input-wrap">
                            <input type="text" name="npwp" maxlength="200" />
                        </div><!-- .input-wrap -->
                    </div><!-- .form-group -->
                    <div class="form-group">
                        <label class="f-pb">Unggah File <span>(Max Size: 1MB)</span></label>
                        <div class="input-wrap">
                            <input type="file" name="filecompany"  />
                        </div><!-- .input-wrap -->
                    </div><!-- .form-group -->
                                            
                    <div class="tc-form-group">
                    	<div class="form-group">
                            <label class="f-pb">Nama Depan Kontak</label>
                            <div class="input-wrap">
                                <input type="text" name="name" value="" maxlength="200"  />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">Nama Belakang Kontak</label>
                            <div class="input-wrap">
                                <input type="text" name="lastname" value="" maxlength="100" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                    </div><!-- .tc-form-group -->
                    <div class="tc-form-group">
                    	<div class="form-group">
                            <label class="f-pb">Email Kontak</label>
                            <div class="input-wrap">
                                <input type="text" name="email" id="email" value="" maxlength="200" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">Konfirmasi Email Kontak</label>
                            <div class="input-wrap">
                                <input type="text" name="reemail" value="" maxlength="200" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                    </div><!-- .tc-form-group -->
                    <div class="tc-form-group">
                    	<div class="form-group">
                            <label class="f-pb">Telepon Kontak</label>
                            <div class="input-wrap">
                                <input type="text" name="phone" maxlength="20"  />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">Telepon Seluler Kontak</label>
                            <div class="input-wrap">
                                <input type="text" name="mobilephone" maxlength="20" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                    </div><!-- .tc-form-group -->
                    <div class="tc-form-group">
                    	<div class="form-group">
                            <label class="f-pb">Password</label>
                            <div class="input-wrap">
                                <input type="password" name="password" id="password" maxlength="20" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">Konfirmasi Password</label>
                            <div class="input-wrap">
                                <input type="password" name="repassword" maxlength="20"  />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                    </div><!-- .tc-form-group -->
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
                        <input type="submit" class="btn btn-red f-psb no-margin" value="REGISTER" name="submit" />
                    </div><!-- .form-button -->
                </form>
                
                
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script>
$(document).ready(function() {	
	$("#menu_list3").addClass("active");		
});
</script>


</body>
</html>