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
                    <div class="grid-child n-1-1per1 n-1-1per2 n-768-1per4 n-768-no-margin-bottom">
                        <div class="ads-banner">
                            <div class="ngc-media">
                                <a href=""><img src="images/ads/ads1.jpg" alt="PUT TITLE HERE" class="lazyload" data-expand="-10"></a>
                            </div><!-- .ngc-media -->
                        </div><!-- .ads-banner -->
                    </div><!-- .grid-child -->
                    <div class="grid-child n-1-1per1 n-1-1per2 n-768-1per4 n-768-no-margin-bottom">
                        <div class="ads-banner">
                            <div class="ngc-media">
                                <a href=""><img src="images/ads/ads2.jpg" alt="PUT TITLE HERE" class="lazyload" data-expand="-10"></a>
                            </div><!-- .ngc-media -->
                        </div><!-- .ads-banner -->
                    </div><!-- .grid-child -->
                    <div class="grid-child n-1-1per1 n-1-1per2 n-768-1per4 n-768-no-margin-bottom">
                        <div class="ads-banner">
                            <div class="ngc-media">
                                <a href=""><img src="images/ads/ads1.jpg" alt="PUT TITLE HERE" class="lazyload" data-expand="-10"></a>
                            </div><!-- .ngc-media -->
                        </div><!-- .ads-banner -->
                    </div><!-- .grid-child -->
                    <div class="grid-child n-1-1per1 n-1-1per2 n-768-1per4 n-768-no-margin-bottom">
                        <div class="ads-banner">
                            <div class="ngc-media">
                                <a href=""><img src="images/ads/ads2.jpg" alt="PUT TITLE HERE" class="lazyload" data-expand="-10"></a>
                            </div><!-- .ngc-media -->
                        </div><!-- .ads-banner -->
                    </div><!-- .grid-child -->
                </div><!-- .row -->
            </div><!-- .main-container -->
        </section><!-- .top-ads -->
    </div><!-- .righjt -->
</div><!-- .new-homepage-banner-wrap -->

<section class="below-banner-section">
    <div class="container">
        <div class="below-banner-carousel">
            <div class="wrap">
               <?php banneradshome();?>
            </div><!-- .wrap -->
        </div><!-- .below-banner-carousel -->
    </div><!-- .container -->
</section><!-- .below-banner-section -->



