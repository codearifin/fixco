<?php
@session_start();
include('config/connection.php');
include "include/function.php";
include "config/myconfig.php";
?>

<div class="popup-wrap popup-medium">
	<div class="popup-header">
    	<h2 class="f-pb">Tambah Alamat</h2>
    </div><!-- .popup-header -->
    <div class="popup-content">
        <form action="<?php echo $GLOBALS['SITE_URL'];?>do-add-new-addres" method="post" class="general-form" id="addressbook_form">
        	<div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">Nama Penerima</label>
                    <div class="input-wrap">
                        <input type="text" placeholder="Nama lengkap penerima" name="name" value="" maxlength="200" />
                        <span></span>
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="f-pb">Telepon Seluler</label>
                    <div class="input-wrap">
                        <input type="text" placeholder="Nomor telepon penerima" name="mobilephone" maxlength="20"  />
                        <span></span>
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
            <div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">Provinsi</label>
                    <div class="input-wrap">
                        <div class="select-style">
                            <select name="province" class="province">
                                <option  value="" selected="selected">- Pilih Provinsi -</option>
                                <?php getpropinsilist();?>
                            </select>
                        </div><!-- .select-style -->
                    </div><!-- .input-wrap -->
                    <span class="error_province error_msg"></span>
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="f-pb">Kabupaten</label>
                    <div class="input-wrap">
                        <div class="select-style">
                        <select name="kabupaten" class="kabupaten">
                              <option value="" selected="selected">- Pilih Kabupaten -</option>
                         </select>
                        </div><!-- .select-style -->
                    </div><!-- .input-wrap -->
                    <span class="error_kabupaten error_msg"></span>   
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
            <div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">Kota</label>
                    <div class="input-wrap">
                        <div class="select-style">
                            <select name="city" class="city">
                                <option value="" selected="selected">- Pilih Kota -</option>
                            </select>
                        </div><!-- .select-style -->
                    </div><!-- .input-wrap -->
                    <span class="error_city error_msg"></span>   
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="f-pb">Kode Pos</label>
                    <div class="input-wrap">
                        <input type="text" placeholder="Kode pos alamat Anda" name="kodepos" />
                        <span></span>
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
            <div class="form-group">
                <label class="f-psb">Alamat Lengkap</label>
                <div class="input-wrap">
                    <textarea placeholder="Alamat lengkap Anda" name="address"></textarea>
                    <span></span>
                </div><!-- .input-wrap -->
            </div><!-- .form-group -->
            <div class="form-button">
            	<input type="submit" class="btn btn-red no-margin min-190" value="SIMPAN" name="submit" />
            </div><!-- .form-button -->
        </form>
    </div><!-- .popup-content -->
</div><!-- .popup-wrap -->

 		<script type="text/javascript">
                $(document).ready(function() {
                    $(".province").change(function(){				   	
                         var propinsi = $(this).val();			
                         $.post("include/get_kabupaten.php", {"propinsi": propinsi},
                         function(data){
                            $(".kabupaten").html(data); 
                        });
                    }); 
                               
                    $(".kabupaten").change(function(){
                         var kabupaten = $(this).val();			
                         $.post("include/get_kotalist.php", {"kabupaten": kabupaten},
                         function(data){
                            $(".city").html(data); 
                         });
                    }); 

					
					$("#addressbook_form").validate({
										rules: {
											name:{
												required: true,
												abcval:true
											},
											lastname:{
												abcval:true
											},
											email:{
												required: true,
												email:true
											},
											phone:{
												angkaval: true		
											},						
											mobilephone:{
												required: true,
												angkaval: true		
											},
											province:{ 
												 required: true
											},
											kabupaten:{ 
												 required: true
											},
											city:{
												 required: true	
											},
											address:{
												 required: true,
												 addressval: true	
											}
										
										
										},

										errorPlacement: function(error, element) {  
											
											if (element.is(".province")) { 
												$(".error_province").html(error);  
											}if (element.is(".kabupaten")) { 
												$(".error_kabupaten").html(error); 
											}if (element.is(".city")) { 
												$(".error_city").html(error);  												 
											}else { element.next('span').html(error) }
										},

										
										messages: {
											name:{
												required:"* This field can't be empty.",
												abcval:"* This field contain alphabet only."
											},
											lastname:{
												abcval:"* This field contain alphabet only."
											},
											email:{
												required:"* This field can't be empty.",
												email:"* Please enter valid email address."
											},
											phone:{
												angkaval:"* This field only insert with character 0-9 + "		
											},						
											mobilephone:{
												required:"* This field can't be empty.",
												angkaval:"* This field only insert with character 0-9 + "		
											},						
											province:{ 
												 required:"* This field can't be empty."
											},
											kabupaten:{ 
												 required:"* This field can't be empty."
											},
											city:{
												 required:"* This field can't be empty."
											},
											address:{
												required:"* This field can't be empty.",
												addressval:"* This field cannot insert special character , e.g: *($#&"
											}						
											
										}
									});	
											
                
                });
            </script>