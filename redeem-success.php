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
	
	if(isset($_GET['tokencode'])): $tokencode = replaceUrel($_GET['tokencode']); else: $tokencode = ''; endif;
	$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d %M %Y, %H:%i:%s') as tgl FROM `order_header_redeemlist` WHERE `idmember`='$idmember' and `tokenpay`='$tokencode'");
	$row = $que->fetch_assoc();	
	$nameprod = $row['sku_product'].' - '.$row['name'];
	
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
                <h1 class="f-pb">Redeem Success!</h1>
                <div class="status-corporate status-full">
                	<h2 class="f-psb">Terima Kasih Telah Melakukan Redeem Point Reward.</h2><br>
                    <p>Berikut adalah ID redeem Anda:<br>
                    <span class="f-pb f-red"><?php echo '#'.sprintf('%06d',$row['id']).'';?></span>
                    </p>
                   <?php pages(12);?>
                </div><!-- .status-corporate -->
                
                <div class="history-deposit">
                	<table class="clean-table" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<td><strong>Tanggal</strong></td>
                            <td>:</td>
                            <td><?php echo $row['tgl'];?></td>
                        </tr>
                        <tr>
                        	<td><strong>Redeem ID</strong></td>
                            <td>:</td>
                            <td><?php echo '#'.sprintf('%06d',$row['id']).'';?></td>
                        </tr>
                        <tr>
                        	<td><strong>Nama Penerima</strong></td>
                            <td>:</td>
                            <td><?php echo $row['nama_penerima'];?></td>
                        </tr>
                        <tr>
                        	<td><strong>Alamat</strong></td>
                            <td>:</td>
                            <td>
                                   <?php
                                        echo $row['address_penerima'].'<br />';
                                        echo $row['kota_penerima'].', '. $row['kabupaten_penerima'].'<br />';
                                        echo $row['provinsi_penerima'].' - '.$row['country_penerima'].' '.$row['kodepos'];				
                                   ?>   
                            </td>
                        </tr>
                        <tr>
                        	<td><strong>Telepon Seluler</strong></td>
                            <td>:</td>
                            <td><?php echo $row['phone_penerima'];?></td>
                        </tr>
                    </table>
                    <br>
                    <div class="table-wrap">
                        <table cellspacing="0" cellpadding="0" class="blue-table">
                            <thead>
                                <tr>
                                    <th colspan="2">Ringkasan Redeem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div class="ringkasan-wrap">
                                            <div class="rw-1">
												<?php echo'<a href="'.$GLOBALS['SITE_URL'].'product-redeem-detail/'.replace($row['name']).'/'.$row['idproduct'].'" class="nuke-fancied2">'.getimageslistredeem($row['idproduct'],$nameprod).'</a>';?>
                                            </div>
                                            <div class="rw-2-wrap">
                                                <div class="rw-2">
                                                    <h3 class="f-pb">
                                                    	<?php echo'<a href="'.$GLOBALS['SITE_URL'].'product-redeem-detail/'.replace($row['name']).'/'.$row['idproduct'].'" class="nuke-fancied2">'.$nameprod.'</a>';?>
                                                    </h3>
                                                    <p class="f-1200-14px">Point yang dibutuhkan : <?php echo number_format($row['orderamount']);?> point</p>
                                                </div><!-- .rw-2 -->
                                                <div class="rw-3 f-red f-pb">
                                                    <?php echo number_format($row['orderamount']);?> point
                                                </div><!-- .rw-3 -->
                                            </div><!-- .rw-2-wrap -->
                                        </div><!-- .ringkasan-wrap -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- .table-wrap -->
                </div><!-- .history-deposit -->
                
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