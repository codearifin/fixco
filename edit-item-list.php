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
	
	if(isset($_GET['idlist'])): $idlist = replaceUrel($_GET['idlist']); else: $idlist = 0; endif;
	
	//select quote detail
	$queeerp = $db->query("SELECT * FROM `draft_quotation_detail` WHERE `id`='$idlist' and `tokenpay`='".$row['tokenpay']."' ");
	$re = $queeerp->fetch_assoc();	
  	$itemwarnalist = explode("#",$re['nama_detail']);
	$warnaprod = $itemwarnalist[0];	
?>

<div class="popup-wrap popup-medium">
	<div class="popup-header">
    	<h2 class="f-pb">Edit Produk - <?php echo '#DQ-'.sprintf('%06d',$row['id']).'';?></h2>
    </div><!-- .popup-header -->
    <div class="popup-content">

		<form action="<?php echo $GLOBALS['SITE_URL'];?>do-edit-product-list" method="post" class="general-form" id="addressbook_form">
        	<input type="hidden" name="token" value="<?php echo $row['tokenpay'];?>" />
            <input type="hidden" name="uidlist" value="<?php echo $re['id'];?>" />
            
        	<div class="tc-form-group">
                <div class="form-group" style="width:100%;">
                    <div class="input-wrap">
                    	 <div class="select-style">
                            <select name="idpord" class="idpord">
                                <option value="<?php echo $re['idproduct'];?>"><?php echo $re['name'];?></option>
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
                                   <option value="<?php echo $re['iddetail'];?>" selected="selected"><?php echo $re['sku'];?> - <?php echo $warnaprod;?> - Rp. <?php echo number_format($re['price']);?></option>
                               </select>
                           </div><!-- .select-style -->
                       </div><!-- .input-wrap -->
                       <span class="error_iddetail error_msg"></span>
                 </div><!-- .form-group -->
                 <div class="form-group" style="width:30%;">
                      <label class="f-pb">Qty <span style="font-size:11px; font-weight:normal;">* Minimal 1 item</span></label>
                       <div class="input-wrap">
                          <input type="text" name="qty" value="<?php echo number_format($re['qty']);?>" style="width:80px;" onkeyup="rupiah(this);" />
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