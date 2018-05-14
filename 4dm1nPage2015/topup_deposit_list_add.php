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
			nominal_trf:{
				required: true	
			},
			namabank:{
				required: true
			},
			namapemilik:{
				required: true
			},
			tgltrf:{
				required: true
			}
			
			
	  },
	  messages: {
			memberid:{ 
				 required:"* This field can't be empty."
			},
			nominal_trf:{
				required:"* This field can't be empty."
			},
			namabank:{
				required:"* This field can't be empty."
			},
			namapemilik:{
				required:"* This field can't be empty."
			},
			tgltrf:{
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
            	<h2><a href="topup_deposit_list.php">Topup Deposit</a> &raquo; New Topup Deposit</h2>
            </div><!-- .cm-top -->
            <div class="cm-mid">         
            	<form action="eccomerce/do_save_topup_deposit_list.php" method="post" class="general-form" id="media_form" enctype="multipart/form-data">
                    <table cellspacing="0" cellpadding="0" class="browse-table">
                    
                        <tr>
                            <td class="td1"><label for="category_item">Select Member</label></td>
                            <td>
                               <select name="memberid">
                                     <option value="" selected="selected">Select Member</option>
									<?php 
                                        $ressub = $db->query("SELECT * FROM `member` WHERE `status` = 'Active' and `member_category` = 'CORPORATE MEMBER' ORDER BY `name` ASC");
                                        while($rowsub = $ressub->fetch_assoc()):
                                            echo'<option value = "'.$rowsub['id'].'">'.$rowsub['name'].' '.$rowsub['lastname'].'</option>';
                                        endwhile;
                                    ?>	
                                </select> 
                            </td>
                       </tr>
 
                         <tr>
                            <td class="td1"><label for="category_item">Nominal Topup</label></td>
                            <td>
                             	<input type="text" name="nominal_trf" onkeyup="rupiah(this)" />
                            </td>
                       </tr>

                         <tr>
                            <td class="td1"><label for="category_item">Dari Bank</label></td>
                            <td>
                             	<input type="text" name="namabank" placeholder="e.g BCA" />
                            </td>
                       </tr>
 
                          <tr>
                            <td class="td1"><label for="category_item">Nama Pemegang Rekening</label></td>
                            <td>
                             	<input type="text" name="namapemilik" />
                            </td>
                       </tr>
                                             

                       <tr>
                            <td class="td1"><label for="category_item">Tanggal Trf</label></td>
                            <td>
                             	<input type="text" name="tgltrf" class="date-pick" />
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