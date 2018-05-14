<?php
	@session_start();
	include('config/connection.php');
	include "include/function.php";
	include "config/myconfig.php";
	$newToken = generateFormTokenorderform('RegisterPIERO2015Login');		
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
    	<h2 class="f-pb">Corporate Login</h2>
    </div><!-- .popup-header -->
    <div class="popup-content">
        <form action="<?php echo $GLOBALS['SITE_URL'];?>do-login-corporate-order" method="post" class="general-form" id="login_formuidPopup">
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
            <a href="<?php echo $GLOBALS['SITE_URL'];?>/popup-reset-password-corporate" class="nuke-fancied2 f-lightGray">Forgot password?</a>
            <div class="form-button">
            	<input type="submit" class="btn btn-yellow no-margin btn-full" value="LOGIN" name="submit" />
            </div><!-- .form-button -->
        </form>
    </div><!-- .popup-content -->
</div><!-- .popup-wrap -->