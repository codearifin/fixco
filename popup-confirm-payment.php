<?php
@session_start();
include('config/connection.php');
include "include/function.php";
include "config/myconfig.php";
?>

<div class="popup-wrap popup-medium">
	<div class="popup-header">
    	<h2 class="f-pb">Confirm Payment</h2>
    </div><!-- .popup-header -->
    <div class="popup-content">
        <form action="<?php echo $GLOBALS['SITE_URL'];?>do-payment-confirmation" method="post" class="general-form" id="konfirm_payformpop" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $_SERVER["HTTP_REFERER"];?>" name="pageURL" />
            <div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">Order ID</label>
                    <div class="input-wrap">
                        <input type="text" placeholder="Order ID Anda, E.g: 000008" name="idorder" maxlength="10" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="f-pb">Nominal Transfer</label>
                    <div class="input-wrap">
                        <input type="text" placeholder="Nominal uang yang Anda transfer" onKeyUp="formatAngka(this)" name="nominal" maxlength="14"  />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
            
            <div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">Nama Bank</label>
                    <div class="input-wrap">
                        <input type="text" placeholder="Nama bank rekening yang Anda gunakan"  name="nama_bank" maxlength="200"/>
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="f-pb">Nama Pemegang Rekening</label>
                    <div class="input-wrap">
                        <input type="text" placeholder="Nama pada rekening yang digunakan" name="atas_nama" maxlength="200" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
            <div class="tc-form-group">
                 <div class="form-group">
                    <label class="f-pb">No Rekening</label>
                    <div class="input-wrap">
                        <input type="text" placeholder="No. rekening yang digunakan" name="norek" maxlength="100" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->           
                <div class="form-group">
                    <label class="f-pb">Tanggal Transfer</label>
                    <div class="input-wrap">
                        <input type="text" placeholder="Tanggal ketika transfer" name="tanggal" class="date-picker" maxlength="100" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->
            <div class="tc-form-group">
                <div class="form-group">
                    <label class="f-pb">Unggah Bukti Transfer <span>(Max Size: 1MB)</span></label>
                    <div class="input-wrap">
                        <input type="file" name="bukti_trf" />
                    </div><!-- .input-wrap -->
                </div><!-- .form-group -->
            </div><!-- .tc-form-group -->               
            
            <div class="form-button">
                <input type="submit" class="btn btn-red no-margin f-psb btn-checkout" value="KONFIRMASI" name="submit" />
            </div><!-- .form-button -->
        </form>
    </div><!-- .popup-content -->
</div><!-- .popup-wrap -->

<!-- RNT -->
<link href="<?php echo $GLOBALS['SITE_URL'];?>js/themes/base/jquery.ui.all.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $GLOBALS['SITE_URL'];?>js/calender/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['SITE_URL'];?>js/calender/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['SITE_URL'];?>js/calender/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['SITE_URL'];?>js/calender/jquery.ui.datepicker-id.min.js"></script>

<script type="text/javascript">
$(function() {
	$('.date-picker').datepicker({
		dateFormat: "dd/mm/yy"
	});
});
</script>

<script type="text/javascript">
 $(document).ready(function() {
 
 $("#konfirm_payformpop").validate({
			rules: {
				nama_bank:{
					required: true,
					 addressval: true
				},
				norek:{ 
					 required: true,
					 addressval: true
				},
				jumlah_transfer:{ 
					 required: true,
					 addressval: true
				},
				atas_nama:{
					required: true,
					 addressval: true
				},
				tanggal:{
					required: true
				},
				idorder:{
					required: true
				},
				nominal:{
					required: true	
				},
				transferke:{
					required: true	
				}									
				
			},
			messages: {
				nama_bank:{
					required:"* This field can't be empty.",
					addressval:"* This field cannot insert special character , e.g: *($#&"
				},
				norek:{ 
					required:"* This field can't be empty.",
				    addressval:"* This field cannot insert special character , e.g: *($#&"
				},
				jumlah_transfer:{ 
					required:"* This field can't be empty.",
					addressval:"* This field cannot insert special character , e.g: *($#&"
				},
				atas_nama:{
					required:"* This field can't be empty.",
					addressval:"* This field cannot insert special character , e.g: *($#&"
				},
				tanggal:{
					required:"* This field can't be empty."
				},
				idorder:{
					required:"* This field can't be empty."
				},
				nominal:{
					required:"* This field can't be empty."
				},
				transferke:{
					required:"* This field can't be empty."	
				}
				
			}
		});


 });
</script>