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
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL']; ?>" class="bc-home">Home</a></li>
                <li><a href="<?php echo $GLOBALS['SITE_URL'].'blog/'.$category; ?>"><?php echo $category; ?></a></li>
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
                <h2 class="ngc-maintitle">Related <?php echo $category; ?></h2>
                <div class="related-blog">
                    <div class="related-blog-carousel">
                        <?php relatedblog($category,$url); ?>
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