<?php include("header.php"); 


	//SELECT SUB LEVEl

	if(isset($_GET['idsubkat'])): $idsubkat = replaceUrel($_GET['idsubkat']); else: $idsubkat = 0; endif;

	if(isset($_GET['namesubkat'])): $namesubkat = replaceUrel($_GET['namesubkat']); else: $namesubkat = ''; endif;

	

	$subkatList = global_select_single("subcategory","*"," `id` = '$idsubkat' and `publish` = 1 ");

	//select category

	$idkat = $subkatList['idkat'];

	$namekat = generalselect("category", "name"," `id` = '$idkat' and `publish` = 1 ");	

?>

</head>



<body>



<?php include("head.php"); ?>


<section id="breadcrumbs">

	<div class="container">

    	<div class="row">

        	<ul class="breadcrumbs">

            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>

                <li><a <?php echo'href="'.$GLOBALS['SITE_URL'].'product-category/'.replace($namekat).'/'.$idkat.'" title=""';?>><?php echo replacebr($namekat);?></a></li>

                <li class="f-pb"><?php echo replacebr($subkatList['name']);?></li>

            </ul><!-- .breadcrumbs -->

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- #breadcrumbs -->

<?php if($subkatList['banner_image']!=""):?>
    <section id="desktop-slider">
        <div class="container">
            <span class="slider-mask"></span>
            <div id="desktop-flex" class="flexslider">
            <ul class="slides">
                <?php echo'<li><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$subkatList['banner_image'].'" class="flexImages" alt="" /></li>';?>
            </ul><!-- .slides -->
        </div><!-- #desktop-flex -->
     </div><!-- .container -->
    </section><!-- #desktop-slider -->
<?php endif;?>

<section id="template-page" class="section one-column">

	<div class="container">

    	<div class="row">

        	<div class="template-wrap">

            	<ul class="top-bc-wrap">

                	<?php include("cart-list-link.php");?>

                </ul><!-- .top-bc-wrap -->

                <h1 class="f-pb"><?php echo replacebr($subkatList['name']);?></h1>

                <ul class="prod-cat-wrap">

                	<?php productsublevel($idkat,$subkatList['id']);?>  

                </ul><!-- .prod-cat-wrap -->

            </div><!-- .template-wrap -->

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- .section -->



<?php include("foot.php"); ?>

<?php include("footer.php"); ?>



<script type="text/javascript">

	$(document).ready(function() {

		$("#menu_list1").addClass("active");	

	});	

</script>



</body>

</html>