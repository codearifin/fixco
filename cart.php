<?php include("header.php");$jml_item = $jcart->items_num(); ?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Shopping Cart</li>
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
                <h1 class="f-pb">Shopping Cart</h1>
                
                <!-- SHOPPING CART COPY START HERE -->
                <div class="scart-wrap">
                    <div class="sc-top clearfix">
                        <div class="sct1">
                            <div class="sct1-cwrap">
                                <div class="sct123 clearfix">
                                    <span class="sct1-1">
                                        <span class="sct11-1">
                                            <span class="f-pb">Product</span>
                                        </span><!-- .sct11-1 -->
                                        <span class="sct11-2">
                                            <span class="f-pb">Price</span>
                                        </span><!-- .sct11-2 -->
                                    </span><!-- .sct1-1 -->
                                    <span class="sct1-23">
                                        <span class="sct1-2 f-pb">Qty</span>
                                        <span class="sct1-3 f-pb">Sub Total</span>
                                    </span><!-- .sct1-23 -->
                                </div><!-- .sct123 -->
                            </div><!-- .sct1-cwrap -->
                        </div><!-- .sct1 -->
                    </div><!-- .sc-top -->
                    
                    <div class="sc-bottom clearfix f-pb">
                         <div id="jcart"><?php $jcart->display_cart();?></div>             
                    </div><!-- .sc-bottom -->
                    
                    <div class="btn-wrap aright">
                        <?php 
							   if($jml_item>0):
								   if(isset($_SESSION['user_token'])==''):
									   echo'<a href="'.$GLOBALS['SITE_URL'].'login-order" class="btn btn-red no-margin f-psb btn-checkout nuke-fancied2">Checkout</a>';
								   else:
								   	  if($_SESSION['user_statusmember']=="REGULAR MEMBER"):
									  		echo'<a href="'.$GLOBALS['SITE_URL'].'finishorder" class="btn btn-red no-margin f-psb btn-checkout">Checkout</a>';
									  else:
									  		echo'<a href="'.$GLOBALS['SITE_URL'].'finishorder-corporate" class="btn btn-red no-margin f-psb btn-checkout">Checkout</a>';
									  endif;	
								   endif;							   
							   endif;
						?>
                    </div><!-- .btn-wrap -->
                </div><!-- .scart-wrap -->
            	<!-- SHOPPING CART COPY END HERE -->
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>


<script>
$(document).ready(function() {
	$("#menu_list2").addClass("active");		
});
</script>

</body>
</html>