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
			name:{ 
				 required: true
			},
			images:{
				required: true	
			}	
			
	  },
	  messages: {
			name:{ 
				required:"* This field can't be empty."
			},
			images:{
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
            	<h2><a href="member_list.php">Member List</a> &raquo; Edit Member Status</h2>
            </div><!-- .cm-top -->
            <div class="cm-mid">         
            	<?php
					$idmember = $_GET['idmember'];
					$querypage = $db->query("SELECT * FROM `member` WHERE `id`='$idmember'");
					$data = $querypage->fetch_assoc();	
				?>  
                <form action="lib/do-save-member-status.php" method="post" class="general-form" id="media_form" enctype="multipart/form-data">
                    <input type="hidden" name="idmember" value="<?php echo $data['id'];?>" />
                    
                    <table cellspacing="0" cellpadding="0" class="browse-table">
                    
                        <tr>
                            <td class="td1"><label for="dash-uname">Status Member</label></td>
                            <td>
                              <select name="status" id="status">
                                    <option value="Active" <?php if($data['status']=="Active"): echo'selected="selected"'; endif;?>>Active</option>
                                    <option value="InActive" <?php if($data['status']=="InActive"): echo'selected="selected"'; endif;?>>InActive</option>
                                </select>
                            </td>
                        </tr>
 
                         <tr>
                            <td class="td1"><label for="dash-uname">Member Category</label></td>
                            <td>
                              <select name="member_category" id="member_category">
                                    <option value="REGULAR MEMBER" <?php if($data['member_category']=="REGULAR MEMBER"): echo'selected="selected"'; endif;?>>REGULAR MEMBER</option>
                                    <option value="CORPORATE MEMBER" <?php if($data['member_category']=="CORPORATE MEMBER"): echo'selected="selected"'; endif;?>>CORPORATE MEMBER</option>
                                </select>
                            </td>
                        </tr>
                                               
                                                         
                        <tr>
                            <td class="td1" style="padding-top:30px;"></td>
                            <td style="padding-top:30px;">
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