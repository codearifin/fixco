<?php
	@session_start();
	include('config/connection.php');
	include "include/function.php";
	include "config/myconfig.php";
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#reset_formuid").validate({
				rules: {
					email:{
						required: true,
						email:true
					}			
					
				},
				messages: {
					email:{
						required:"* Please enter your email address.",
						email:"* Please enter valid your email address."
					}					
				}
		});		
	});
</script>

<div class="popup-wrap popup-small">
	<div class="popup-header">
    	<h2 class="f-pb">Lupa Password?</h2>
    </div><!-- .popup-header -->
    <div class="popup-content">
    	<p>Masukkan email Anda yang terdaftar di website kami.</p>
        <form action="<?php echo $GLOBALS['SITE_URL'];?>do-reset-password-corporate" method="post" class="general-form" id="reset_formuid">
        	<div class="form-group">
            	<label class="f-pb">Email</label>
            	<div class="input-wrap">
                	<input type="text" placeholder="Silakan masukkan email Anda" name="email" maxlength="200" />
                </div><!-- .input-wrap -->
            </div><!-- .form-group -->
            <div class="form-button">
            	<input type="submit" class="btn btn-red no-margin min-190" value="RESET PASSWORD" />
            </div><!-- .form-button -->
        </form>
    </div><!-- .popup-content -->
</div><!-- .popup-wrap -->