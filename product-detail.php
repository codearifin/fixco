<?php include("header.php"); 

//select prod

if(isset($_GET['idprod'])): $idprodList = replaceUrel($_GET['idprod']); else: $idprodList = 0; endif;

$query = $db->query("SELECT * FROM `product` WHERE `id`='$idprodList' and `publish`=1 ") or die($db->error);

$row = $query->fetch_assoc();

$idprod = $row['id']; $totalstock = gettotalstock($idprod);

$idproddetail_first = getproddetaillist($idprod);	

if($idprod < 1):

	echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'product"</script>';

endif;

?>

</head>



<body>



<?php include("head.php"); ?>



<section id="breadcrumbs">

	<div class="container">

    	<div class="row">

        	<ul class="breadcrumbs">

            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>

                <?php getlinkurelproddetail($row['idkat'],$row['idsubkat'],$row['idsublevel']);?>

                <li class="f-pb"><?php echo $row['name'];?></li>

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

                

                <div id="product-detail">

                    <div class="new-pdtop-wrapper">

                    	<div class="product-detail-top">

                            <div class="pdt-left">

                                <div class="pdtl-large">

                                    <div class="img-wrap">

                                    	<?php getproductimg($idprod,$row['image']);?>

                                        <?php if($row['discount_value']>0):?>

                                            <div class="ctab-badge-wrap">

                                                <span class="ctab-badge f-psb"><?php echo $row['discount_value'];?>% OFF</span>

                                            </div><!-- .ctab-badge-wrap -->

                                        <?php endif;?>

                                    </div>

                                </div><!-- .pdtl-large -->

                                <div id="gallery_01" class="pdtl-thumb">

                                    <?php getproductimgList($idprod,$row['image']);?>

                                </div><!-- .pdtl-thumb -->

                            </div><!-- .pdt-left -->

                            

                            <div class="pdt-right">

                                <div class="prod-label-wrap">

                                	<?php

                                  		    if(strtolower($row['product_terbaik'])=="produk terbaik"): echo'<span class="prod-label">Produk Terbaik</span>'; endif;

                                        	if(strtolower($row['product_unggulan'])=="produk unggulan"): echo'<span class="prod-label">Produk Unggulan</span>'; endif;

    								?>		

                                </div><!-- .prod-label-wrap -->

                                <div class="pdtr-heading">

                                	<h1><?php echo $row['name'];?></h1>

                                    <div class="prod-raty-wrap">

                                        <div class="prod-raty" data-score="<?php echo ulasanproductbintang($idprod);?>"></div>

                                        <span class="pr-number">(<?php echo gettotalreview($idprod);?> review)</span>

                                    </div><!-- .prod-raty-wrap -->

                                </div><!-- .pdtr-heading -->

                                <div class="pdtr-content">

                                	<?php echo $row['short_description'];?>

                                   

                                    <div class="pdtr-table-wrap tablewrapper-detail">
    									<?php if($idproddetail_first>0): getprodpriceFirsttbale($idprod,$idproddetail_first); endif;?>
                                    </div><!-- .pdtr-table-wrap -->

                                    

                                    

                                    <input type="hidden" name="idprodlist_uid" class="idprodlist_uid" value="<?php echo $idprod;?>" />

                                    <div class="pdtr-bottom">

                                    	<div class="form-group">

                                        	<label style="font-size:13px; display:inline-block; width:80px;"><?php echo $row['product_detail_label'];?>:</label>

                                            <div class="input-wrap">

                                            	<div class="select-style">

                                                	<select name="product_detail" class="idproddetail_uid">

                                                    	<?php productlistdetail($idprod,$row['product_detail_label']);?>

                                                    </select>

                                                </div><!-- .select-style -->

                                            </div><!-- .input-wrap -->

                                        </div><!-- .form-group -->
                                        
                                        <div style="height:10px;"></div>
                                        
                                        <div class="form-group">

                                        	<label style="font-size:13px; display:inline-block; width:80px;">Qty:</label>

                                            <div class="input-wrap">

                                            	<input type="text" value="1" name="qty_productlist" class="qty_productlist" onKeyUp="rupiah(this)" style="max-width:60px;" />

                                            </div><!-- .input-wrap -->

                                        </div><!-- .form-group -->

                                        <br>

                                        <div class="prod-price-wrap wrapper_price_product">

                                        	<?php
    											  if($idproddetail_first>0):
    											  	 getprodpriceFirst($idprod,$idproddetail_first);
    												 
    											  else:	
    												   
    												   if($row['discount_value']>0):
    														$diskonval1 = ($row['price']*$row['discount_value'])/100;
    														$diskonval2 = round($diskonval1);
    														$diskonval3 = $row['price']-$diskonval1;
    														echo'<span class="old-price">Rp '.number_format($row['price']).',-</span>';
    														echo'<span class="prod-price f-pb">Rp '.number_format($diskonval3).',-</span>';	
    	
    													else:	
    														echo'<span class="prod-price f-pb">Rp '.number_format($row['price']).',-</span>';	
    													endif;
    													
    												endif;

    										?>

                                        </div><!-- .prod-price-wrap -->

                                        

                                        <div class="form-button">

                                        	<?php 

    												if($totalstock>0):

    													echo'<a href="" class="btn btn-red no-margin f-psb add_to_cartbtn"><span>ADD TO CART</span></a>';

    												else:

    													if(strtolower($row['soldout_contactus'])=="yes"):

    														echo'<a href="'.$GLOBALS['SITE_URL'].'contact" class="btn btn-red no-margin f-psb"><span>CONTACT US</span></a>';

    													else:

    														echo'<a class="btn no-margin f-psb soldoutbtn"><span>SOLD OUT</span></a>';

    													endif;

    												endif;	

    										?> 

                                        </div><!-- .form-button -->
                                        <div id="share"></div>

                                        <div class="table-wrap">

                                        	<?php /*

                                            <table cellspacing="0" cellpadding="0" class="price-table">

                                        		<tr>

                                                	<th>Qty</th>

                                                    <td>10</td>

                                                    <td>100</td>

                                                    <td>500</td>

                                                    <td>1000</td>

                                                </tr>

                                                <tr>

                                                	<th>Harga</th>

                                                    <td>2.807.000</td>

                                                    <td>2.707.000</td>

                                                    <td>2.507.000</td>

                                                    <td>2.307.000</td>

                                                </tr>

                                        	</table>*/?>

                                        </div><!-- .table-wrap -->



                                    </div><!-- .pdtr-bottom -->	

                                </div><!-- .pdtr-content -->

                            </div><!-- .pdt-right -->

                        </div><!-- .new-pdtop-wrapper -->

                        <div class="pd-top-info">
                            <div class="item">
                                <div class="why-fixcomart">
                                    <h3>Why Fixcomart?</h3>
                                    <?php echo whyfixcomart(); ?>
                                    
                                </div><!-- .why-fixcomart -->
                            </div><!-- .item -->
                        </div><!-- .pd-top-info -->

                    </div><!-- .product-detail-top -->

                    

                    <div class="product-detail-bottom">

                    	<h2>Deskripsi Produk</h2>

                        <div class="nuke-wysiwyg">

                        	<?php echo $row['description'];?>	

                        </div><!-- .nuke-wysiwyg -->

                    </div><!-- .product-detail-bottom -->

                    

                    <div class="product-detail-bottom prod-spec">

                    	<h2>Spesifikasi Produk</h2>

                        <div class="nuke-wysiwyg">

                        	<?php echo $row['specification_description'];?>	

                        </div><!-- .nuke-wysiwyg -->

                      

                        <div class="table-wrap">

                           <?php attributeList($idprod);?>

                        </div><!-- .table-wrap -->

                    </div><!-- .product-detail-bottom -->

                    

                    <div class="product-detail-bottom">

                    	<h2>Lihat Produk Lainnya</h2>

                        <div class="other-product">

                            <div class="owl-ctab">

                                <?php relatedproduct($idprod,$row['idkat'],$row['idsubkat'],$row['idsublevel']);?>  

                            </div><!-- .owl-ctab -->

                        </div><!-- .other-product -->

                    </div><!-- .product-detail-bottom -->

                    

                    <div class="product-detail-bottom">

                    	<h2>Ulasan Produk</h2>

                        <div class="ulasan-list">

                        	<?php ulasanproduct($idprod);?>                             

                        </div><!-- .ulasan-list -->

                        
                       <?php if(isset($_SESSION['user_token'])!=''){?>  

                        <div class="add-ulasan">
                        
                          <form action="<?php echo $GLOBALS['SITE_URL'];?>do-insertdata-ulasan" method="post" class="general-form ulasan-form" id="ratingproduct_list">

                           	<input type="hidden" name="idprod" value="<?php echo $idprod;?>" />

                            <input type="hidden" name="urlpage" value="<?php echo $_SERVER['REQUEST_URI'];?>" />
                            
                            <input type="hidden" name="name" value="<?php getusernamemember($_SESSION['user_token']);?>" />


                            <h3 class="f-pb">Berikan rating untuk produk ini: <span class="raty-rating"></span></h3>


                                <div class="form-group">

                                	<label class="f-pb">Judul Ulasan</label>

                                    <div class="input-wrap">

                                    	<input type="text" placeholder="Judul ulasan Anda" name="judul" maxlength="200" />

                                    </div><!-- .input-wrap -->

                                </div><!-- .form-group -->

                                <div class="form-group">

                                	<label class="f-pb">Ulasan Anda</label>

                                    <div class="input-wrap">

                                    	<textarea name="description"></textarea>

                                    </div><!-- .input-wrap -->

                                </div><!-- .form-group -->

                                <div class="form-button">

                                	<input type="submit" value="SUBMIT ULASAN" name="submit" class="btn btn-red no-margin f-psb" />

                                </div><!-- .form-button -->

                            </form>
                           
                            </div><!-- .add-ulasan -->
                             
                           
						   <?php }else { 
						   
						   			// echo '<p>Silahkan login untuk memberikan komentar pada produk ini.</p>'; 
						   			// echo'<a href="'.$GLOBALS['SITE_URL'].'login-review-product" class="btn btn-red no-margin f-psb btn-checkout nuke-fancied2">Login</a>';
                                    echo '<div class="add-ulasan">
                                                <h3 class="f-pb">Silakan Login Dahulu</h3>
                                                <p>Anda harus login terlebih dahulu sebelum bisa memberikan ulasan.</p>
                                            </div><!-- .add-ulasan -->';
						   }?>

                       

                    </div><!-- .product-detail-bottom -->

                    

                </div><!-- #product-detail -->

                

            </div><!-- .template-wrap -->

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- .section -->



