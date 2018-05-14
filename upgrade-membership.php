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
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Upgrade Membership</li>
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
                
                <?php
					$quepp = $db->query("SELECT * FROM `member_membership_data` WHERE `idmember`='$idmember'");
					$jumpage = $quepp->num_rows;
					if($jumpage>0):
						$row = $quepp->fetch_assoc();
						//sudah upgrade--
						
						$quipe = $db->query("SELECT *, DATE_FORMAT(`date`,'%d/%m/%Y') as tgl, DATE_FORMAT(`date`,'%H:%i:%s') as tgl2 FROM `member_membership_data_payment` WHERE `idmember` = '$idmember' and `iddata_membership` = '".$row['id']."' ");
						$jumpagePay = $quipe->num_rows;
					?>	
						
                    <h1 class="f-pb">Upgrade Membership</h1>
                    <?php pages(9);?>
                    <form action="<?php echo $GLOBALS['SITE_URL'];?>do-edit-membership" method="post" class="general-form" id="member_shipuid" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="f-pb">Nama Perusahaan</label>
                            <div class="input-wrap">
                                <input type="text" name="company" maxlength="200" value="<?php echo $row['company_name'];?>" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">NPWP Perusahaan <em>(optional)</em></label>
                            <div class="input-wrap">
                                <input type="text" name="npwp" maxlength="200" value="<?php echo $row['npwp'];?>" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">Unggah File <span>(Max Size: 1MB)</span> <br /> 
                            <a href="<?php echo $GLOBALS['UPLOAD_FOLDER'];?><?php echo $row['file_member'];?>" target="_blank" style=" padding-bottom:10px; padding-top:4px; display:block;">View File</a></label>
                            <div class="input-wrap">
                                <input type="file" name="filecompany_edt" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-button">
                        	<?php if($jumpagePay >0): echo'<input type="submit" class="btn btn-red no-margin f-psb" name="submit" value="SIMPAN">'; 
								  else: echo'<input type="submit" class="btn btn-red no-margin f-psb" name="submit" value="SIMPAN & LANJUT">'; endif;?>
                        </div><!-- .form-button -->
                    </form>					
                    
                    <?php if($jumpagePay >0): $ros = $quipe->fetch_assoc();?>
                    <div class="history-deposit">
                	<h2 class="f-pb">History Deposit</h2>
                    <div class="table-wrap">
                        <table cellspacing="0" cellpadding="0" class="blue-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Info</th>
                                    <th>Kredit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $ros['tgl'];?><br />
                                        <?php echo $ros['tgl2'];?>
                                    </td>
                                    <td><?php getstatuspayment($ros['idbank'],$ros['metode_payment']);?></td>
                                    <td><strong class="f-green">Rp <?php echo number_format($ros['amount']);?></strong></td>
                                    <?php if($ros['status']=="Waiting"):?>
                                  	 	 <td><strong>Waiting Approval</strong></td>
                                    <?php else:?>
                                    	 <td><strong style="color:#00CC66;">Paid</strong></td>
                                    <?php endif;?>  
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- .table-wrap -->
                </div><!-- .history-deposit -->  
                <?php endif;?> 
                
                
                    
                <?php else:	?>
                    <h1 class="f-pb">Upgrade Membership</h1>
                    <?php pages(9);?>
                    <form action="<?php echo $GLOBALS['SITE_URL'];?>do-upgrade-membership" method="post" class="general-form" id="member_shipuid" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="f-pb">Nama Perusahaan</label>
                            <div class="input-wrap">
                                <input type="text" name="company" maxlength="200" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">NPWP Perusahaan <em>(optional)</em></label>
                            <div class="input-wrap">
                                <input type="text" name="npwp" maxlength="200" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-group">
                            <label class="f-pb">Unggah File <span>(Max Size: 1MB)</span></label>
                            <div class="input-wrap">
                                <input type="file" name="filecompany" />
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="form-button">
                        	<input type="submit" class="btn btn-red no-margin f-psb" name="submit" value="SIMPAN & LANJUT">
                        </div><!-- .form-button -->
                    </form>
                <?php endif;?>
                
                
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".menu9").addClass("active");	
	});	
</script>


</body>
</html>