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
						name:{
							required: true,
							abcval:true
						},
						lastname:{
							abcval:true
						},
						divisi:{
							required: true,
							abcval:true
						},
						email:{
							required: true,
							email:true
						},
						reemail:{ 
							 required: true,
							 equalTo: "#email"
						},						
						phone:{
							angkaval: true		
						},						
						mobilephone:{
							required: true,
							angkaval: true		
						},
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
						name:{
							required:"* This field can't be empty.",
							abcval:"* This field contain alphabet only."
						},
						lastname:{
							abcval:"* This field contain alphabet only."
						},
						divisi:{
							required:"* This field can't be empty.",
							abcval:"* This field contain alphabet only."	
						},
						email:{
							required:"* This field can't be empty.",
							email:"* Please enter valid email address."
						},
						reemail:{ 
							required:"* This field can't be empty.",
							equalTo: "* Confirm email different with your email address."
						},							
						phone:{
							angkaval:"* This field only insert with character 0-9 + "		
						},						
						mobilephone:{
							required:"* This field can't be empty.",
							angkaval:"* This field only insert with character 0-9 + "		
						},						
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
    	<h2 class="f-pb">Edit User</h2>
    </div><!-- .popup-header -->
    <div class="popup-content">
       <form action="<?php echo $GLOBALS['SITE_URL'];?>do-edit-user-corporate" method="post" class="general-form" id="adduserUid">
       		<input type="hidden" value="<?php echo $row['id'];?>" name="iduser" />
            <input type="hidden" name="emaillama" value="<?php echo $row['email'];?>" />
             
         	<div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">Nama Depan</label>
                    <div class="input-wrap">
                        <input type="text" name="name" maxlength="200" value="<?php echo $row['name'];?>" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="f-pb">Nama Belakang</label>
                    <div class="input-wrap">
                        <input type="text" name="lastname" maxlength="200" value="<?php echo $row['lastname'];?>" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
            <div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">Divisi</label>
                    <div class="input-wrap">
                        <input type="text" name="divisi" maxlength="200" value="<?php echo $row['divisi'];?>" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="f-pb">Email</label>
                    <div class="input-wrap">
                        <input type="text" name="email" value="<?php echo $row['email'];?>" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
            <div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">Telepon</label>
                    <div class="input-wrap">
                        <input type="text" name="phone" value="<?php echo $row['phone'];?>" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="f-pb">Telepon Selular</label>
                    <div class="input-wrap">
                        <input type="text" name="mobilephone" value="<?php echo $row['mobile'];?>" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
                   	
            <div class="form-button">
            	<input type="submit" class="btn btn-red no-margin min-190" value="SIMPAN" name="submit" />
            </div><!-- .form-button -->
        </form>
    </div><!-- .popup-content -->
</div><!-- .popup-wrap -->