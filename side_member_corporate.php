<?php if(isset($_SESSION['user_token'])==''):?>
    
     <li><a href="<?php echo $GLOBALS['SITE_URL'];?>register-corporate" class="menu_regis">Register Akun</a></li>
     <li><a href="<?php echo $GLOBALS['SITE_URL'];?>login-corporate" class="menu_login nuke-fancied2">Member Login</a></li> 	
	 <li><a href="<?php echo $GLOBALS['SITE_URL'];?>payment-confirmation" class="menu4">Konfirmasi Pembayaran</a></li>

<?php else:?>     
     
    <?php
		if($_SESSION['user_statusmember']=="CORPORATE MEMBER"):
			if(isset($_SESSION['user_token'])): $UsertokenidLogin = $_SESSION['user_token']; else: $UsertokenidLogin = ''; endif;
			$statusmember = getstatusmember($UsertokenidLogin);	
		else:
			echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'my-account"</script>';	
			$statusmember = 0;
		endif;
	?>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>my-account-corporate" class="menu1">Corporate Profil</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>change-password-corporate" class="menu8">Ganti Password</a></li>
    <?php if($statusmember==1): echo'<li><a href="'.$GLOBALS['SITE_URL'].'user-management" class="menu11">User Management</a></li>'; endif;?>
    <?php if($statusmember==1): echo'<li><a href="'.$GLOBALS['SITE_URL'].'address-book-corporate" class="menu3">Alamat Pengiriman</a></li>'; endif;?>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>deposit-corporate" class="menu2">Deposit</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>draft-quotation" class="menu99">Draft Quotation</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>payment-confirmation-corporate" class="menu4">Konfirmasi Pembayaran</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>order-history-corporate" class="menu5">History Pembelian</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>logout" class="menu7">Log Out</a></li>
<?php endif;?>    