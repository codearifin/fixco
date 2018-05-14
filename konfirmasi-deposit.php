<?php include("header.php"); 
	if(isset($_SESSION['user_token'])==''):
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Deposit</li>
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
                <h1 class="f-pb">Deposit</h1>
                <div class="red-tabbing">
                	<a href="<?php echo $GLOBALS['SITE_URL'];?>deposit-corporate">Deposit Anda</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>history-deposit">History Deposit</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>konfirmasi-deposit" class="active">Top Up Deposit</a>
                </div><!-- .red-tabbing --><br>
              
                <form action="<?php echo $GLOBALS['SITE_URL'];?>do-konfirmasi-deposit" method="post" class="general-form" id="topupdepositUid" enctype="multipart/form-data">
                	<div class="form-group">
                        <label class="f-pb">Nominal Transfer</label>
                        <div class="input-wrap">
                            <input type="text" placeholder="Nominal jumlah deposit yang Anda transfer" name="jumlahdeposit" onKeyUp="rupiah(this)" />
                        </div><!-- .input-wrap -->
                    </div><!-- .form-group -->
                    <div class="tc-form-group">
                        <div class="form-group">
                            <label class="f-pb">Nama Bank</label>
                            <div class="input-wrap">
                                <input type="text" placeholder="Nama bank rekening yang Anda gunakan" name="bankname" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">Nama Pemegang Rekening</label>
                            <div class="input-wrap">
                                <input type="text" placeholder="Nama pada rekening yang digunakan" name="namapemilik" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                    </div><!-- .tc-form-group -->
                    <div class="tc-form-group">
                        <div class="form-group">
                            <label class="f-pb">Tanggal Transfer</label>
                            <div class="input-wrap">
                                <input type="text" placeholder="Tanggal ketika transfer" name="tanggal" class="date-picker" maxlength="100" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">Unggah Bukti Transfer <span>(Max Size: 1MB)</span></label>
                            <div class="input-wrap">
                                <input type="file" name="bukti_trf" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                    </div><!-- .tc-form-group -->
                    <div class="form-button">
                    	<input type="submit" class="btn btn-red no-margin f-psb btn-checkout" value="TOP UP" name="submit" />
                    </div><!-- .form-button -->
                </form>
                
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".menu2").addClass("active");	
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