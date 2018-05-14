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

			jumlah_voucher:{ 

				 required: true

			},

			datepost:{

				required: true	

			},

			datepost2:{

				required: true	

			},

			price:{

				required: true	

			}				

			

	  },

	  messages: {



			jumlah_voucher:{ 

				required:"* This field can't be empty."

			},

			datepost:{

				required:"* This field can't be empty."

			},

			datepost2:{

				required:"* This field can't be empty."

			},

			price:{

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

            	<h2><a href="view.php?menu=member&submenu=voucher_online">Voucher Online</a> &raquo; New Generate Voucher</h2>

            </div><!-- .cm-top -->

            <div class="cm-mid">         



            	<form action="lib/do_save_voucheronline2_multiple.php" method="post" class="general-form" id="voucher_doaddform" enctype="multipart/form-data">

                    <input type="hidden" name="stmember" value="1" />

                    

                    <table cellspacing="0" cellpadding="0" class="browse-table">



                        

                        <tr>

                            <td class="td1"><label for="">Jumlah Voucher</label></td>

                            <td>

                                <input type="text" name="jumlah_voucher" value="1" style="width:40px;" />

                            </td>

                        </tr>

                        

                        <tr>

                            <td class="td1"><label for="dash-datepost">Start Date</label></td>

                            <td>

                                <input id="dash-datepost" type="text" name="datepost" value="" class="date-pick" />

                            </td>

                        </tr>



                         <tr>

                            <td class="td1"><label for="dash-datepost">End Date</label></td>

                            <td>

                                <input id="dash-datepost2" type="text" name="datepost2" value="" class="date-pick"  />

                            </td>

                        </tr>                                       



                        <tr>

                            <td class="td1"><label for="dash-urel">Diskon Type</label></td>

                            <td>

                                <select name="diskon_typeid" class="diskon_typeid">

                                    <option value="AMOUNT">AMOUNT</option>

                                    <option value="PERCENT">PERCENT</option>
									
                                    <option value="BONUS PRODUCT">BONUS PRODUCT</option>

                                </select>

                            </td>

                        </tr>     

                                

                                

                         <tr>

                            <td class="td1"><label for="dash-urel">Discount Value</label></td>

                            <td>

                               <input name="price" id="price" type="text" >

                            </td>

                        </tr>                                

                      

 

                        <tr>

                            <td class="td1"><label for="dash-minorder">Min Transaksi Price</label></td>

                            <td>

                               <input name="minbelanja" id="dash-minorder" type="text" maxlength="10" onkeyup="rupiah(this)" style="width:100px;" value="0" >

                            </td>

                        </tr>  



                         <tr>

                            <td class="td1"><label for="dash-makbelanja">Mak Transaksi Price </label></td>

                            <td>

                               <input name="makbelanja" id="dash-makbelanja" type="text" maxlength="10" onkeyup="rupiah(this)" style="width:100px;" value="0" >

                            </td>

                        </tr> 

                        

                       <tr>

                            <td class="td1"><label for="min_qty_beli">Mak Item Buy</small></label></td>

                            <td>

                              <input name="min_qty_beli" type="text" maxlength="8" value="1" style="width:40px;">&nbsp;&nbsp;item

                            </td>

                       </tr>     

                       



                       <tr>

                            <td class="td1"><label for="min_user_byuser">Status Item</label></td>

                            <td>

                                <select name="status_item">

                                    <option value="ALL PRODUCT">ALL PRODUCT</option>

                                    <option value="ONLY CATEGORY">ONLY CATEGORY</option>

                                    <option value="ONLY PRODUCT">ONLY PRODUCT</option>
                                    
                                    <option value="BRAND">BRAND</option>

                                </select>

                            </td>

                       </tr>   



                       <tr>

                            <td class="td1"><label for="product_item">Product Item</label></td>

                            <td>

                                <select name="product_item">

                                    <option value="" selected="selected">Select Product</option>

									<?php 

										$qu = $db->query("SELECT `id`,`name` FROM `product` ORDER BY `id` ASC");

										while($re2 = $qu->fetch_assoc()):

											echo'<option value="'.$re2['id'].'">'.$re2['name'].'</option>';

										endwhile;

									?>

                                </select>

                            </td>

                       </tr>

                       

                        <tr>

                            <td class="td1"><label for="category_item">Category Item</label></td>

                            <td>

                                <select name="category_item" id="category">

                                	<option value="" selected="selected">Select Category</option>

                                    <?php 

										$qucate = $db->query("SELECT `id`,`name` FROM `category` ORDER BY `id` ASC");

										while($re = $qucate->fetch_assoc()):

											echo'<option value="'.$re['id'].'">'.$re['name'].'</option>';

										endwhile;

									?>

                                </select>

                            </td>

                       </tr>                       
                                              

                        <tr>

                            <td class="td1"><label for="stock">Stock</small></label></td>

                            <td>

                              <input name="stock" type="text" maxlength="8" value="1" style="width:40px;">&nbsp;&nbsp;item

                            </td>

                       </tr>                       

                        <tr>

                            <td class="td1"><label for="dash-urel">Discount Status</label></td>

                            <td>

                                <select name="discount_status" class="discount_status">

                                    <option value="Grand Total">Grand Total</option>

                                    <option value="Per Item">Per Item</option>

                                </select>

                            </td>

                        </tr>  
                        
                        
                        <td class="td1"> Publish </td>
                    	<td>
                    
                                    <input type="radio" name="publish" value="1" checked="checked" /> Yes &nbsp;&nbsp;
                    
                                    <input type="radio" name="publish" value="0" /> No
                    
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