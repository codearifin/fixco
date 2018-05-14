<?php include("header.php"); 
	
	//SELECT SUB CATEGORY
	if(isset($_GET['idkat'])): $idkat = replaceUrel($_GET['idkat']); else: $idkat = 0; endif;
	if(isset($_GET['namekat'])): $namekat = replaceUrel($_GET['namekat']); else: $namekat = ''; endif;
	
	$katList = global_select_single("category","*"," `id` = '$idkat' and `publish` = 1 ");
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb"><?php echo replacebr($katList['name']);?></li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section one-column">
	<div class="container">
    	<div class="row">
        	<div class="template-wrap">
            	<ul class="top-bc-wrap">
                	<?php include("cart-list-link.php");?>
                </ul><!-- .top-bc-wrap -->
                <h1 class="f-pb"><?php echo replacebr($katList['name']);?></h1>
                <ul class="prod-cat-wrap">
                	<?php productsubcategory($katList['id']);?>  
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