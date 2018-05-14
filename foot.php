<section class="newsletter-section">

	<div class="container">

        <div class="row">

        	<h3 class="f-psb">Dapatkan Penawaran Terbaik dari Kami</h3>

            <form action="<?php echo $GLOBALS['SITE_URL'];?>do-newsletter" method="post" class="newsletter-form" id="newsletteruidform">

            	<input type="text" placeholder="Masukkan alamat email Anda di sini" name="email" maxlength="200" />

                <input type="submit" value="SUBMIT" class="submit-btn" name="submit" />

            </form>

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- .newsletter-section -->



<footer>

	<div class="footer-top">

    	<div class="container">

        	<div class="row">

            	<div class="split-container">

                	<div class="split-2-640 split-4-992 ft-child-wrap">

                    	<div class="ft-child">

                        	<h3>Layanan Pelanggan</h3>

                            <ul class="footer-info">

                            	<li class="fi-address"><?php echo nl2br($company_address);?></li>

								<li class="fi-phone"><?php echo $phonepig;?></li>

                                <li class="fi-email"><a href="mailto:<?php echo $emailconfig;?>"><?php echo $emailconfig;?></a></li>

                                <li class="fi-wa"><?php echo $whatsapppig;?></a></li>

                                <li class="fi-bbm"><?php echo $pinbbpig;?></a></li>

                            </ul><!-- .footer-info -->

                        </div><!-- .ft-child -->

                        <div class="ft-child">

                        	<nav class="soc-nav">

                            	<a href="<?php echo $facebook_link;?>" target="_blank"><i class="fa fa-facebook"></i></a>

                                <a href="<?php echo $twitter_link;?>" target="_blank"><i class="fa fa-twitter"></i></a>

                                <a href="<?php echo $instagram_link;?>" target="_blank"><i class="fa fa-instagram"></i></a>

                                <a href="<?php echo $youtube_link;?>" target="_blank"><i class="fa fa-youtube"></i></a>
                                
                                <a href="https://www.messenger.com/t/fixcomart" target="_blank"><img src="<?php echo $GLOBALS['SITE_URL'];?>images/icon-red-fb.png" style="width:27px;" /></a>

                            </nav>

                        </div><!-- .ft-child -->

                    </div><!-- .ft-child-wrap -->

                    <div class="split-2-640 split-4-992 ft-child-wrap">

                    	<div class="ft-child">

                        	<h3>Informasi Pelanggan</h3>

                            <nav class="footer-nav">

                                <?php $query = $db->query("SELECT * FROM `info_pages`");

                                while($row = $query->fetch_assoc()): ?>

                                <a href="<?php echo $GLOBALS['SITE_URL'];?>other?id=<?php echo $row['id'];?>">&raquo; <?php echo $row['pages'] ?></a>

                                <?php endwhile;?>

                                <a href="<?php echo $GLOBALS['SITE_URL'];?>payment-confirmation-popup" class="nuke-fancied2">&raquo; Konfirmasi Pembayaran</a>

                                <a href="<?php echo $GLOBALS['SITE_URL'];?>faq">&raquo; FAQ</a>
                                
                            </nav>

                        </div><!-- .ft-child -->

                    </div><!-- .ft-child-wrap -->

                    <div class="clear-640 noclear-992"></div>

                    <div class="split-2-640 split-4-992 ft-child-wrap">

                    	<div class="ft-child">

                        	<h3>Tentang Fixcomart</h3>

                            <nav class="footer-nav">

                            	<a href="<?php echo $GLOBALS['SITE_URL'];?>about">&raquo; Tentang Kami</a>

                                <a href="<?php echo $GLOBALS['SITE_URL'];?>contact">&raquo; Hubungi Kami</a>

                            </nav>

                        </div><!-- .ft-child -->

                        <div class="ft-child">

                        	<h3>Corporate Member</h3>

                            <nav class="footer-nav">

                            	<a href="<?php echo $GLOBALS['SITE_URL'];?>join-corporate-member">&raquo; Menjadi Corporate Member</a>
                                
                                <a href="<?php echo $GLOBALS['SITE_URL'];?>corporate-member">&raquo; Corporate Member</a>

                                <a href="<?php echo $GLOBALS['SITE_URL'];?>diskon-deposit">&raquo; Diskon Deposit</a>


                                <a href="<?php echo $GLOBALS['SITE_URL'];?>multiple-user">&raquo; Power User / Multiple User</a>

                                <a href="<?php echo $GLOBALS['SITE_URL'];?>late-payment">&raquo; Late Payment</a>

                            </nav>

                        </div><!-- .ft-child -->

                    </div><!-- .ft-child-wrap -->

                    <div class="split-2-640 split-4-992 ft-child-wrap">

                    	<div class="ft-child">

                        	<h3>Metode Pembayaran</h3>

                            <ul class="payment-icon">

                                <?php $query = $db->query("SELECT * FROM `payment_method`");

                                while($row = $query->fetch_assoc()): ?>

                                <li><img src="<?php echo $GLOBALS['SITE_URL'];?>uploads/<?php echo $row['logo_image'];?>" alt="" /></li>

                                <?php endwhile;?>

                            	<!-- <li><img src="<?php echo $GLOBALS['SITE_URL'];?>images/payment/payment1.png" alt="" /></li>

                                <li><img src="<?php echo $GLOBALS['SITE_URL'];?>images/payment/payment2.png" alt="" /></li>

                                <li><img src="<?php echo $GLOBALS['SITE_URL'];?>images/payment/payment3.png" alt="" /></li>

                                <li><img src="<?php echo $GLOBALS['SITE_URL'];?>images/payment/payment4.png" alt="" /></li>

                                <li><img src="<?php echo $GLOBALS['SITE_URL'];?>images/payment/payment5.png" alt="" /></li>

                                <li><img src="<?php echo $GLOBALS['SITE_URL'];?>images/payment/payment6.png" alt="" /></li>

                                <li><img src="<?php echo $GLOBALS['SITE_URL'];?>images/payment/payment7.png" alt="" /></li> -->

                            </ul><!-- .payment-icon -->

                        </div><!-- .ft-child -->

                        <div class="ft-child">

                        	<h3>Layanan Kurir</h3>

                            <ul class="payment-icon">

                                 <?php $query = $db->query("SELECT * FROM `courier_method`");

                                while($row = $query->fetch_assoc()): ?>

                                <li><img src="<?php echo $GLOBALS['SITE_URL'];?>uploads/<?php echo $row['logo_image'];?>" alt="" /></li>

                                <?php endwhile;?>

                            	<!-- <li><img src="<?php echo $GLOBALS['SITE_URL'];?>images/payment/kurir1.png" alt="" /></li>

                                <li><img src="<?php echo $GLOBALS['SITE_URL'];?>images/payment/kurir2.png" alt="" /></li>

                                <li><img src="<?php echo $GLOBALS['SITE_URL'];?>images/payment/kurir3.png" alt="" /></li> -->


                            </ul><!-- .payment-icon -->

                        </div><!-- .ft-child -->

                    </div><!-- .ft-child-wrap -->

                </div><!-- .split-container -->

            </div><!-- .row -->

        </div><!-- .container -->

    </div><!-- .footer-top -->

    <div class="footer-bottom">

    	<div class="container">

        	<div class="row">

            	Copyrights &copy; 2016 Fixco Mart. All Rights Reserved.

            </div><!-- .row -->

        </div><!-- .container -->

    </div><!-- .footer-bottom -->

</footer>