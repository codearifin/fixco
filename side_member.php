<?php if(isset($_SESSION['user_token'])==''):?>
	
     <li><a href="<?php echo $GLOBALS['SITE_URL'];?>register" class="menu_regis">Register Akun</a></li>
     <li><a href="<?php echo $GLOBALS['SITE_URL'];?>login" class="menu_login nuke-fancied2">Member Login</a></li> 	
	 <li><a href="<?php echo $GLOBALS['SITE_URL'];?>payment-confirmation" class="menu4">Konfirmasi Pembayaran</a></li>

<?php else:?>
    <?php
		if($_SESSION['user_statusmember']=="REGULAR MEMBER"):

		else:
			echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'my-account-corporate"</script>';
		endif;
	?>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>my-account" class="menu1">Profil Saya</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>upgrade-membership" class="menu9">Upgrade Membership</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>change-password" class="menu8">Ganti Password</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>address-book" class="menu3">Alamat Pengiriman</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>payment-confirmation" class="menu4">Konfirmasi Pembayaran</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>order-history" class="menu5">History Pembelian</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>point-reward" class="menu10">Poin Reward</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>affiliate" class="menu11">Affiliate</a></li>
    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>logout" class="menu7">Log Out</a></li>

<?php endif;?>