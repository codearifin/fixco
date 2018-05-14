<?php include("header.php"); 
   $row = global_select_single("aboutus","*");	
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Tentang Kami</li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section">
	<div class="container">
    	<div class="row">
        	<aside id="template-sidebar" class="ts-ads-wrap">
                <div class="ts-child">
                	<h3 class="f-pb">TENTANG KAMI</h3>
                    <ul class="ts-menu">
                    	<?php include("side-aboutus.php");?>
                    </ul><!-- .ts-menu -->
                </div><!-- .ts-child -->
            </aside><!-- #product-sidebar -->
            <div class="template-wrap">
                <h1 class="f-pb"><?php echo $row['title'];?></h1>
                <div class="nuke-wysiwyg">
                   <?php if($row['about_image']<>''): echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['about_image'].'" alt="'.$row['title'].'" />'; endif;?>	
                   <?php echo $row['description'];?>
                </div><!-- .nuke-wysiwyg -->
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".menu1").addClass("active");	
	});	
</script>

</body>
</html>