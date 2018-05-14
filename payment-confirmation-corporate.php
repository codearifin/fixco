<?php include("header.php"); ?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Payment Confirmation</li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section">
	<div class="container">
    	<div class="row">
        	<aside id="template-sidebar" class="ts-ads-wrap">
                <div class="ts-child">
                	<h3 class="f-pb">AKUN SAYA</h3>
                    <ul class="ts-menu">
                       <?php include("side_member_corporate.php");?>
                    </ul><!-- .ts-menu -->
                </div><!-- .ts-child -->
            </aside><!-- #product-sidebar -->
            <div class="template-wrap">
                <h1 class="f-pb">Payment Confirmation</h1>
                <form action="<?php echo $GLOBALS['SITE_URL'];?>do-payment-confirmation" method="post" class="general-form" id="konfirm_payform" enctype="multipart/form-data">
                	<input type="hidden" value="<?php echo $GLOBALS['SITE_URL'];?>payment-confirmation-corporate" name="pageURL" />
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
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script>
$(document).ready(function() {
	$(".menu4").addClass("active");		
});
</script>

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

</body>
</html>