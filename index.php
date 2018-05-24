<?php include("header.php"); ?>

</head>



<body>

<?php include("head.php"); ?>

<div class="new-homepage-banner-wrap">

    <div class="left">
        <div class="new-all-product-menu">
            <h2>Semua Produk</h2>
            <div class="content">
                <?php allcategorynew(); ?>
            </div><!-- .content -->
        </div><!-- .new-all-product-menu -->
    </div><!-- .left -->
    <div class="right">
        <section id="desktop-slider">
            <div class="container">
                <span class="slider-mask"></span>
                <div id="desktop-flex" class="flexslider">
                <ul class="slides">
                    <?php banner();?>
                </ul><!-- .slides -->
            </div><!-- #desktop-flex -->
         </div><!-- .container -->
        </section><!-- #desktop-slider -->

        <section class="top-ads">
            <div class="main-container">
                <div class="row small-gutter">
                    <?php topads(); ?>
                </div><!-- .row -->
            </div><!-- .main-container -->
        </section><!-- .top-ads -->
    </div><!-- .righjt -->
</div><!-- .new-homepage-banner-wrap -->

<section class="below-banner-section">
    <div class="container">
        <div class="carousel-wrapper">
            <div class="below-banner-carousel">
                <div class="wrap">
                   <?php banneradshome();?>
                </div><!-- .wrap -->
            </div><!-- .below-banner-carousel -->
        </div><!-- .carousel-wrapper -->
    </div><!-- .container -->
</section><!-- .below-banner-section -->

<section class="section section-flashsale" style="background-image:url(uploads/<?php echo getflashsalebgimg(); ?>)">
    <div class="container">
        <div class="row">
            <div class="fs-heading">
                <h2>Flash Sale</h2>
                <div class="countdown-wrap">
                    <ul id="fs-date" class="fs-countdown">
                        <li><span class="hours">00</span><p class="hours_text">HOURS</p></li>
                        <li class="seperator">:</li>
                        <li><span class="minutes">00</span><p class="minutes_text">MINS</p></li>
                        <li class="seperator">:</li>
                        <li><span class="seconds">00</span><p class="seconds_text">SECONDS</p></li>
                    </ul>
                </div><!-- .countdown-wrap -->
            </div><!-- .fs-heading -->
            <div class="flash-carousel-wrap">
                <div class="flash-carousel">
                    <?php getflashsaleproduct(); ?>
                </div><!-- .flash-carousel -->
            </div><!-- .flash-carousel-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .index-tab -->

<?php if(checkrfq() == 1): ?>
<section class="section section-quotation-new">
    <div class="container">
        <div class="content">
            <div class="top">
                <div class="white-logo">
                    <?php getrfqimage(); ?>
                </div><!-- .white-logo -->
                <h2 class="ngc-maintitle"><?php getrfqheadername(); ?></h2>
                <p><?php getrfqdescription(); ?></p>
                <?php getrfqcsimage(); ?>
            </div><!-- .top -->
            <div class="bottom" style="background-color:<?php getrfqbackgroundcolor(); ?>;">
                <h2 class="ngc-title">Dapatkan Penawaran Terbaik</h2>
                <form action="<?php echo $GLOBALS['SITE_URL']; ?>quotation-request" class="general-form quotation-form" method="post">
                    <div class="form-group">
                        <label class="f-pb">Nama Produk</label>
                        <div class="input-wrap has-icon quotation-input-form">
                            <input type="text" name="nama_produk" class="autocomplete-quotation" placeholder="Masukkan Nama produk yang diinginkan">
                            <span class="fa fa-sticky-note icon"></span>
                        </div><!-- .input-wrap -->
                    </div><!-- .form-group -->
                    <div class="row medium-gutter">
                        <div class="grid-child n-1-1per2 n-540-1per4 n-768-1per2 n-no-margin-bottom">
                            <div class="form-group n-no-margin-bottom">
                                <label class="f-pb">Jumlah</label>
                                <div class="input-wrap">
                                    <input type="number" name="jumlah" value="100">
                                </div><!-- .input-wrap -->
                            </div><!-- .form-group -->
                        </div><!-- .grid-child -->
                        <div class="grid-child n-1-1per2 n-540-1per4 n-768-1per2 n-no-margin-bottom">
                            <div class="form-group n-no-margin-bottom">
                                <label class="f-pb">Satuan</label>
                                <div class="input-wrap">
                                    <div class="select-style">
                                        <select name="satuan">
                                            <?php satuanquotation(); ?>
                                        </select>
                                    </div><!-- .select-style -->
                                </div><!-- .input-wrap -->
                            </div><!-- .form-group -->
                        </div><!-- .grid-child -->
                        <div class="grid-child n-1-1per1 n-540-1per2 n-768-1per1 n-no-margin-bottom">
                            <input type="submit" class="btn btn-blue" value="BUAT PERMINTAAN" name="submit" />
                            <a href="<?php getrfqlink(); ?>" class="f-pb link-white">Apa Itu Request Quotation?</a>
                            <!-- <a href="quotation-request.php" class="btn btn-yellow">BUAT PERMINTAAN</a> -->
                        </div><!-- .grid-child -->
                    </div><!-- .row -->
                </form><!-- .general-form -->
            </div><!-- .bottom -->
        </div><!-- .content -->
        

        
    </div><!-- .container -->