<section class="section section-quotation">
    <div class="container">
        <div class="row">
            <div class="grid-child n-768-2per5 n-768-no-margin-bottom">
                <h2 class="ngc-title">
                    <img src="images/request-quotation.png" alt="REQUEST FOR QUOTATION">
                </h2>
                <p class="ngc-intro">Kirim Permintaan Sesuai Kebutuhan Bisnis Anda dan Temukan Penawaran dengan Harga Terbaik.</p>
                <a href="" class="btn btn-red bigger n-no-margin">LEBIH LANJUT</a>
            </div><!-- .grid-child -->
            <div class="grid-child n-768-3per5 n-no-margin-bottom">
                <div class="request-quotation-box">
                    <h2 class="ngc-title">Dapatkan Penawaran Terbaik</h2>
                    <form class="general-form quotation-form">
                        <div class="form-group">
                            <label class="f-pb">Nama Produk</label>
                            <div class="input-wrap has-icon quotation-input-form">
                                <input type="text" class="autocomplete-quotation" placeholder="Masukkan Nama Produk yang anda minta… contoh: Tekiro Box Fullset">
                                <span class="fa fa-sticky-note icon"></span>
                            </div><!-- .input-wrap -->
                        </div><!-- .form-group -->
                        <div class="row medium-gutter">
                            <div class="grid-child n-1-1per2 n-540-1per4 n-no-margin-bottom">
                                <div class="form-group n-no-margin-bottom">
                                    <label class="f-pb">Jumlah</label>
                                    <div class="input-wrap">
                                        <input type="number" value="100">
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                            </div><!-- .grid-child -->
                            <div class="grid-child n-1-1per2 n-540-1per4 n-no-margin-bottom">
                                <div class="form-group n-no-margin-bottom">
                                    <label class="f-pb">Satuan</label>
                                    <div class="input-wrap">
                                        <div class="select-style">
                                            <select>
                                                <option>Pieces</option>
                                                <option>Karton</option>
                                                <option>Box</option>
                                            </select>
                                        </div><!-- .select-style -->
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                            </div><!-- .grid-child -->
                            <div class="grid-child n-1-1per1 n-540-1per2 n-no-margin-bottom">
                                <a href="quotation-request.php" class="btn btn-yellow">BUAT PERMINTAAN</a>
                            </div><!-- .grid-child -->
                        </div><!-- .row -->
                    </form><!-- .general-form -->
                </div><!-- .request-quotation-box -->
            </div><!-- .grid-child -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->



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
                <div class="row small-gutter">
                    <div class="grid-child n-1-1per1 n-540-1per2">
                        <div class="ads-banner">
                            <div class="ngc-media">
                                <a href=""><img src="images/ads/ads1.jpg" alt="PUT TITLE HERE" class="lazyload" data-expand="-10"></a>
                            </div><!-- .ngc-media -->
                        </div><!-- .ads-banner -->
                    </div><!-- .grid-child -->
                    <div class="grid-child n-1-1per1 n-540-1per2">
                        <div class="ads-banner">
                            <div class="ngc-media">
                                <a href=""><img src="images/ads/ads2.jpg" alt="PUT TITLE HERE" class="lazyload" data-expand="-10"></a>
                            </div><!-- .ngc-media -->
                        </div><!-- .ads-banner -->
                    </div><!-- .grid-child -->
                </div><!-- .row -->
                 <div class="row small-gutter">
                    <div class="grid-child n-1-1per1">
                        <div class="ads-banner">
                            <div class="ngc-media">
                                <a href=""><img src="images/ads/ads3.jpg" alt="PUT TITLE HERE" class="lazyload" data-expand="-10"></a>
                            </div><!-- .ngc-media -->
                        </div><!-- .ads-banner -->
                    </div><!-- .grid-child -->
                </div><!-- .row -->
                <div class="row small-gutter">
                    <div class="grid-child n-1-1per1 n-540-1per3">
                        <div class="ads-banner">
                            <div class="ngc-media">
                                <a href=""><img src="images/ads/ads1.jpg" alt="PUT TITLE HERE" class="lazyload" data-expand="-10"></a>
                            </div><!-- .ngc-media -->
                        </div><!-- .ads-banner -->
                    </div><!-- .grid-child -->
                    <div class="grid-child n-1-1per1 n-540-1per3">
                        <div class="ads-banner">
                            <div class="ngc-media">
                                <a href=""><img src="images/ads/ads2.jpg" alt="PUT TITLE HERE" class="lazyload" data-expand="-10"></a>
                            </div><!-- .ngc-media -->
                        </div><!-- .ads-banner -->
                    </div><!-- .grid-child -->
                    <div class="grid-child n-1-1per1 n-540-1per3">
                        <div class="ads-banner">
                            <div class="ngc-media">
                                <a href=""><img src="images/ads/ads3.jpg" alt="PUT TITLE HERE" class="lazyload" data-expand="-10"></a>
                            </div><!-- .ngc-media -->
                        </div><!-- .ads-banner -->
                    </div><!-- .grid-child -->
                </div><!-- .row -->
            </div><!-- .bottom-ads -->

            <?php productescalatornew();?>

            <div class="index-blog">
                <?php bloglistindex(); ?>

                <div class="n-align-center">
                    <a href="blog" class="btn btn-red bigger n-no-margin">LIHAT LEBIH</a>
                </div>
            </div><!-- .index-blog -->

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- .index-product -->



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

        /*$('#fs-date').countdown({
            date: '5/7/2018 4:02:30', // gmt + 0
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
        });*/
    });
</script>

</html>