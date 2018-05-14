<?php include("header.php"); 

//setup captcha
$cryptinstall = "js/crypt/cryptographp.fct.php";
include $cryptinstall; 	

if(isset($_SESSION['name_fb'])): $first_nameregis = $_SESSION['name_fb']; else: $first_nameregis = ''; endif;
if(isset($_SESSION['lastname_fb'])): $lastname_fbregis = $_SESSION['lastname_fb']; else: $lastname_fbregis = ''; endif;
if(isset($_SESSION['email_fb'])): $email_fbregis = $_SESSION['email_fb']; else: $email_fbregis = ''; endif;
if(isset($_SESSION['fbid_fb'])): $fbid_fbregis = $_SESSION['fbid_fb']; else: $fbid_fbregis = ''; endif;
if(isset($_SESSION['googleid'])): $googleid_regis = $_SESSION['googleid']; else: $googleid_regis = ''; endif;	

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
                <li class="f-pb">Register untuk Regular Member</li>
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
               
                <h1 class="f-pb">Registrasi Regular Member</h1>
           		<?php if($fbid_fbregis<>'' or $googleid_regis<>''): 
                            echo'<div class="error-msg">';
                                echo'* Silahkan lengkapi form registarsi.';
                            echo'</div>';
                        endif;
                 ?>  
                <form action="<?php $GLOBALS['SITE_URL'];?>do-register" method="post" class="general-form register-form" id="register_form">
                        <input type="hidden" name="tokenId" value="<?php echo $newToken;?>" />
                    	<input type="hidden" name="fbid_fbregis" value="<?php echo $fbid_fbregis;?>" />
                    	<input type="hidden" name="googleid" value="<?php echo $googleid_regis;?>" />
                        
                    <div class="tc-form-group">
                    	<div class="form-group">
                            <label class="f-pb">Nama Depan</label>
                            <div class="input-wrap">
                                <input type="text" name="name" value="<?php echo $first_nameregis;?>" maxlength="200"  />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">Nama Belakang</label>
                            <div class="input-wrap">
                                <input type="text" name="lastname" value="<?php echo $lastname_fbregis;?>" maxlength="100" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                    </div><!-- .tc-form-group -->
                    <div class="tc-form-group">
                    	<div class="form-group">
                            <label class="f-pb">Email Kontak</label>
                            <div class="input-wrap">
                                <input type="text" name="email" id="email" value="<?php echo $email_fbregis;?>" maxlength="200" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">Konfirmasi Email</label>
                            <div class="input-wrap">
                                <input type="text" name="reemail" value="<?php echo $email_fbregis;?>" maxlength="200" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                    </div><!-- .tc-form-group -->
                    <div class="tc-form-group">
                    	<div class="form-group">
                            <label class="f-pb">Telepon</label>
                            <div class="input-wrap">
                                <input type="text" name="phone" maxlength="20"  />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">Telepon Seluler</label>
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

<section class="section">
	<div class="container">
    	<div class="row">
            <div class="corporate-invitation">
            	<?php corporateinvitation();?>
            </div><!-- .corporate-invitation -->
            
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