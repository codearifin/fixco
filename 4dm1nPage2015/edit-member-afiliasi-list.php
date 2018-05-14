<?php include("header.php"); ?>

<?php include("function_rnt.php");?>

<script>
$(document).ready(function() {
  $("#main-nav li.top-<?php echo $theMenu['url']; ?>").find("a").addClass("active");
  <?php if(isset($theSubMenu)) { ?>
  $(".side-menu li.left-<?php echo $theSubMenu['url']; ?>").find("a").addClass("current");
  <?php }?>
 
	// jquery validate
	$('form#media_form').validate({
	  rules: {
			komisi_persen:{ 
				 required: true
			}	
			
	  },
	  messages: {
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
            	<h2><a href="affiliate_memberlist.php">Affiliate Member</a> &raquo; Edit Data</h2>
            </div><!-- .cm-top -->
            <div class="cm-mid">         
            	<?php
					$iddata = $_GET['iddata'];
					$querypage = $db->query("SELECT * FROM `affiliate_memberid` WHERE `id`='$iddata'");
					$data = $querypage->fetch_assoc();	
				?>  
                <form action="lib/do_save_affiliate_memberid.php" method="post" class="general-form" id="media_form" enctype="multipart/form-data">
                    <input type="hidden" name="iddata" value="<?php echo $data['id'];?>" />
                    
                    <table cellspacing="0" cellpadding="0" class="browse-table">

                        <tr>
                            <td class="td1"><label for="dash-uname">Member</label></td>
                            <td>
                              <input type="text" name="memberdis" disabled="disabled" value="<?php echo getnamegenal($data['member_id'],"member","name");?> <?php echo getnamegenal($data['member_id'],"member","lastname");?>" />
                            </td>
                        </tr>   
                                            
                        <tr>
                            <td class="td1"><label for="dash-uname">Commission</label></td>
                            <td>
                              <input type="text" name="komisi_persen" value="<?php echo $data['komisi_persen'];?>" style="width:50px;" /> &nbsp;&nbsp;%
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