<?php include("header.php"); 
	if(isset($_SESSION['user_token'])==''):
		$_SESSION['error_msg']='login-first';
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;

	//member data
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
	$query = $db->query("SELECT `id` FROM `member` WHERE `tokenmember`='$Usertokenid'");
	$res = $query->fetch_assoc();
	$idmember = $res['id'];	
	
	//filter ordder
	if(isset($_SESSION['sort_filtertahunredeem'])): $sort_filtertahun = $_SESSION['sort_filtertahunredeem']; else: $sort_filtertahun = ""; endif;		
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Point Reward</li>
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
                         <?php include("side_member.php");?>
                    </ul><!-- .ts-menu -->
                </div><!-- .ts-child -->
            </aside><!-- #product-sidebar -->
            <div class="template-wrap">
                <h1 class="f-pb">Point Reward</h1>
                <div class="red-tabbing">
                	<a href="<?php echo $GLOBALS['SITE_URL'];?>point-reward">History Point Reward</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>redeem-point">Redeem Point</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>redeem-history" class="active">Redeem History</a>
                </div><!-- .red-tabbing -->
                
                <div class="table-sort">
                	<label>Sortir waktu:</label>
                    <div class="input-wrap">
                    	<div class="select-style">
							<?php
                                $tanggalNow = date("Y"); 
                                $tanggalBulanNow = date("m"); 
                            ?>        
                             <select class="generalsortprodlist">
                                    <option value="" selected="selected">Tampilkan Semua</option>
                                    <option value="<?php gettanggalsort2($tanggalNow,$tanggalBulanNow,0);?>" <?php gettanggalsort3($tanggalNow,$tanggalBulanNow,0,$sort_filtertahun);?>><?php gettanggalsort($tanggalNow,$tanggalBulanNow,0);?></option>
                                    <option value="<?php gettanggalsort2($tanggalNow,$tanggalBulanNow,1);?>" <?php gettanggalsort3($tanggalNow,$tanggalBulanNow,1,$sort_filtertahun);?>><?php gettanggalsort($tanggalNow,$tanggalBulanNow,1);?></option>
                                    <option value="<?php gettanggalsort2($tanggalNow,$tanggalBulanNow,2);?>" <?php gettanggalsort3($tanggalNow,$tanggalBulanNow,2,$sort_filtertahun);?>><?php gettanggalsort($tanggalNow,$tanggalBulanNow,2);?></option>
                                    <option value="<?php gettanggalsort2($tanggalNow,$tanggalBulanNow,3);?>" <?php gettanggalsort3($tanggalNow,$tanggalBulanNow,3,$sort_filtertahun);?>><?php gettanggalsort($tanggalNow,$tanggalBulanNow,3);?></option>
                                    <option value="<?php gettanggalsort2($tanggalNow,$tanggalBulanNow,4);?>" <?php gettanggalsort3($tanggalNow,$tanggalBulanNow,4,$sort_filtertahun);?>><?php gettanggalsort($tanggalNow,$tanggalBulanNow,4);?></option>
                                    <option value="<?php gettanggalsort2($tanggalNow,$tanggalBulanNow,5);?>" <?php gettanggalsort3($tanggalNow,$tanggalBulanNow,5,$sort_filtertahun);?>><?php gettanggalsort($tanggalNow,$tanggalBulanNow,5);?></option>
                             </select>
                             <input type="hidden" name="name_sort" class="name_sortuid" value="sort_filtertahunredeem" /> 
                        </div><!-- .select-style -->
                    </div><!-- .input-wrap -->
                </div><!-- .table-sort -->
                
                <div class="order-table">
                	<div class="ot-header f-pb">
                    	<div class="oth-1">
                        	Redeem ID
                        </div><!-- .oth-1 -->
                        <div class="oth-2">
                        	<div class="oth-2-2">
                            	Status
                            </div><!-- .oth-2-2 -->
                        </div><!-- .oth-2 -->
                    </div><!-- .ot-header -->
                    <div class="ot-body">
                    	<?php getorderlistredeem($idmember,$sort_filtertahun);?>                        
                    </div><!-- .ot-body -->
                </div><!-- .order-table -->
                
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script type="text/javascript">
		$(document).ready(function() {
		  $(".menu10").addClass("active");	
	    });	
</script>

</body>
</html>