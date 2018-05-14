<input type="hidden" name="url_siteid" class="url_siteid" value="<?php echo $GLOBALS['SITE_URL'];?>" />

<header>

	<section class="header">
        <div class="header-bottom">
        	<div class="container">
            	<div class="row">
                	<a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="logo"><img src="<?php echo $GLOBALS['SITE_URL'];?>images/fixcomart-logo.png" alt="FixcoMart" /></a>
                    <div class="hb-right">
                    	<a href="<?php echo $GLOBALS['SITE_URL'];?>shopping-cart" class="btn-cart f-1200-14px">
						<span class="cart-text">SHOPPING CART (<strong class="total_jcart"><?php echo number_format($jcart->items_num());?></strong>)</span>
						<span class="hide-992 f-pb total_jcart"><?php echo number_format($jcart->items_num());?></span></a>

                        <!-- Added 18 Mei 2017 -->
                        <div class="btn-user-area">
                            <a href="" class="toggle-user-menu"><span class="fa fa-user"></span></a>
                            <div class="user-menu-wrap">
                                <ul class="user-menu">
                                   <?php if(isset($_SESSION['user_token'])==''):?>  

                                    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>login" class="nuke-fancied2">Member Login</a></li>

                                    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>register">Member Sign Up</a></li>

                                    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>login-corporate" class="nuke-fancied2">Corporate Login</a></li>

                                    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>register-corporate">Corporate Sign Up</a></li>

                                

                                <?php else:?>

                                      <?php if($_SESSION['user_statusmember']=="REGULAR MEMBER"):?>

                                             <li><a href="<?php echo $GLOBALS['SITE_URL'];?>my-account">Akun Saya</a></li>

                                             <li><a href="<?php echo $GLOBALS['SITE_URL'];?>order-history">History Pembelian</a></li>

                                       
                                      <?php else:?>
                                    
                                             <li><a href="<?php echo $GLOBALS['SITE_URL'];?>my-account-corporate">Akun Saya</a></li>

                                             <li><a href="<?php echo $GLOBALS['SITE_URL'];?>order-history-corporate">History Pembelian</a></li>                                          

                                      <?php endif;?>
                                                                    
                                <?php endif;?>

                                    <li><a href="<?php echo $GLOBALS['SITE_URL'];?>track-order" class="nuke-fancied2">Status Pesanan</a></li>

                                    <li><a href="#" class="has-sub"><span>Layanan Pelanggan</span></a>

                                        <ul class="sub-menu">

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>cara-pembelian">Cara Belanja</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>metode-pembayaran">Metode Pembayaran</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>payment-confirmation-popup" class="nuke-fancied2">Konfirmasi Pembayaran</a></li>

                                        </ul><!-- .sub-menu -->

                                    </li>
                                </ul><!-- .user-menu -->
                            </div><!-- .user-menu-wrap -->
                        </div><!-- .btn-user-area -->
                        
                        <form action="<?php echo $GLOBALS['SITE_URL'];?>do-search-product" method="post" class="search-form">
                            <div class="search-cat-wrap-desktop">
                                <div class="selected-cat">
                                    <span class="text">Semua Kategori</span><span class="fa fa-angle-down"></span>
                                </div><!-- .selected-cat -->
                                <select class="hidden-select-desktop" name="idsubkat">
                                    <option value="all">Semua Kategori</option>
                                    <?php 
										if(isset($_GET['sidsubkat'])): getsubkatheadersearch($_GET['sidsubkat']); else: getsubkatheadersearch(0); endif;?>
                                </select><!-- .hidden-select-desktop -->
                            </div><!-- .search-cat-wrap-desktop -->
                        	<input id="autocomplete-desktop" type="text" name="keyword" maxlength="200" placeholder="Masukkan pencarian Anda di sini" />
                            <input type="submit" value="SEARCH" class="submit-btn" name="submit" />
                        </form>
                    </div><!-- .hb-right -->
                </div><!-- .row -->
            </div><!-- .container -->
        </div><!-- .header-bottom -->

        <form action="<?php echo $GLOBALS['SITE_URL'];?>do-search-product" method="post" class="mobile-search-form">
            <input type="hidden" name="idsubkat" value="all" />
            <input type="text" id="autocomplete-mobile" placeholder="Masukkan pencarian Anda di sini" name="keyword" maxlength="200" />

            <input type="submit" value="SEARCH" class="submit-btn" name="submit" />
        </form>

    </section><!-- .header -->

    <section class="category-section">

    	<div class="container">

        	<div class="row">

                <nav>

                    <ul id="main-navigation">

                        <li><a href="" class="all-cat" title="SEMUA PRODUK">SEMUA PRODUK</a>

                        	<?php include("all-product-navigation.php"); ?>

                        </li>

                        <!-- <?php allcategoryheader();?>  -->

                    </ul><!-- #main-navigation -->

                </nav>

                <div class="new-left">
                    <span class="ht"><span class="ht-call">Hubungi Kami <?php echo $phonepig;?></span></span>
                </div><!-- .new-left -->

                <div class="new-right">
                    <?php if(isset($_SESSION['user_token'])==''):?> 
                    <?php $newToken = generateFormTokenorderform('RegisterPIERO2015Login');?>
                    <div class="htr">
                        
                        <span class="ht"><span class="ht-login-corp">Login Corporate <i class="fa fa-angle-down"></i></span></span>
                        <div class="ht-hidden htr-login">
                            <div class="login-header">
                                <h3>LOGIN</h3>
                                <a href="<?php echo $GLOBALS['SITE_URL'];?>register-corporate">Register Akun</a>
                            </div><!-- .login-header -->
                            <form action="<?php echo $GLOBALS['SITE_URL'];?>do-login-corporate" method="post" class="general-form login-form" id="login_formuidcp">
                                <input type="hidden" name="tokenId" value="<?php echo $newToken;?>" />

                                <div class="form-group">

                                    <div class="input-wrap">

                                        <input type="text" placeholder="Email Anda" class="has-icon hi-email" name="email" maxlength="200" />

                                    </div><!-- .input-wrap -->

                                </div><!-- .form-group -->

                                <div class="form-group">

                                    <div class="input-wrap">

                                        <input type="password" placeholder="Password Anda" class="has-icon hi-password" name="password" maxlength="20" />

                                    </div><!-- .input-wrap -->

                                </div><!-- .form-group -->

                                <a href="<?php echo $GLOBALS['SITE_URL'];?>popup-reset-password-corporate" class="nuke-fancied2 f-lightGray">Forgot password?</a>

                                <div class="form-button centered">

                                    <input type="submit" class="btn btn-yellow no-margin btn-full" value="LOGIN" name="submit" />

                                </div><!-- .form-button -->

                            </form>
                        </div><!-- .ht-hidden -->
                    </div><!-- .htr -->
                    <div class="htr">
                        <span class="ht"><span class="ht-login">Login <i class="fa fa-angle-down"></i></span></span>
                        <div class="ht-hidden htr-login">
                            <div class="login-header">
                                <h3>LOGIN</h3>
                                <a href="<?php echo $GLOBALS['SITE_URL'];?>register">Register Akun</a>
                            </div><!-- .login-header -->
                            <form action="<?php echo $GLOBALS['SITE_URL'];?>do-login" method="post" class="general-form login-form" id="login_formuid">
                                <input type="hidden" name="tokenId" value="<?php echo $newToken;?>" />

                                

                                <div class="form-group">

                                    <div class="input-wrap">

                                        <input type="text" placeholder="Email Anda" class="has-icon hi-email" name="email" maxlength="200" />

                                    </div><!-- .input-wrap -->

                                </div><!-- .form-group -->

                                <div class="form-group">

                                    <div class="input-wrap">

                                        <input type="password" placeholder="Password Anda" class="has-icon hi-password" name="password" maxlength="20" />

                                    </div><!-- .input-wrap -->

                                </div><!-- .form-group -->

                                <a href="<?php echo $GLOBALS['SITE_URL'];?>reset-password" class="nuke-fancied2 f-lightGray">Forgot password?</a>

                                <div class="form-button centered">

                                    <input type="submit" class="btn btn-yellow no-margin btn-full" value="LOGIN"  name="submit" />

                                </div><!-- .form-button -->

                                

                                <div class="separator"><span>atau</span></div>

                                <div class="social-login">

                                    <a href="<?php echo $login_url;?>"><i class="fa fa-facebook"></i>&nbsp;&nbsp;Facebook</a>

                                    <a href="<?php echo $authUrl;?>" class="last"><i class="fa fa-google-plus"></i>&nbsp;&nbsp;Google+</a>

                                </div><!-- .social-login -->

                            </form>
                        </div><!-- .ht-hidden -->
                    </div><!-- .htr -->

                    <?php else:?>

                        <?php if($_SESSION['user_statusmember']=="REGULAR MEMBER"):?>

                                <div class="htr">

                                    <span class="ht"><span class="ht-login">&nbsp;Akun Saya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-angle-down"></i></span></span>

                                    <ul class="ht-menu ht-hidden">

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>my-account">Profil Saya</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>upgrade-membership">Upgrade Membership</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>change-password">Ganti Password</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>address-book">Alamat Pengiriman</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>payment-confirmation">Konfirmasi Pembayaran</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>order-history">History Pembelian</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>point-reward">Poin Reward</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>affiliate">Affiliate</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>logout">Log Out</a></li>

                                    </ul><!-- .ht-hidden -->

                                </div><!-- .htr -->

                        <?php else: 

                                 //CORPORATE MENU--
                             ?>
                             <div class="htr">

                                    <span class="ht"><span class="ht-login">&nbsp;Akun Saya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-angle-down"></i></span></span>

                                    <ul class="ht-menu ht-hidden">

                                            <?php if($_SESSION['user_status']=="SUPER USER"): ?>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>my-account-corporate">Profil Saya</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>change-password-corporate">Ganti Password</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>user-management">User Management</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>address-book-corporate">Alamat Pengiriman</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>deposit-corporate">Deposit</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>draft-quotation">Draft Quotation</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>payment-confirmation-corporate">Konfirmasi Pembayaran</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>order-history-corporate">History Pembelian</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>logout">Log Out</a></li>

                                            <?php else: ?>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>my-account-corporate">Profil Saya</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>change-password-corporate">Ganti Password</a></li>

                                            <!-- <li><a href="<?php echo $GLOBALS['SITE_URL'];?>user-management">User Management</a></li> -->

                                            <!-- <li><a href="<?php echo $GLOBALS['SITE_URL'];?>address-book-corporate">Alamat Pengiriman</a></li> -->

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>deposit-corporate">Deposit</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>draft-quotation">Draft Quotation</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>payment-confirmation-corporate">Konfirmasi Pembayaran</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>order-history-corporate">History Pembelian</a></li>

                                            <li><a href="<?php echo $GLOBALS['SITE_URL'];?>logout">Log Out</a></li>

                                        <?php endif;?>

                                    </ul><!-- .ht-hidden -->

                                </div><!-- .htr -->                           

                            <?php endif;?>
                        <?php endif;?>

                        <div class="htr">

                            <span class="ht"><span class="ht-service">Layanan Pelanggan <i class="fa fa-angle-down"></i></span></span>

                            <ul class="ht-menu ht-hidden">

                                <li><a href="<?php echo $GLOBALS['SITE_URL'];?>cara-pembelian">Cara Belanja</a></li>

                                <li><a href="<?php echo $GLOBALS['SITE_URL'];?>metode-pembayaran">Metode Pembayaran</a></li>

                                <li><a href="<?php echo $GLOBALS['SITE_URL'];?>payment-confirmation-popup" class="nuke-fancied2">Konfirmasi Pembayaran</a></li>

                            </ul><!-- .ht-hidden -->

                        </div><!-- .htr -->
                        <div class="htr">

                            <a href="<?php echo $GLOBALS['SITE_URL'];?>track-order" class="ht nuke-fancied2"><span class="ht-status">Status Pesanan</span></a>

                        </div><!-- .htr -->
                        
                </div><!-- .new-right -->
        	</div><!-- .row -->

        </div><!-- .container -->

    </section><!-- .category-section -->

    

    <div class="mobile-menu-toggle">

    	<button class="c-hamburger c-hamburger--htx">

          <span>toggle menu</span>

        </button>

    </div><!-- .mobile-menu-toggle -->

</header>







<section class="mobile-nav-wrap">

    <ul class="mobile-nav">

        <?php allcategoryheadermobile();?>

    </ul><!-- .mobile-nav -->

</section><!-- .mobile-nav-wrap -->