</section><!-- .section -->
<?php endif; ?>



<section class="section section-index-featured">

	<div class="container">

    	<div class="row">
            <h2 class="ngc-maintitle">Fixco Products</h2>

        	<div class="ngc-tab-wrap prod-tab-wrap">

            	<div class="ngc-tabs">

                    <a href="#tab-1" class="active">Produk Terbaru</a>

                    <a href="#tab-2" class="">Produk Unggulan</a>

                    <a href="#tab-3" class="">Produk Terbaik</a>

                </div><!-- .ngc-tabs -->

                <div class="ngc-tab-containers">
                    <div id="tab-1" class="ngc-tab-container active">
                        
                        <div class="tab-carousel-wrap">
                            <div class="featured-carousel">
                                <?php getprodutterbaru();?>
                            </div>
                        </div>
                    </div><!-- .ngc-tab-container -->

                    <div id="tab-2" class="ngc-tab-container">
                         <div class="tab-carousel-wrap">
                            <div class="featured-carousel">
                                <?php getprodutterunggulan();?>
                            </div>
                        </div>
                        

                    </div><!-- .ngc-tab-container -->

                    <div id="tab-3" class="ngc-tab-container">
                        <div class="tab-carousel-wrap">
                            <div class="featured-carousel">
                                <?php getproduterbaik();?>
                            </div>
                        </div>

                    </div><!-- .ngc-tab-container -->
                </div>    
            </div><!-- .ngc-tab-wrap -->

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- .index-tab -->



<section class="section index-product">

	<div class="container">

    	<div class="row">

            <div class="bottom-ads">
                <?php bottomads(); ?>
            </div><!-- .bottom-ads -->

            <?php productescalatornew();?>

            <div class="index-blog">
                <?php bloglistindex(); ?>

                <div class="n-align-center">
                    <a href="<?php echo $GLOBALS['SITE_URL'].'blog'; ?>" class="btn btn-red bigger n-no-margin">LIHAT LEBIH</a>
                </div>
            </div><!-- .index-blog -->

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- .index-product -->

<?php if(checkcorporatejoin() == 1): ?>
<section class="section join-corporate" style="background-image:url(uploads/<?php echo getcorporatejoinbgimg(); ?>)">
    <div class="container">
        <div class="row">
            <div class="content">
                <div class="jc-intro">
                    <?php corporatejoinheader(); ?>
                    <a href="<?php echo getsiteurl().'register-corporate'; ?>" class="btn btn-red">DAFTAR SEKARANG</a>
                </div><!-- .jc-intro -->
                <div class="jc-why">
                    <div class="row medium-gutter same-height">
                        <?php corporatejoindetail(); ?>
                    </div><!-- .row -->
                </div><!-- .jc-why -->
            </div><!-- .content -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->
<?php endif; ?>

<section class="footer-about">

	<div class="container">

    	<div class="row">

        	<div class="split-container">

                <?php homepageaboutus();?>

            </div><!-- .split-container -->

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- .footer-about -->	



<?php include("foot.php"); ?>

<?php include("footer.php"); ?>


</body>
<script>
    $(document).ready(function(){
        $(".category-section").addClass("home");
        $(document).on('click','.new-all-product-menu .content .main-category li.has-sub a', function(e){
            var getURL = $(this).attr("href");
            if ($(this).hasClass("active")) {
                // already opened
                $(this).removeClass("active");
                $(getURL).removeClass("active");
            } else {
                // not opened yet
                $(".new-all-product-menu .content .main-category li.has-sub a").removeClass("active");
                $(this).addClass("active");
                $(".new-all-product-menu .content .new-mega").removeClass("active");
                $(getURL).addClass("active");
            }
            e.preventDefault();
        });

        var searchresultsArray = $.map(results, function (value, key) { return { value: value, data: key }; });
        $('.autocomplete-quotation').autocomplete({
            lookup: searchresultsArray,
            appendTo: ".quotation-input-form"
        });

        $('#fs-date').countdown({
            date: '<?php echo flashsaleenddate(); ?>', // gmt + 0
            day: 'DAY',
            days: 'DAYS',
            hour: 'HOUR',
            hours: 'HOURS',
            minute: 'MIN',
            minutes: 'MINS',
            second: 'SEC',
            seconds: 'SECS'
        }, function () {
          //alert('Flash sale ended!');
          $(".section-flashsale").hide();
        });
    });
</script>

</html>