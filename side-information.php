<?php $query = $db->query("SELECT * FROM `info_pages`");

while($row = $query->fetch_assoc()): ?>

<li><a href="<?php echo $GLOBALS['SITE_URL'];?>other?id=<?php echo $row['id'];?>" class="menu-<?php echo $row['id'] ?>"><?php echo $row['pages'] ?></a></li>

<?php endwhile;?>

<!-- <li><a href="<?php echo $GLOBALS['SITE_URL'];?>cara-pembelian" class="menu1">Cara Pembelian</a></li>

<li><a href="<?php echo $GLOBALS['SITE_URL'];?>metode-pembayaran" class="menu2">Metode Pembayaran</a></li> -->

<li><a href="popup-confirm-payment.php" class="nuke-fancied2">Konfirmasi Pembayaran</a></li>

<!-- <li><a href="<?php echo $GLOBALS['SITE_URL'];?>pengiriman-produk" class="menu3">Pengiriman Produk</a></li>

<li><a href="<?php echo $GLOBALS['SITE_URL'];?>pengembalian-produk" class="menu4">Pengembalian Produk</a></li>

<li><a href="<?php echo $GLOBALS['SITE_URL'];?>syarat-dan-ketentuan" class="menu5">Syarat &amp; Ketentuan</a></li> -->

<li><a href="<?php echo $GLOBALS['SITE_URL'];?>syarat-dan-ketentuan-membership" class="menu10">Syarat &amp; Ketentuan Membership</a></li>

<li><a href="<?php echo $GLOBALS['SITE_URL'];?>syarat-dan-ketentuan-redeem" class="menu11">Syarat &amp; Ketentuan Redeem Point</a></li>

<li><a href="<?php echo $GLOBALS['SITE_URL'];?>faq" class="menu6">FAQ</a></li>

<li><a href="<?php echo $GLOBALS['SITE_URL'];?>join-corporate-member" class="menu18">Menjadi Corporate Member</a></li>

<!-- <li><a href="<?php echo $GLOBALS['SITE_URL'];?>warranty" class="menu3_waranty">Warranty</a></li> -->

<!-- Last update by Andy Tc on 18 May 2017 - Add while dan comment list yang sudah ada di database -->