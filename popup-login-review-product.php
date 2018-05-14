<?php
	@session_start();
	include('config/connection.php');
	include "include/function.php";
	include "config/myconfig.php";
	$newToken = generateFormTokenorderform('RegisterPIERO2015Login');	
	
	//update facebook BY RNT 12 may 17
	//login via facebook
	$login_url = ''.$SITE_URL.'facebook_login/login.php';		
	
	//google plus
	require_once('login_google/googlesdk/src/Google_Client.php');		
	require_once('login_google/googlesdk/src/contrib/Google_Oauth2Service.php');
	$client = new Google_Client();
	$client->setApplicationName("Google UserInfo PHP Starter Application");
	$oauth2 = new Google_Oauth2Service($client);
    $authUrl = $client->createAuthUrl();		
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#login_formuidPopup").validate({
				rules: {
					email:{
						required: true,
						email:true
					},
					password:{
						required: true
					}				
					
				},
				messages: {
					email:{
						required:"* Please enter your email address.",
						email:"* Please enter valid your email address."
					},
					password:{
						required:"* Please enter your password."
					}					
				}
		});		
	});
</script>

<div class="popup-wrap popup-small popup-login">
	<div class="popup-header">
    	<h2 class="f-pb">Login</h2>
    </div><!-- .popup-header -->
    <div class="popup-content">
    	<div style="text-align:right; padding-bottom:10px; font-size:14px;">
        	<a href="<?php echo $GLOBALS['SITE_URL'];?>login-corporate-review-product" class="nuke-fancied2"><u>Login Corporate!</u></a>
        </div>
        
        <form action="<?php echo $GLOBALS['SITE_URL'];?>do-login-review-product" method="post" class="general-form" id="login_formuidPopup">
        	 <input type="hidden" name="tokenId" value="<?php echo $newToken;?>" />
             
            <div class="form-group">
            	<label class="f-pb">Email</label>
            	<div class="input-wrap">
                	<input type="text" placeholder="Email Anda" class="has-icon hi-email" name="email" maxlength="200" />
                </div><!-- .input-wrap -->
            </div><!-- .form-group -->
            <div class="form-group">
            	<label class="f-pb">Password</label>
            	<div class="input-wrap">
                	<input type="password" placeholder="Password Anda" class="has-icon hi-password"  name="password" maxlength="20" />
                </div><!-- .input-wrap -->
            </div><!-- .form-group -->
            <a href="<?php echo $GLOBALS['SITE_URL'];?>/reset-password" class="nuke-fancied2 f-lightGray">Forgot password?</a>
            <div class="form-button">
            	<input type="submit" class="btn btn-yellow no-margin btn-full" value="LOGIN" name="submit" />
            </div><!-- .form-button -->
            <div class="separator"><span>atau</span></div>
            <div class="social-login">
                <a href="<?php echo $login_url;?>"><i class="fa fa-facebook"></i>&nbsp;&nbsp;Facebook</a>
                <a href="<?php echo $authUrl;?>" class="last"><i class="fa fa-google-plus"></i>&nbsp;&nbsp;Google+</a>
            </div><!-- .social-login -->
        </form>
    </div><!-- .popup-content -->
</div><!-- .popup-wrap -->