<?php include("foot.php"); ?>

<?php include("footer.php"); ?>



<script>

	$(window).bind("resize", function(){ // scroll event

		$('.pdtl-large .img-wrap').each(function() {

			var dwidth = $(this).width();

			var dHeight = $(this).height();

			$(".zoomContainer").css({"width" : dwidth+2, "height" : dHeight+2}); // +2 becoz of border

		});

	});

</script>



<script>
$(window).bind("resize", function(){ // scroll event
        $('.pdtl-large .img-wrap').each(function() {
            var dwidth = $(this).width();
            var dHeight = $(this).height();
            $(".zoomContainer").css({"width" : dwidth+2, "height" : dHeight+2}); // +2 becoz of border
        });
    });

$(document).ready(function() {	
	//RATY RNT

	$('div.prod-raty').raty({

	  score: function() {

		return $(this).attr('data-score');

	  },

	  path: '<?php echo $GLOBALS['SITE_URL'];?>images',

	  readOnly: true,

	  noRatedMsg : "Not rated yet!"

	});

	

	$('.raty-rating').raty({

	  path: '<?php echo $GLOBALS['SITE_URL'];?>images',

	  noRatedMsg : "Not rated yet!"

	});

	$("#menu_list1").addClass("active");

    $("#share").jsSocials({
        showLabel: true,
        showCount: true,
        shares: ["twitter", "facebook"]
    }); 
});

</script>



</body>

</html>