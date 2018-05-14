<?php
@session_start();
include('config/connection.php');
include "include/function.php";
include "config/myconfig.php";

if(isset($_SESSION['user_token'])==''):
	echo'<script type="text/javascript">window.location="/index"</script>';
endif;

//member data
if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
$qummep = $db->query("SELECT `idmember_list` FROM `corporate_user` WHERE `id`='$Usertokenid'");
$ros = $qummep->fetch_assoc();	
$uidmembermain = $ros['idmember_list'];
	
if(isset($_GET['id'])): $idabook = replaceUrel($_GET['id']); else: $idabook = 0; endif;
$qupp = $db->query("SELECT * FROM `address_book` WHERE `id`='$idabook' and `member_id`='$uidmembermain' ");
$data = $qupp->fetch_assoc();	
?>

<div class="popup-wrap popup-medium">
	<div class="popup-header">
    	<h2 class="f-pb">Edit Alamat</h2>
    </div><!-- .popup-header -->
    <div class="popup-content">
        <form action="<?php echo $GLOBALS['SITE_URL'];?>do-edit-address-book-corporate" method="post" class="general-form" id="addressbook_form">
        	<input type="hidden" name="idabook" value="<?php echo $data['id'];?>" />
            
        	<div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">Nama Penerima</label>
                    <div class="input-wrap">
                        <input type="text" name="name" value="<?php echo $data['name'];?>" maxlength="200" />
                        <span></span>
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="f-pb">Telepon Seluler</label>
                    <div class="input-wrap">
                        <input type="text" value="<?php echo $data['mobile_phone'];?>" name="mobilephone" maxlength="20"  />
                        <span></span>
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
            <div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">Provinsi</label>
                    <div class="input-wrap">
                        <div class="select-style">
                            <select name="province" class="province">
                                <option value="<?php echo $data['provinsi'];?>" selected="selected"><?php echo ucwords($data['provinsi']);?></option>
                                <?php getpropinsilist();?>
                            </select>
                        </div><!-- .select-style -->
                    </div><!-- .input-wrap -->
                    <span class="error_province error_msg"></span>
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="f-pb">Kabupaten</label>
                    <div class="input-wrap">
                        <div class="select-style">
                        <select name="kabupaten" class="kabupaten">
                              <option value="<?php echo $data['kabupaten'];?>" selected="selected"><?php echo ucwords($data['kabupaten']);?></option>
                              <?php getpropinsilist_kabu($data['provinsi']);?>
                         </select>
                        </div><!-- .select-style -->
                    </div><!-- .input-wrap -->
                    <span class="error_kabupaten error_msg"></span>   
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
            <div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">Kota</label>
                    <div class="input-wrap">
                        <div class="select-style">
                            <select name="city" class="city">
                               <option value="<?php echo $data['idcity'];?>" selected="selected"><?php echo getnamakota($data['idcity']);?></option>
                               <?php getpropinsilist_kota($data['kabupaten']);?>
                            </select>
                        </div><!-- .select-style -->
                    </div><!-- .input-wrap -->
                    <span class="error_city error_msg"></span>   
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="f-pb">Kode Pos</label>
                    <div class="input-wrap">
                        <input type="text" value="<?php echo $data['kodepos'];?>" name="kodepos" />
                        <span></span>
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
            <div class="form-group">
                <label class="f-psb">Alamat Lengkap</label>
                <div class="input-wrap">
                    <textarea name="address"><?php echo $data['address'];?></textarea>
                    <span></span>
                </div><!-- .input-wrap -->
            </div><!-- .form-group -->
            <div class="form-button">
            	<input type="submit" class="btn btn-red no-margin min-190" value="EDIT DATA" name="submit" />
            </div><!-- .form-button -->
        </form>
    </div><!-- .popup-content -->
</div><!-- .popup-wrap -->

 		<script type="text/javascript">
                $(document).ready(function() {
                    $(".province").change(function(){				   	
                         var url_siteid = $(".url_siteid").val();	
						 var propinsi = $(this).val();			
                         $.post(""+url_siteid+"include/get_kabupaten.php", {"propinsi": propinsi},
                         function(data){
                            $(".kabupaten").html(data); 
                        });
                    }); 
                               
                    $(".kabupaten").change(function(){
                         var url_siteid = $(".url_siteid").val();	
						 var kabupaten = $(this).val();			
                         $.post(""+url_siteid+"include/get_kotalist.php", {"kabupaten": kabupaten},
                         function(data){
                            $(".city").html(data); 
                         });
                    }); 

					
					$("#addressbook_form").validate({
										rules: {
											name:{
												required: true,
												abcval:true
											},
											lastname:{
												abcval:true
											},
											email:{
												required: true,
												email:true
											},
											phone:{
												angkaval: true		
											},						
											mobilephone:{
												required: true,
												angkaval: true		
											},
											province:{ 
												 required: true
											},
											kabupaten:{ 
												 required: true
											},
											city:{
												 required: true	
											},
											address:{
												 required: true,
												 addressval: true	
											}
										
										
										},

										errorPlacement: function(error, element) {  
											
											if (element.is(".province")) { 
												$(".error_province").html(error);  
											}if (element.is(".kabupaten")) { 
												$(".error_kabupaten").html(error); 
											}if (element.is(".city")) { 
												$(".error_city").html(error);  												 
											}else { element.next('span').html(error) }
										},

										
										messages: {
											name:{
												required:"* This field can't be empty.",
												abcval:"* This field contain alphabet only."
											},
											lastname:{
												abcval:"* This field contain alphabet only."
											},
											email:{
												required:"* This field can't be empty.",
												email:"* Please enter valid email address."
											},
											phone:{
												angkaval:"* This field only insert with character 0-9 + "		
											},						
											mobilephone:{
												required:"* This field can't be empty.",
												angkaval:"* This field only insert with character 0-9 + "		
											},						
											province:{ 
												 required:"* This field can't be empty."
											},
											kabupaten:{ 
												 required:"* This field can't be empty."
											},
											city:{
												 required:"* This field can't be empty."
											},
											address:{
												required:"* This field can't be empty.",
												addressval:"* This field cannot insert special character , e.g: *($#&"
											}						
											
										}
									});	
											
                
                });
            </script>