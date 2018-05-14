<?php include("header.php"); ?>

</head>



<body>



<?php include("head.php"); ?>



<section id="breadcrumbs">

	<div class="container">

    	<div class="row">

        	<ul class="breadcrumbs">

            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>

                <li class="f-pb">Corporate Member</li>

            </ul><!-- .breadcrumbs -->

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- #breadcrumbs -->



<section id="template-page" class="section">

	<div class="container">

    	<div class="row">

        	<aside id="template-sidebar" class="ts-ads-wrap">

                <div class="ts-child">

                	<h3 class="f-pb" style="text-transform:uppercase;">Informasi Pelanggan</h3>

                    <ul class="ts-menu">
                         
                         <?php include("side-information.php");?>

                    </ul><!-- .ts-menu -->

                </div><!-- .ts-child -->

            </aside><!-- #product-sidebar -->

            <div class="template-wrap">

                <h1 class="f-pb">Warranty</h1>

                <div class="nuke-wysiwyg">

                   <?php pages(23);?>

                </div><!-- .nuke-wysiwyg -->

            </div><!-- .template-wrap -->

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- .section -->



<?php include("foot.php"); ?>

<?php include("footer.php"); ?>



<script type="text/javascript">

	$(document).ready(function() {

		$(".menu3_waranty").addClass("active");	

	});	

</script>



</body>

</html>