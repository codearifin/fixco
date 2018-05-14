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
			memberid:{ 
				 required: true
			},
			datepost:{
				required: true	
			},
			jumlah_mutasi:{
				required: true	
			}	
			
	  },
	  messages: {
			memberid:{ 
				required:"* This field can't be empty."
			},
			datepost:{
				required:"* This field can't be empty."
			},
			jumlah_mutasi:{
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
	
	$('.date-pick').datepicker({ 'format': 'yyyy/mm/dd', 'autoclose': true });

	  
});
</script>
    
    <div id="cms-content" class="clearfix">
    	<?php show_left_menu($theMenu); ?>
        <div class="cms-main-content right">
        	<div class="cm-top">
            	<h2><a href="commission_withdrawal.php">Commission Withdrawal</a> &raquo; Add Mutasi Commission</h2>
            </div><!-- .cm-top -->
            <div class="cm-mid">         
            	
                
                <form action="lib/do_save_datamutasi_commission_withdrawal.php" method="post" class="general-form" id="media_form" enctype="multipart/form-data">
                    <table cellspacing="0" cellpadding="0" class="browse-table">
                    
                        <tr>
                            <td class="td1"><label for="category_item">Select Member</label></td>
                            <td>
                               <select name="memberid">
                                     <option value="" selected="selected">Select Member</option>
									<?php 
                                        $ressub = $db->query("SELECT * FROM `member` WHERE `member_category` = 'REGULAR MEMBER' ORDER BY `name` ASC");
                                        while($rowsub = $ressub->fetch_assoc()):
                                            echo'<option value = "'.$rowsub['id'].'">'.$rowsub['name'].' '.$rowsub['lastname'].'</option>';
                                        endwhile;
                                    ?>	
                                </select> 
                            </td>
                       </tr>
                       
                       <tr>
                            <td class="td1"><label for="dash-datepost">Posting Date</label></td>
                            <td>
                                <input id="dash-datepost" type="text" name="datepost" value="" class="date-pick" />
                            </td>
                        </tr>                      

                       <tr>
                            <td class="td1"><label for="">Jumlah Mutasi<br />Pemotongan</label></td>
                            <td>
                                <input type="text" name="jumlah_mutasi" onkeyup="rupiah(this)" value="" style="width:150px;" />
                            </td>
                       </tr>   
                        
                       <tr>
                            <td class="td1"><label for="">Description</label></td>
                            <td>
                               <textarea name="description"></textarea>
                            </td>
                       </tr>                           
                                             
                                                
                        <tr>
                            <td class="td1" style="padding-top:10px;"></td>
                            <td style="padding-top:10px;">
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