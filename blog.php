<?php include("header.php"); 
if(isset($_GET['category'])){
    $category = $_GET['category'];
}else{
    $category = NULL;
}

if(isset($_GET['page'])){
    $page = $_GET['page'];
}else{
    $page = 1;
}

?>
</head>

<body>

<?php include("head.php"); ?>

<section class="static-banner">
    <?php getblogbanner($category); ?>
</section><!-- .static-banner -->

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="index.php" class="bc-home">Home</a></li>
                <li class="f-pb">
                    <?php if($category != NULL) echo ucfirst($category); else getblogbreadcrumb(); ?>
                </li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->


<section id="template-page" class="section">
	<div class="container">
        <div class="row">
            <?php if($category != NULL) echo bloglist(ucfirst($category),$page); else bloglist(NULL,$page); ?>
                
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>


</body>
</html>