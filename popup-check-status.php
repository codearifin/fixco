<?php
	@session_start();
	include('config/connection.php');
	include "include/function.php";
	include "config/myconfig.php";
?>
<script type="text/javascript">

	//voucher mizuno
	$('form#trackingorder_idform').validate({
	  rules: {
			orderuid:{ 
				 required: true
			}	
			
	  },
	  messages: {
			orderuid:{ 
				required:"* This field can't be empty."
			}	
	  },
	  errorPlacement: function(error, element){
		error.insertAfter(element);
	  },
	   submitHandler:function(form){
			 var url_siteid = $(".url_siteid").val();	
			 var orderid = $(".orderuid").val();
			 
			 $.post(""+url_siteid+"include/do_track_order.php", {"orderid": orderid},
			 function(data){
				$(".hasil_trackorder").html(data);
			 });	 

	   }
	});	
	
</script>

<div class="popup-wrap popup-small">
	<div class="popup-header">
    	<h2 class="f-pb">Status Pesanan</h2>
    </div><!-- .popup-header -->
    <div class="popup-content">
    	<p>Masukan Order ID Anda untuk mengetahui status pesanan Anda.</p>
        <form action="" method="post" class="general-form" id="trackingorder_idform">
        	<div class="form-group">
            	<label class="f-pb">Order ID</label>
            	<div class="input-wrap">
                	<input type="text" placeholder="Silakan masukkan Order ID Anda" name="orderuid" class="orderuid"  />
                </div><!-- .input-wrap -->
            </div><!-- .form-group -->
            <div class="form-button">
            	<input type="submit" class="btn btn-red no-margin min-190" value="LIHAT" name="submit" />
            </div><!-- .form-button -->
        </form>
        <div class="hasil_trackorder"></div>
        
    </div><!-- .popup-content -->
</div><!-- .popup-wrap -->