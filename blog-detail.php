<?php include("header.php"); 
if(isset($_GET['category'])){
    $category = $_GET['category'];
}else{
    $category = NULL;
}

if(isset($_GET['blogurl'])){
    $url = $_GET['blogurl'];
}else{
    $url = "";
}
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="index.php" class="bc-home">Home</a></li>
                <li><a href="blog.php">News</a></li>
                <li class="f-pb">Detail</li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section">
	<div class="container">
        <div class="row">
            
            <?php blogdetail($category,$url); ?>

            <div class="related-blog-wrap">
                <h2 class="ngc-maintitle">Related News</h2>
                <div class="related-blog">
                    <div class="related-blog-carousel">
                        <div class="item">
                            <div class="blog-child">
                                <div class="ngc-media">
                                    <a href="blog-detail.php">
                                        <img src="images/blog/thumb-1.jpg" alt="BLOG TITLE HERE" class="lazyload" data-expand="-10">
                                        <span class="blog-date">
                                            <span class="large">21</span>
                                            MAY
                                        </span>
                                    </a>
                                </div><!-- .ngc-media -->
                                <div class="ngc-text">
                                    <h3 class="ngc-title">
                                        <a href="blog-detail.php">
                                            In Hac Habitasse Platea Dictumst Vivamus
                                        </a>
                                    </h3>
                                    <p>In hac habitasse platea dictumst. Vivamus adipiscing fermentum quam volutpat aliquam. Integer et elit eget volutpat aliquam.</p>
                                    <a href="blog-detail.php" class="read-more">Read More <span class="fa fa-angle-right"></span></a>
                                </div><!-- .ngc-text -->
                            </div><!-- .blog-child -->
                        </div><!-- .item -->
                        <div class="item">
                            <div class="blog-child">
                                <div class="ngc-media">
                                    <a href="blog-detail.php">
                                        <img src="images/blog/thumb-2.jpg" alt="BLOG TITLE HERE" class="lazyload" data-expand="-10">
                                        <span class="blog-date">
                                            <span class="large">28</span>
                                            APR
                                        </span>
                                    </a>
                                </div><!-- .ngc-media -->
                                <div class="ngc-text">
                                    <h3 class="ngc-title">
                                        <a href="blog-detail.php">
                                            Nam Porttitor Blandit Accumsan Ut vel di
                                        </a>
                                    </h3>
                                    <p>In hac habitasse platea dictumst. Vivamus adipiscing fermentum quam volutpat aliquam. Integer et elit eget elit facilisis.</p>
                                    <a href="blog-detail.php" class="read-more">Read More <span class="fa fa-angle-right"></span></a>
                                </div><!-- .ngc-text -->
                            </div><!-- .blog-child -->
                        </div><!-- .item -->
                        <div class="item">
                            <div class="blog-child">
                                <div class="ngc-media">
                                    <a href="blog-detail.php">
                                        <img src="images/blog/thumb-3.jpg" alt="BLOG TITLE HERE" class="lazyload" data-expand="-10">
                                        <span class="blog-date">
                                            <span class="large">21</span>
                                            MAY
                                        </span>
                                    </a>
                                </div><!-- .ngc-media -->
                                <div class="ngc-text">
                                    <h3 class="ngc-title">
                                        <a href="blog-detail.php">
                                            In Hac Habitasse Platea Dictumst Vivamus
                                        </a>
                                    </h3>
                                    <p>In hac habitasse platea dictumst. Vivamus adipiscing fermentum quam volutpat aliquam. Integer et elit eget volutpat aliquam.</p>
                                    <a href="blog-detail.php" class="read-more">Read More <span class="fa fa-angle-right"></span></a>
                                </div><!-- .ngc-text -->
                            </div><!-- .blog-child -->
                        </div><!-- .item -->
                        <div class="item">
                            <div class="blog-child">
                                <div class="ngc-media">
                                    <a href="blog-detail.php">
                                        <img src="images/blog/thumb-4.jpg" alt="BLOG TITLE HERE" class="lazyload" data-expand="-10">
                                        <span class="blog-date">
                                            <span class="large">28</span>
                                            APR
                                        </span>
                                    </a>
                                </div><!-- .ngc-media -->
                                <div class="ngc-text">
                                    <h3 class="ngc-title">
                                        <a href="blog-detail.php">
                                            Nam Porttitor Blandit Accumsan Ut vel di
                                        </a>
                                    </h3>
                                    <p>In hac habitasse platea dictumst. Vivamus adipiscing fermentum quam volutpat aliquam. Integer et elit eget elit facilisis.</p>
                                    <a href="blog-detail.php" class="read-more">Read More <span class="fa fa-angle-right"></span></a>
                                </div><!-- .ngc-text -->
                            </div><!-- .blog-child -->
                        </div><!-- .item -->
                    </div><!-- .related-blog-carousel -->
                </div><!-- .related-blog -->
            </div><!-- .related-blog-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script>
    $(document).ready(function(){
        $('.related-blog-carousel').slick({
            dots: true,
            arrows: false,
            slidesToShow: 2,
            slidesToScroll: 1,
            infinite: true,
            adaptiveHeight: true,
            autoplay: true,
            autoplaySpeed: 5000,
            responsive: [
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 1
                  }
                }
            ]
        });
        $("#share").jsSocials({
            showLabel: true,
            showCount: true,
            shares: ["twitter", "facebook"]
        });
    });
</script>


</body>
</html>