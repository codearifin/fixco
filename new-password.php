<?php
	@session_start();
	include('config/connection.php');
	include "include/function.php";
	include "config/myconfig.php";
	
	//member data
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
	$qummep = $db->query("SELECT `idmember_list` FROM `corporate_user` WHERE `id`='$Usertokenid'");
	$ros = $qummep->fetch_assoc();	
	$idmember = $ros['idmember_list'];
	
	if(isset($_GET['id'])): $iduser = $_GET['id']; else: $iduser = 0; endif;

	$query = $db->query("SELECT * FROM `corporate_user` WHERE `id`='$iduser' and `idmember_list`='$idmember' and `status` = 0 ");
	$row = $query->fetch_assoc();				
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#adduserUid").validate({
				rules: {
						password:{ 
							 required: true,
							 minlength: 6
						},
						repassword:{ 
							 required: true,
							 equalTo: "#password"
						}	
					
				},
				messages: {					
						password:{ 
							 required:"* This field can't be empty.",
							 minlength: "* Please enter minimum length 6 character."
						},
						repassword:{ 
							  required:"* This field can't be empty.",
							  equalTo: "* Confirm password different with your password."
						}				
				}
		});		
	});
</script>

<div class="popup-wrap popup-medium">
	<div class="popup-header">
    	<h2 class="f-pb">Ganti Password</h2>
    </div><!-- .popup-header -->
    <div class="popup-content">
       <form action="<?php echo $GLOBALS['SITE_URL'];?>do-change-password-user" method="post" class="general-form" id="adduserUid">
       		<input type="hidden" value="<?php echo $row['id'];?>" name="iduser" />
             
            <div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">New Pasword</label>
                    <div class="input-wrap">
                        <input type="password" name="password" id="password" maxlength="20" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="f-pb">Konfirmasi Password</label>
                    <div class="input-wrap">
                        <input type="password" name="repassword" maxlength="20" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
                   	
            <div class="form-button">
            	<input type="submit" class="btn btn-red no-margin min-190" value="SIMPAN" name="submit" />
            </div><!-- .form-button -->
        </form>
    </div><!-- .popup-content -->
</div><!-- .popup-wrap -->