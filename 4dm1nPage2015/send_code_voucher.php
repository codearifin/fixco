<?php include("header.php"); ?>
<script>
$(document).ready(function() {
  $("#main-nav li.top-<?php echo $theMenu['url']; ?>").find("a").addClass("active");
  <?php if(isset($theSubMenu)) { ?>
  $(".side-menu li.left-<?php echo $theSubMenu['url']; ?>").find("a").addClass("current");
  <?php }?>
 
	// jquery validate
	$('form#voucher_doaddform').validate({
	  rules: {
			product_item:{ 
				 required: true
			},
			memberid:{
				required: true	
			}	
			
	  },
	  messages: {
			product_item:{ 
				required:"* This field can't be empty."
			},
			memberid:{
				required:"* This field can't be empty."
			}
			
			
	  },
	  errorPlacement: function(error, element){
		error.insertAfter(element);
	  },
	   submitHandler:function(form){
		   	form.submit(); 	  
	   }
	});	

	  
});
</script>
    
    <div id="cms-content" class="clearfix">
    	<?php show_left_menu($theMenu); ?>
        <div class="cms-main-content right">
        	<div class="cm-top">
            	<h2><a href="view.php?menu=member&submenu=voucher_online">Voucher Online</a> &raquo; Send Voucher Code To Member</h2>
            </div><!-- .cm-top -->
            <div class="cm-mid">         

            	<form action="lib/send_voucher_to_member_email.php" method="post" class="general-form" id="voucher_doaddform" enctype="multipart/form-data">
                    
                    <table cellspacing="0" cellpadding="0" class="browse-table"> 

                       <tr>
                            <td class="td1"><label for="product_item">Select Voucher Code</label></td>
                            <td>
                                <select name="product_item">
                                    <option value="" selected="selected">Select Voucher</option>
									<?php 
										$qu = $db->query("SELECT `id`,`voucher_code`,`stock` FROM `voucher_online` WHERE `stock` > 0 ORDER BY `id` ASC");
										while($re2 = $qu->fetch_assoc()):
											echo'<option value="'.$re2['id'].'">'.$re2['voucher_code'].' - Stock :  '.number_format($re2['stock']).'</option>';
										endwhile;
									?>
                                </select>
                            </td>
                       </tr>
                       
                        <tr>
                            <td class="td1"><label for="category_item">Select Member</label></td>
                            <td>
                               <select name="memberid">
                                     <option value="" selected="selected">Select Member</option>
									<?php 
                                        $ressub = $db->query("SELECT * FROM `member` WHERE `status`='Active' ORDER BY `name` ASC");
                                        while($rowsub = $ressub->fetch_assoc()):
                                            echo'<option value = "'.$rowsub['id'].'">'.$rowsub['name'].' '.$rowsub['lastname'].' ['.$rowsub['email'].']</option>';
                                        endwhile;
                                    ?>	
                                </select> 
                            </td>
                       </tr>                       
                                                                                        
                         <tr>
                            <td class="td1"></td>
                            <td style="padding-top:20px;">
                                <div class="btn-area clearfix">
                                    <input type="submit" value="SAVE" class="submit-btn left" name="submit" />
                                    <input type="reset" value="RESET" class="delete-btn left" />
                                </div><!-- .btn-area -->
                            </td>
                        </tr>
                    </table>
                </form>
                    
            </div><!-- .cm-mid -->
        </div><!-- .cms-main-content -->
    </div><!-- #cms-content -->
    
<?php include("footer.php"); ?>