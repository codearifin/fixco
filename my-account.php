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
	
	//act edit
	if(isset($_GET['act'])): $act = $_GET['act']; else: $act = ''; endif;	
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Akun Saya</li>
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
                <h1 class="f-pb">Profil Saya</h1>
                
                
            	 <?php if($res['status_complete']==0): 
                          echo'<div class="error-msg-profile">';
                                echo'<div class="textAlert">* Silahkan lengkapi alamat penagihan anda!</div>';
                          echo'</div>';		
                      endif;
                ?>
                <form action="<?php echo $GLOBALS['SITE_URL'];?>do-update-profile" method="post" class="general-form profile-form" id="edit_register_form">
                	<input type="hidden" name="email_lama" value="<?php echo $res['email'];?>"  />
                    <input type="hidden" name="actUrl" value="<?php echo $act;?>"  />
                        
                    <div class="boxed-form">
                    	<h2 class="boxed-heading f-pb">UBAH PROFIL</h2>
                        <div class="boxed-content">
                        	<div class="tc-form-group">
                                <div class="form-group">
                                    <label class="f-pb">Nama Depan</label>
                                    <div class="input-wrap">
                                        <input type="text" name="name" value="<?php echo $res['name'];?>" maxlength="200" />
                                        <span></span>
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                                <div class="form-group">
                                    <label class="f-pb">Nama Belakang</label>
                                    <div class="input-wrap">
                                        <input type="text" name="lastname" value="<?php echo $res['lastname'];?>" maxlength="100" />
                                        <span></span>
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                            </div><!-- .tc-form-group -->
                            <div class="tc-form-group">
                                <div class="form-group">
                                    <label class="f-pb">Telepon</label>
                                    <div class="input-wrap">
                                        <input type="text" name="phone" maxlength="20" value="<?php echo $res['phone'];?>" />
                                        <span></span>
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                                <div class="form-group">
                                    <label class="f-pb">Telepon Seluluer</label>
                                    <div class="input-wrap">
                                        <input type="text" name="mobilephone" maxlength="20" value="<?php echo $res['mobile_phone'];?>" />
                                        <span></span>
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                            </div><!-- .tc-form-group -->
                            <div class="tc-form-group">
                                <div class="form-group">
                                    <label class="f-pb">email</label>
                                    <div class="input-wrap">
                                        <input type="text" name="email" id="email" value="<?php echo $res['email'];?>" maxlength="200"  />
                                        <span></span> 
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                                <div class="form-group">
                                    <label class="f-pb">Jenis Kelamin</label>
                                    <div class="input-wrap">
                                        <div class="select-style">
                                        	<select name="gender" class="gender">
                                            	<option value="">Jenis Kelamin</option>
                                            	<option value="Laki-Laki" <?php if($res['gender']=="Laki-Laki"): echo'selected="selected"'; endif;?>>Laki-Laki</option>
                                                <option value="Perempuan" <?php if($res['gender']=="Perempuan"): echo'selected="selected"'; endif;?>>Perempuan</option>
                                            </select>
                                        </div><!-- .select-style -->
                                    </div><!-- .input-wrap -->
                                    <span class="error_gender error_msg"></span>
                                </div><!-- .form-group -->
                            </div><!-- .tc-form-group -->
                            
                             <div class="tc-form-group">
                                <div class="form-group">
                                    <label class="f-pb">Bank Account</label>
                                    <div class="input-wrap">
                                        <input type="text" name="bank_account" placeholder="e.g: BCA - No. Rek 670 7460 602 A.n. Jack Sparrow" value="<?php echo $res['bank_account'];?>" maxlength="200"  />
                                        <span></span> 
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
 
                            </div><!-- .tc-form-group -->
                            
                        </div><!-- .boxed-content -->
                    </div><!-- .boxed-form -->
                    
                    <?php if($res['status_complete']==0):?> 
                    <div class="boxed-form">
                    	<h2 class="boxed-heading f-pb">ALAMAT PENAGIHAN</h2>
                        <div class="boxed-content">
                            <div class="tc-form-group">
                                <div class="form-group">
                                    <label class="f-pb">Provinsi</label>
                                    <div class="input-wrap">
                                        <div class="select-style">
                                            <select name="province" class="province">
                                                <option value="" selected="selected">- Pilih Provinsi -</option>
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
                                                <option value="">- Pilih Kabupaten -</option>
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
                                                <option value="">- Pilih Kota -</option>
                                            </select>
                                        </div><!-- .select-style -->
                                    </div><!-- .input-wrap -->
                                    <span class="error_city error_msg"></span>  
                                </div><!-- .form-group -->
                                <div class="form-group">
                                    <label class="f-pb">Kode Pos</label>
                                    <div class="input-wrap">
                                        <input type="text" placeholder="Kode pos alamat Anda" name="kodepos" value=""  />
                                        <span></span> 
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                            </div><!-- .tc-form-group -->
                            <div class="form-group">
                                <label class="f-psb">Alamat Lengkap</label>
                                <div class="input-wrap">
                                    <textarea placeholder="Alamat lengkap Anda"  name="address"></textarea>
                                    <span></span>
                                </div><!-- .input-wrap -->
                            </div><!-- .form-group -->
                        </div><!-- .boxed-content -->
                    </div><!-- .boxed-form -->
                    <?php else:
						
								$quepp = $db->query("SELECT * FROM `billing_address` WHERE `idmember`='$idmember'");
								$row = $quepp->fetch_assoc();
					?>
                    <div class="boxed-form">
                    	<h2 class="boxed-heading f-pb">ALAMAT PENAGIHAN</h2>
                        <div class="boxed-content">
                            <div class="tc-form-group">
                                <div class="form-group">
                                    <label class="f-pb">Provinsi</label>
                                    <div class="input-wrap">
                                        <div class="select-style">
                                            <select name="province" class="province">
                                                <option value="<?php echo $row['provinsi'];?>" selected="selected"><?php echo ucwords($row['provinsi']);?></option>
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
                                               <option value="<?php echo $row['kabupaten'];?>" selected="selected"><?php echo ucwords($row['kabupaten']);?></option>
                                               <?php getpropinsilist_kabu($row['provinsi']);?>
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
                                             	<option value="<?php echo $row['idcity'];?>" selected="selected"><?php echo getnamakota($row['idcity']);?></option>
                                                <?php getpropinsilist_kota($row['kabupaten']);?>
                                            </select>
                                        </div><!-- .select-style -->
                                    </div><!-- .input-wrap -->
                                    <span class="error_city error_msg"></span>  
                                </div><!-- .form-group -->
                                <div class="form-group">
                                    <label class="f-pb">Kode Pos</label>
                                    <div class="input-wrap">
                                        <input type="text" name="kodepos" value="<?php echo $row['kodepos'];?>"  />
                                        <span></span> 
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                            </div><!-- .tc-form-group -->
                            <div class="form-group">
                                <label class="f-psb">Alamat Lengkap</label>
                                <div class="input-wrap">
                                    <textarea name="address"><?php echo $row['address'];?></textarea>
                                    <span></span>
                                </div><!-- .input-wrap -->
                            </div><!-- .form-group -->
                        </div><!-- .boxed-content -->
                    </div><!-- .boxed-form -->                    
                    <?php endif;?>
                    
                    <div class="form-button">
                    	<input type="submit" class="btn btn-red no-margin f-psb btn-checkout" value="SIMPAN PROFIL" name="submit" />
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
		$(".menu1").addClass("active");	
	});	
</script>

</body>
</html>