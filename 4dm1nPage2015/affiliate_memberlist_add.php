<?php include("header.php"); ?>

<script>
$(document).ready(function() {
  $("#main-nav li.top-<?php echo $theMenu['url']; ?>").find("a").addClass("active");
  <?php if(isset($theSubMenu)) { ?>
  $(".side-menu li.left-<?php echo $theSubMenu['url']; ?>").find("a").addClass("current");
  <?php }?>
 
	// jquery validate
	$('form#media_form').validate({
	  rules: {
			anggotanya_member_id:{ 
				 required: true
			},
			member_id:{
				required: true	
			},
			komisi_persen:{
				required: true	
			}	
			
	  },
	  messages: {
			anggotanya_member_id:{ 
				required:"* This field can't be empty."
			},
			member_id:{
				required:"* This field can't be empty."
			},
			komisi_persen:{
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
            	<h2><a href="affiliate_memberlist.php">Affiliate Member</a> &raquo; New Affiliate</h2>
            </div><!-- .cm-top -->
            <div class="cm-mid">         
            	<form action="lib/do_savedata_affiliate_memberlist.php" method="post" class="general-form" id="media_form" enctype="multipart/form-data">
                    <table cellspacing="0" cellpadding="0" class="browse-table">
                    
                        <tr>
                            <td class="td1"><label for="category_item">Select Member</label></td>
                            <td>
                               <select name="anggotanya_member_id">
                                     <option value="" selected="selected">Select Member</option>
									<?php 
                                        $ressub = $db->query("SELECT * FROM `member` WHERE `status` = 'Active' ORDER BY `name` ASC");
                                        while($rowsub = $ressub->fetch_assoc()):
                                            echo'<option value = "'.$rowsub['id'].'">'.$rowsub['name'].' '.$rowsub['lastname'].'</option>';
                                        endwhile;
                                    ?>	
                                </select> 
                            </td>
                       </tr>
                              
                              
                         <tr>
                            <td class="td1"><label for="category_item">Affiliate To</label></td>
                            <td>
                               <select name="member_id">
                                     <option value="" selected="selected">Select Member</option>
									<?php 
                                        $ressub2 = $db->query("SELECT * FROM `member` WHERE `status` = 'Active' and `member_category` = 'REGULAR MEMBER' ORDER BY `name` ASC");
                                        while($rowsub2 = $ressub2->fetch_assoc()):
                                            echo'<option value = "'.$rowsub2['id'].'">'.$rowsub2['name'].' '.$rowsub2['lastname'].'</option>';
                                        endwhile;
                                    ?>	
                                </select> 
                            </td>
                       </tr>                             
                              
                         <tr>
                            <td class="td1"><label for="dash-uname">Commission</label></td>
                            <td>
                              <input type="text" name="komisi_persen" value="" style="width:50px;" /> &nbsp;&nbsp;%
                            </td>
                        </tr>                                
                                                
                        <tr>
                            <td class="td1"></td>
                            <td>
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