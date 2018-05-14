<?php include("header.php"); 

	if(isset($_SESSION['useridprod_compare1'])): $idcompare1 = $_SESSION['useridprod_compare1']; else: $idcompare1 = ''; endif;
	if(isset($_SESSION['useridprod_compare2'])): $idcompare2 = $_SESSION['useridprod_compare2']; else: $idcompare2 = ''; endif;
	if(isset($_SESSION['useridprod_compare3'])): $idcompare3 = $_SESSION['useridprod_compare3']; else: $idcompare3 = ''; endif;
	if(isset($_SESSION['useridprod_compare4'])): $idcompare4 = $_SESSION['useridprod_compare4']; else: $idcompare4 = ''; endif;
	
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Bandingkan Produk</li>
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
                <h1 class="f-pb">Bandingkan Produk</h1>
                
                <div class="compare-wrap">
                      <?php if($idcompare1 < 1 and $idcompare2 < 1 and $idcompare3 < 1 and $idcompare4 < 1): 
								echo'<span class="notfound">Please select product to compare!</span>'; 
							 endif;
						?>                
                	
                    <?php if($idcompare1 < 1 and $idcompare2 < 1 and $idcompare3 < 1 and $idcompare4 < 1): else:?>
                   
                        <div class="side-compare">
                            <div class="side-item first cvalue-1"></div><!-- .side-item -->
                            <div class="side-item second cvalue-2"><span>Harga</span></div><!-- .side-item -->
                            <div class="side-item cvalue-3"><span>Brand</span></div><!-- .side-item -->
                            <div class="side-item cvalue-6"><span>Weight</span></div><!-- .side-item -->
                        </div><!-- .side-compare -->
                        <div class="main-compare">
                            <div class="owl-compare">
                                <?php getproductlist($idcompare1);?>
                                <?php getproductlist($idcompare2);?>
                                <?php getproductlist($idcompare3);?>
                                <?php getproductlist($idcompare4);?>   
                            </div><!-- .owl-compare -->
                        </div><!-- .main-compare -->
                    <?php endif;?>
                    
                    
                </div><!-- .compare-wrap -->
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script>
	(function($){
		function fixValue1() {
			var heights = new Array();
			
			// Loop to get all element heights
			$('.cvalue-1').each(function() {	
				// Need to let sizes be whatever they want so no overflow on resize
				$(this).css('min-height', '0');
				$(this).css('max-height', 'none');
				$(this).css('height', 'auto');
	
				// Then add size (no units) to array
				heights.push($(this).height());
			});
	
			// Find max height of all elements
			var max = Math.max.apply( Math, heights );
	
			// Set all heights to max height
			$('.cvalue-1').each(function() {
				$(this).css('height', max);
			});	
		}
		function fixValue2() {
			var heights = new Array();
			
			// Loop to get all element heights
			$('.cvalue-2').each(function() {	
				// Need to let sizes be whatever they want so no overflow on resize
				$(this).css('min-height', '0');
				$(this).css('max-height', 'none');
				$(this).css('height', 'auto');
	
				// Then add size (no units) to array
				heights.push($(this).height());
			});
	
			// Find max height of all elements
			var max = Math.max.apply( Math, heights );
	
			// Set all heights to max height
			$('.cvalue-2').each(function() {
				$(this).css('height', max);
			});	
		}
	
	
		$(window).load(function() {
			// Fix heights on page load
			fixValue1();
			fixValue2();
	
			// Fix heights on window resize
			$(window).resize(function() {
				// Needs to be a timeout function so it doesn't fire every ms of resize
				setTimeout(function() {
					fixValue1();
					fixValue2();
				}, 120);
			});
		});
	})(jQuery);
	
	$(document).ready(function() {
		$(".owl-compare").owlCarousel({
			autoPlay: 5000,
			items : 4,
			itemsDesktop : [1190,4],
			itemsDesktopSmall : [990,4],
			itemsTablet: [900,3],
			itemsTabletSmall : [640, 2],
			itemsMobile : [360, 1],
			navigation: true,
			pagination: false
		});
	});
</script>

</body>
</html>