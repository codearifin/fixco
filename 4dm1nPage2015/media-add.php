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
            	<h2><a href="media.php">Media</a> &raquo; New Media</h2>
            </div><!-- .cm-top -->
            <div class="cm-mid">         
            	<form action="lib/do_savemedia.php" method="post" class="general-form" id="media_form" enctype="multipart/form-data">
                    <table cellspacing="0" cellpadding="0" class="browse-table">
                    
                        <tr>
                            <td class="td1"><label for="dash-uname">Title</label></td>
                            <td><input id="dash-uname" type="text" name="name" value="" maxlength="200" /></td>
                        </tr>
                        
                     
                        <tr>
                            <td class="td1">Upload File</td>
                            <td>
                                <input type="file" name="images" />
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