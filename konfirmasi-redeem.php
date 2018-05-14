<?php include("header.php"); 

	if(isset($_SESSION['user_token'])==''):
		$_SESSION['error_msg']='login-first';
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;
	//member data
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
	$query = $db->query("SELECT * FROM `member` WHERE `tokenmember`='$Usertokenid'");
	$res = $query->fetch_assoc();
	$idmember = $res['id'];	
	$totalpointmember = gettotalpintmemberTotal($idmember);
	
	if($res['status_complete']==0):
		echo '<script language="JavaScript">window.location="'.$GLOBALS['SITE_URL'].'my-account";</script>';
	endif;	
	
	//select prod
	if(isset($_GET['idprod'])): $idprodList = replaceUrel($_GET['idprod']); else: $idprodList = 0; endif;
	$query = $db->query("SELECT * FROM `redeem_product` WHERE `id`='$idprodList' and `publish`=1 ") or die($db->error);
	$jumpage = $query->num_rows;
	if($jumpage>0):
		$row = $query->fetch_assoc();	
		
		if($row['point_redeem']>$totalpointmember or $row['stock'] < 1):
			echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'redeem-point"</script>';
		endif;
		
	else:
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'redeem-point"</script>';
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
         
         	
            <form action="<?php echo $GLOBALS['SITE_URL'];?>do-savedataredeem" method="post">
            	<input type="hidden" name="idprod_redeem" value="<?php echo $row['id'];?>" />
            <div class="template-wrap">
                <h1 class="f-pb">Point Reward</h1>
                <div class="red-tabbing">
                	<a href="<?php echo $GLOBALS['SITE_URL'];?>point-reward">History Point Reward</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>redeem-point" class="active">Redeem Point</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>redeem-history">Redeem History</a>
                </div><!-- .red-tabbing -->
                <div class="status-corporate">
                    Point Reward yang Anda miliki:
                    <span class="f-pb f-red"><?php echo number_format($totalpointmember);?> point</span>
                </div><!-- .status-corporate -->
                
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
                                	<div class="rw-1"><?php echo '<a href="'.$GLOBALS['SITE_URL'].'product-redeem-detail/'.replace($row['name']).'/'.$row['id'].'" class="nuke-fancied2">
															<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['name'].'" /></a>';?>
                                    </div>
                                    <div class="rw-2-wrap">
                                        <div class="rw-2">
                                            <h3 class="f-pb"><?php echo '<a href="'.$GLOBALS['SITE_URL'].'product-redeem-detail/'.replace($row['name']).'/'.$row['id'].'" class="nuke-fancied2">'.$row['sku_product'].' - '.$row['name'].'';?></h3>
                                            <p class="f-1200-14px">Point yang dibutuhkan : <?php echo number_format($row['point_redeem']);?> point</p>
                                        </div><!-- .rw-2 -->
                                        <div class="rw-3 f-red f-pb">
                                            <?php echo number_format($row['point_redeem']);?> point
                                        </div><!-- .rw-3 -->
                                    </div><!-- .rw-2-wrap -->
                                </div><!-- .ringkasan-wrap -->
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                
               				 <br><br>
               				 <h2 class="f-pb">Alamat Pengiriman</h2><br><br>
                               
                                <div class="address-accordion">
                                  <?php
								  		if(isset($_SESSION['member_shiipinid'])): $idabook = $_SESSION['member_shiipinid']; else: $idabook = 0; endif; 
										if($idabook=="" or $idabook < 1):
											$Klass = ' opened ';
											$Klass_1 = ' style="display:block;" ';
											$Klass_2 = '  checked="checked" ';

											$BKlass = '';
											$BKlass_1 = '';											

										
										else:
											$Klass = '';
											$Klass_1 = '';
											$Klass_2 = '';	

											$BKlass = ' opened ';
											$BKlass_1 = ' style="display:block;" ';																															
										endif;
									?>
                                    
                                    <div class="aa-child">
                                        <a href="#" class="aa-toggle <?php echo $Klass;?>">Gunakan Alamat Penagihan</a>
                                        <div class="aac-child" <?php echo $Klass_1;?>>
                                            <div class="aacc">
                                                <input type="radio" id="alp1" name="alp" class="rme-radio addressmember-list" <?php echo $Klass_2;?> />
                                                <label for="alp1" class="rme-label">
                                                     <?php getmemberaddressredeem($idmember);?>
                                                </label>
                                            </div><!-- .aacc -->
                                        </div><!-- .aac-child -->
                                    </div><!-- .aa-child -->
                                    
                                    <div class="aa-child">
                                        <a href="#" class="aa-toggle <?php echo $BKlass;?>">Pilih dari Alamat Tersimpan</a>
                                        <div class="aac-child" <?php echo $BKlass_1;?>>
                                            <?php getaddressbookdetailredeem($idmember,$idabook);?>
                                            <a href="<?php echo $GLOBALS['SITE_URL'];?>add-new-addres-redeem" class="nuke-fancied2 btn-add-address f-psb">Add Address</a>
                                        </div><!-- .aac-child -->
                                    </div><!-- .aa-child -->
                                </div><!-- .address-accordion -->
                
                <div class="snk-check">
                    <input type="checkbox" id="snk" name="snk" class="rme-check termchecklist" />
                    <label for="snk" class="rme-label">Saya setuju dengan <a href="<?php echo $GLOBALS['SITE_URL'];?>syarat-dan-ketentuan-redeem" target="_blank">syarat dan ketentuan</a> yang berlaku.</label>
                </div><!-- .snk-check -->
                <br><br>
                <div class="form-button aleft">
                	<input type="hidden" name="bank_transferid" id="bank_transferid" value="1" />
                    <input type="hidden" name="termconditionid" id="termconditionid" value="0" />
                    
                    <?php
							echo'<input type="submit" value="" class="btn btn-red no-margin btn-checkout" name="submit" id="btn_submitorder" style="display:none;"  />';
                            echo'<a href="#" class="btn btn-red no-margin btn-checkout bayar_btndd">REDEEM</a>';
					?>  
                    <img src="<?php echo $GLOBALS['SITE_URL'];?>images/load_page.gif" class="saveorder_load" style="display:none;" />    
					<div style="clear:both; height:1px;"></div>          
                </div><!-- .form-button -->
            </div><!-- .template-wrap -->
            </form>
            
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