<?php
	@session_start();
	require("config/connection.php");
	require("config/myconfig.php");
	require("include/function.php");
	
	if(isset($_SESSION['user_token'])==''):
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;

	//member data
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
	$qummep = $db->query("SELECT `id`,`idmember_list`,`status` FROM `corporate_user` WHERE `id`='$Usertokenid'");
	$ros = $qummep->fetch_assoc();	
	$idmember = $ros['idmember_list']; $idusr = $ros['id'];
	
	if(isset($_GET['token'])): $token = replaceUrel($_GET['token']); else: $token = ''; endif;
	if($ros['status']==1):
		$que = $db->query("SELECT `id`,`tokenpay` FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' and `tokenpay`='$token'");
	else:
		$que = $db->query("SELECT `id`,`tokenpay` FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' and `id_user` = '$idusr' and `tokenpay`='$token'");
	endif;
	$row = $que->fetch_assoc();	
?>

<div class="popup-wrap popup-medium">
	<div class="popup-header">
    	<h2 class="f-pb">Tambah Produk Baru - <?php echo '#DQ-'.sprintf('%06d',$row['id']).'';?></h2>
    </div><!-- .popup-header -->
    <div class="popup-content">

		<form action="<?php echo $GLOBALS['SITE_URL'];?>do-save-product-list" method="post" class="general-form" id="addressbook_form">
        	<input type="hidden" name="token" value="<?php echo $row['tokenpay'];?>" />
            
        	<div class="tc-form-group">
                <div class="form-group" style="width:100%;">
                    <div class="input-wrap">
                    	 <div class="select-style">
                            <select name="idpord" class="idpord">
                                <option value="">Pilih Produk</option>
                                <?php getproductlistaddquote();?>
                            </select>
                         </div>   
                    </div><!-- .input-wrap -->
                    <span class="error_idpord error_msg"></span>
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
            
             <div class="tc-form-group">
                   <div class="form-group" style="width:70%;">
                       <label class="f-pb">Pilih Detail</label>
                       <div class="input-wrap">
                           <div class="select-style">
                               <select name="iddetail" class="iddetail">
                                   <option  value="" selected="selected">- Pilih Detail -</option>
                               </select>
                           </div><!-- .select-style -->
                       </div><!-- .input-wrap -->
                       <span class="error_iddetail error_msg"></span>
                 </div><!-- .form-group -->
                 <div class="form-group" style="width:30%;">
                      <label class="f-pb">Qty</label>
                       <div class="input-wrap">
                          <input type="text" name="qty" value="1" style="width:80px;" onkeyup="rupiah(this);" />
                          <span></span>
                       </div><!-- .input-wrap -->
                   </div><!-- .form-group -->
            </div><!-- .tc-form-group -->

            <div class="form-button" style="padding-top:20px;">
            	<input type="submit" class="btn btn-red no-margin min-190" value="SIMPAN" name="submit" />
            </div><!-- .form-button -->
                        
        </form>
        
    </div><!-- .popup-content -->
</div><!-- .popup-wrap -->

<script type="text/javascript">
               
			    $(document).ready(function() {
                    $(".idpord").change(function(){				   	
                         var url_siteid = $(".url_siteid").val();	
						 var idpord = $(this).val();			
                         $.post(""+url_siteid+"include/getproduct_detail.php", {"idpord": idpord},
                         function(data){
                            $(".iddetail").html(data); 
                        });
                    }); 

					$("#addressbook_form").validate({
						rules: {
							idpord:{
								required: true
							},
							iddetail:{
								required: true
							},
							qty:{
								required: true
							}
													
						},

						errorPlacement: function(error, element) {  
								
							if (element.is(".idpord")) { 
								$(".error_idpord").html(error);  
							}if (element.is(".iddetail")) { 
								$(".error_iddetail").html(error); 											 
							}else { element.next('span').html(error) }
						},

						messages: {

							idpord:{
								required:"* This field can't be empty."
							},
							iddetail:{
								required:"* This field can't be empty."
							},
							qty:{
								required:"* This field can't be empty."
							}
						
						}
					});	
											
                
                });
				
</script>