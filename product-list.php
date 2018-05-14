<?php include("header.php"); 						

if(isset($_SERVER['REQUEST_URI'])):
	$server_uri_prod = $_SERVER['REQUEST_URI'];
else:
	$server_uri_prod = '';	
endif;

if(isset($_SESSION['UrelPageidprod'])):
	$UrelPageidprod = $_SESSION['UrelPageidprod'];
else:
	$UrelPageidprod = '';	
endif;

if($server_uri_prod<>$UrelPageidprod):
	unset($_SESSION['filter_brandid']);
	unset($_SESSION['filter_price1']);
	unset($_SESSION['filter_price2']);
	unset($_SESSION['filter_rate5']);
	unset($_SESSION['filter_rate4']);
	unset($_SESSION['filter_rate3']);
	unset($_SESSION['filter_rate2']);
	unset($_SESSION['filter_rate1']);
	unset($_SESSION['filter_rate0']);
	unset($_SESSION['master_attibutelist']);
endif;

//idkat
if(isset($_GET['idkat'])): $idkat = replaceUrel($_GET['idkat']); else: $idkat = 0; endif;
if(isset($_GET['namekat'])): $namekat = replaceUrel($_GET['namekat']); else: $namekat = ''; endif;

//subkat
if(isset($_GET['idsubkat'])): $idsubkat = replaceUrel($_GET['idsubkat']); else: $idsubkat = 0; endif;
if(isset($_GET['namesubkat'])): $namesubkat = replaceUrel($_GET['namesubkat']); else: $namesubkat = ''; endif;

//idsublevel
if(isset($_GET['idsublevel'])): $idsublevel = replaceUrel($_GET['idsublevel']); else: $idsublevel = 0; endif;
if(isset($_GET['namesublevel'])): $namesublevel = replaceUrel($_GET['namesublevel']); else: $namesublevel = ''; endif;



//keyword
if(isset($_SESSION['keyword_data'])): $keyword = $_SESSION['keyword_data']; $keyword_cari = $_SESSION['keyword_data']; else: $keyword = ''; $keyword_cari = ''; endif;
if(isset($_GET['sidsubkat'])): $sidsubkat = $_GET['sidsubkat']; else: $sidsubkat = ''; endif;


//get active brudchrumb
if($idsublevel > 0):
	$namelistdata = generalselect("sub_level_category", "name"," `id` = '$idsublevel' and `publish` = 1 ");	
	$idkatbrand = getidkatband($idsublevel);
	$limitdefault = 30;

	
elseif($idsubkat > 0):
	$namelistdata = generalselect("subcategory", "name"," `id` = '$idsubkat' and `publish` = 1 ");	
	$idkatbrand = getidkatbandsubkat($idsubkat);
	$limitdefault = 30;


elseif($idkat > 0):	
	$namelistdata = generalselect("category", "name"," `id` = '$idkat' and `publish` = 1 ");	
	$idkatbrand = $idkat;
	$limitdefault = 30;

elseif($keyword!=''):
	$namelistdata = 'Search result for "'.$keyword_cari.'"';
	$idkatbrand = 0;
	$limitdefault = 100;

else:
	$namelistdata = 'Semua Produk';	
	$idkatbrand = 0;	
	$limitdefault = 100;
endif;


//filter session area
if(isset($_SESSION['filter_brandid'])):  $brandlistuid = $_SESSION['filter_brandid']; else: $brandlistuid = ''; endif;
if(isset($_SESSION['filter_price1'])): $priceRange1 = $_SESSION['filter_price1']; else: $priceRange1 = ''; endif;
if(isset($_SESSION['filter_price2'])): $priceRange2 = $_SESSION['filter_price2']; else: $priceRange2 = ''; endif;
if(isset($_SESSION['filter_rate5'])): $bintang5 = $_SESSION['filter_rate5']; else: $bintang5 = ''; endif;
if(isset($_SESSION['filter_rate4'])): $bintang4 = $_SESSION['filter_rate4']; else: $bintang4 = ''; endif;
if(isset($_SESSION['filter_rate3'])): $bintang3 = $_SESSION['filter_rate3']; else: $bintang3 = ''; endif;
if(isset($_SESSION['filter_rate2'])): $bintang2 = $_SESSION['filter_rate2']; else: $bintang2 = ''; endif;
if(isset($_SESSION['filter_rate1'])): $bintang1 = $_SESSION['filter_rate1']; else: $bintang1 = '';endif;
if(isset($_SESSION['filter_rate0'])): $bintang0 = $_SESSION['filter_rate0']; else: $bintang0 = '';endif;

if(isset($_SESSION['master_attibutelist'])): $Listmaster_attribute = $_SESSION['master_attibutelist']; else: $Listmaster_attribute = ''; endif;

//default price range
if($priceRange1>0): $default_price1 = replaceamount($priceRange1); else: $default_price1 = 100000; endif;
if($priceRange2>0): $default_price2 = replaceamount($priceRange2); else: $default_price2 = 1500000; endif;

//get filter brand								
$labelcari_brand = getlabelcaribrand($brandlistuid,$idkatbrand);	

//get price
if($priceRange1>=0 and $priceRange2>0):
	$fixprice1 = replaceamount($priceRange1);
	$fixprice2 = replaceamount($priceRange2)+1;
	$labelcari_price = " and ( `product`.`price` between '$fixprice1' and '$fixprice2' ) ";
else:
	$fixprice1 = ''; $fixprice2 = '';
	$labelcari_price = '';	
endif;	

//star filter
$filterstarprod = getfilterstarprod($bintang5,$bintang4,$bintang3,$bintang2,$bintang1,$bintang0);
//attribute data
$labelmasterAttrList = getdatalistfilterprod($Listmaster_attribute,$idkat,$idsubkat,$idsublevel);
//end filter session area

if($idkat>0):
	$file = "".$GLOBALS['SITE_URL']."product-list-category/".$namekat."/".$idkat."";	
	$filter_cari = " and `product`.`idkat` = '$idkat' ";
	$session_sizename = 'alprodcate_sizeid';

elseif($idsubkat>0):
	$file = "".$GLOBALS['SITE_URL']."product-list-subcategory/".$namesubkat."/".$idsubkat."";	
	$filter_cari = " and `product`.`idsubkat` = '$idsubkat' ";
	$session_sizename = 'alprodsubkat_sizeid';

elseif($idsublevel>0):
	$file = "".$GLOBALS['SITE_URL']."product-list-sublevel/".$namesublevel."/".$idsublevel."";	
	$filter_cari = " and `product`.`idsublevel` = '$idsublevel' ";
	$session_sizename = 'alprodsublevel_sizeid';	


elseif($keyword!=""):
	$file = "".$GLOBALS['SITE_URL']."search-product-result/".$sidsubkat."";	
	
	$tmpdatacari_new = '';
	$pecah_keyword = explode(" ",$keyword_cari);
	$jumdata_cari = count($pecah_keyword);
	if($jumdata_cari < 4){
		for($iicari = 0; $iicari<$jumdata_cari; $iicari++){
			$tmpdatacari_new.=" or `product`.`name` LIKE '%".$pecah_keyword[$iicari]."%' ";
		}
	}
	
	if($sidsubkat == "" or $sidsubkat =="all"):
		$filter_cari = " and ( `product`.`name` LIKE '%$keyword_cari%' ".$tmpdatacari_new." ) ";
	else:
		$namesubkatget = labelkategory($sidsubkat);
		$filter_cari = " and ".$namesubkatget." ( `product`.`name` LIKE '%$keyword_cari%' ".$tmpdatacari_new." ) ";
	endif;
	
	$session_sizename = 'alprodcari_sizeid';
else:
	$file = "".$GLOBALS['SITE_URL']."product";	
	$filter_cari = '';
	$session_sizename = 'alprod_sizeid';	
endif;

//global query

if(isset($_SESSION['sortfilterProdpage'])): $sortfilterProdpage = $_SESSION['sortfilterProdpage']; else: $sortfilterProdpage = $limitdefault; endif;

$batas = $sortfilterProdpage;

if(isset($_GET['halaman'])):
	$halaman = $_GET['halaman'];
else:
	$halaman = 1;	
endif;



if(empty($halaman)):
	$posisi = 0;
	$halaman = 1;	
else: 
	$posisi=($halaman-1) * $batas; 
endif; 

if(isset($_SESSION['sortfilterProd'])): $sortitem = $_SESSION['sortfilterProd']; else: $sortitem =''; endif;
if($sortitem<>''):
	$itemexlode = explode("#",$sortitem);
	$sortprodutby = ' `product`.`'.$itemexlode[0].'` '.$itemexlode[1].' ';
else:
	$sortprodutby = ' `product`.`sortnumber` DESC ';
endif;

$sql2 = $db->query("SELECT `product`.`id` as prodid FROM `product` WHERE 1=1 ".$labelmasterAttrList." ".$filterstarprod." ".$labelcari_brand." ".$labelcari_price." ".$filter_cari." and `product`.`publish`=1 ORDER BY ".$sortprodutby." ") or die($db->error);
$jmldata = $sql2->num_rows;
$jmlhalaman = ceil($jmldata/$batas); 
	
//main query
$query = $db->query("SELECT `product`.`id` as prodid FROM `product` WHERE 1=1 ".$labelmasterAttrList."  ".$filterstarprod." ".$labelcari_brand." ".$labelcari_price." ".$filter_cari." and `product`.`publish`=1 ORDER BY ".$sortprodutby." LIMIT $posisi,$batas") 
or die($db->error);
?>

</head>



<body>



<?php include("head.php"); ?>



<section id="breadcrumbs">

	<div class="container">

    	<div class="row">

        	<ul class="breadcrumbs">

            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>

                <li><a href="<?php echo $GLOBALS['SITE_URL'];?>product">Produk</a></li>

                <?php getlinkurelprod($idkat,$idsubkat,$idsublevel);?>

                <li class="f-pb"><?php echo replacebr($namelistdata);?></li>

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

                <h1 class="f-pb"><?php echo replacebr($namelistdata);?></h1>

                

                <div id="product-list">

                	<aside id="product-sidebar">

                    	<div class="ps-child">

                        	<h3 class="f-pb psc-toggle"><span>BRAND</span></h3>

                            <div class="psc">

                                <form action="<?php echo $GLOBALS['SITE_URL'];?>do-search-product" method="post" class="side-form">

                                	<input type="text" placeholder="Search" name="keyword" maxlength="200" />

                                    <input type="submit" class="submit-btn" value="SEARCH" name="submit" />

                                </form>

                                <?php getbrandsidebar($idkatbrand,$brandlistuid);?>                                
                                

                            </div><!-- .psc -->

                        </div><!-- .ps-child -->

                        

                        <div class="ps-child">

                        	<h3 class="f-pb psc-toggle"><span>HARGA</span></h3>

                            <div class="psc">

                            	<form action="" method="post" class="filter-price-form">

                                    <div class="flat-slider" id="flat-slider"></div>

                                    <label class="f-mr">From:</label>

                                    <input type="text" class="sliderValue priceRange1" name="priceRange1" data-index="0" value="<?php echo number_format($default_price1);?>" onKeyUp="rupiah(this)" />

                                    <label class="f-mr">To:</label>

                                    <input type="text" class="sliderValue priceRange2" name="priceRange2" data-index="1" value="<?php echo number_format($default_price2);?>" onKeyUp="rupiah(this)" />

                                    <input type="hidden" class="statusfilterprice" value="0" />

                                    <?php if($priceRange1>=0 and $priceRange2>0):?>

                                    	<div style="clear:both; height:10px;"></div>

                                    	<a href="" class="btn btn-red no-margin f-psb btn-side-filter rnt-btn resetfilterprice">Reset Filter by Price</a>

                                    <?php endif;?>

                                </form>

                            </div><!-- .psc -->

                        </div><!-- .ps-child -->

                        

                        <div class="ps-child">

                        	<h3 class="f-pb psc-toggle"><span>RATING</span></h3>

                            <div class="psc">

                                <ul class="checkbox-list">

                                    <li>

                                        <input type="checkbox" class="cbox ratting_type5" value="1" id="pl-2-1" <?php if($bintang5==1): echo'checked="checked"'; endif;?> />

                                        <label for="pl-2-1"><div class="prod-raty" data-score="5"></div></label>

                                    </li>

                                    <li>

                                        <input type="checkbox" class="cbox ratting_type4" value="1" id="pl-2-2" <?php if($bintang4==1): echo'checked="checked"'; endif;?> />

                                        <label for="pl-2-2"><div class="prod-raty" data-score="4"></div></label>

                                    </li>

                                    <li>

                                        <input type="checkbox" class="cbox ratting_type3" value="1" id="pl-2-3" <?php if($bintang3==1): echo'checked="checked"'; endif;?> />

                                        <label for="pl-2-3"><div class="prod-raty" data-score="3"></div></label>

                                    </li>

                                    <li>

                                        <input type="checkbox" class="cbox ratting_type2" value="1" id="pl-2-4" <?php if($bintang2==1): echo'checked="checked"'; endif;?> />

                                        <label for="pl-2-4"><div class="prod-raty" data-score="2"></div></label>

                                    </li>

                                    <li>

                                        <input type="checkbox" class="cbox ratting_type1" value="1" id="pl-2-5" <?php if($bintang1==1): echo'checked="checked"'; endif;?> />

                                        <label for="pl-2-5"><div class="prod-raty" data-score="1"></div></label>

                                    </li>

                                </ul><!-- .checkbox-list -->

                            </div><!-- .psc -->

                        </div><!-- .ps-child -->

                        

                        <?php masteratrubutelist($idkat,$idsubkat,$idsublevel,$Listmaster_attribute);?>

                        

                        <br>

                        <a href="" class="btn btn-red no-margin f-psb btn-side-filter check_filter_prod">FILTER</a>
						<input type="hidden" name="Urel_pagelist" class="Urel_pagelist" value="<?php echo $server_uri_prod;?>" />
                        
                    </aside><!-- #product-sidebar -->

                  

                  

                  

                    <div class="product-list-wrap">

                    	<a href="" class="filter-button btn btn-red">Filter Products</a>

                    	<div class="pl-header">

                        	<div class="sort-group">

                            	<label>Sort berdasarkan:</label>

                                <div class="input-wrap">

                                	<div class="select-style">

                                    	<select class="generalsortprodlist">

                                            <option value="" selected="selected">- Sort By -</option>

                                            <option value="name#asc" <?php if($sortitem=="name#asc"): echo'selected="selected"'; endif;?>>By Name, Ascending</option>

                                            <option value="name#desc" <?php if($sortitem=="name#desc"): echo'selected="selected"'; endif;?>>By Name, Descending</option>

                                            <option value="sortnumber#desc" <?php if($sortitem=="sortnumber#desc"): echo'selected="selected"'; endif;?>>Newest</option>

                                            <option value="sortnumber#asc" <?php if($sortitem=="sortnumber#asc"): echo'selected="selected"'; endif;?>>Oldest</option>

                                            <option value="price#asc" <?php if($sortitem=="price#asc"): echo'selected="selected"'; endif;?>>Price, Low to High</option>

                                            <option value="price#desc" <?php if($sortitem=="price#desc"): echo'selected="selected"'; endif;?>>Price, High to Low</option>

                                        </select>

                                        <input type="hidden" name="name_sort" class="name_sortuid" value="sortfilterProd" />  

                                    </div><!-- .select-style -->

                                </div><!-- .input-wrap -->

                            </div><!-- .sort-group -->

                            <div class="sort-group">

                            	<label>Produk ditampilkan:</label>

                                <div class="input-wrap">

                                	<div class="select-style">

                                    	<select class="generalsortprodlist_paging">

                                        	<option value="30" <?php if($sortfilterProdpage==30): echo'selected="selected"'; endif;?>>30</option>

                                            <option value="40" <?php if($sortfilterProdpage==40): echo'selected="selected"'; endif;?>>40</option>

                                            <option value="80" <?php if($sortfilterProdpage==80): echo'selected="selected"'; endif;?>>80</option>

                                            <option value="100" <?php if($sortfilterProdpage==100): echo'selected="selected"'; endif;?>>100</option>

                                            <option value="120" <?php if($sortfilterProdpage==120): echo'selected="selected"'; endif;?>>120</option>

                                            <option value="160" <?php if($sortfilterProdpage==160): echo'selected="selected"'; endif;?>>160</option>

                                        </select>

                                        <input type="hidden" name="name_sort_page" class="name_sort_page" value="sortfilterProdpage" /> 

                                    </div><!-- .select-style -->

                                </div><!-- .input-wrap -->

                            </div><!-- .sort-group -->

                        </div><!-- .pl-header -->

                        <div class="pl-content" style="min-height:600px;">

                        

							<?php while($row = $query->fetch_assoc()):

									 getitemproductlist($row['prodid']);

                                 endwhile;

                                 if($jmldata < 1): echo'<span class="notfound">Record not found.</span>'; endif;

                             ?>	

                             

                        </div><!-- .pl-content -->

                           

                          <?php 

                            if($jmldata>0): 

                                echo'<div class="product-list-bottom">';

                                 echo'<div class="nuke-pagination">';

                                                 

                                        if($halaman>1):

                                            $previous=$halaman-1;

											echo'<a href="'.$file.'/1" class="np-first np-bigger">&laquo;</a>';

                                            echo'<a href="'.$file.'/'.$previous.'" class="np-prev np-bigger">&lsaquo;</a>';

                                        else: 

											echo'<a href="#" class="np-first np-bigger">&laquo;</a>';

                                            echo'<a href="#" class="np-prev np-bigger">&lsaquo;</a>';

                                        endif;		

                                                                                                                                

                                        for($y=1;$y<=$jmlhalaman;$y++){

                                            if($y!=$halaman): 

                                                 echo'<a href="'.$file.'/'.$y.'">'.$y.'</a>';

                                            else: 

                                                 echo'<a href="#" class="np-active">'.$y.'</a>';	

                                            endif;	

                                        }

                                                                                                                                              

                                        if($halaman < $jmlhalaman):

                                             $next=$halaman+1;

                                             echo'<a href="'.$file.'/'.$next.'" class="np-last np-bigger">&rsaquo;</a>';

											 echo'<a href="'.$file.'/'.$jmlhalaman.'"  class="np-last np-bigger" />&raquo;</a>';

                                        else: 

                                             echo'<a href="#" class="np-last np-bigger">&rsaquo;</a>';

											  echo'<a href="#"  class="np-last np-bigger" />&raquo;</a>';

                                        endif;

                                                                

                                 echo'</div><!-- .nuke-pagination -->';	

                            echo'</div><!-- .product-list-bottom -->'; 		

                         endif;

                      ?> 

         

                    </div><!-- .product-list-wrap -->

                </div><!-- #product-list -->

                

            </div><!-- .template-wrap -->

            <div style="clear:both; height:10px;"></div>

            

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- .section -->



<?php include("foot.php"); ?>

<?php include("footer.php"); ?>



<script>

$(document).ready(function() {

	// SIDEBAR PRICE SLIDER

	$('#flat-slider').slider({

	  orientation: 'horizontal',

	  range:       true,

	  min: 0,

	  max:10000000,

	  step:10000,

	  values: [<?php echo $default_price1;?>,<?php echo $default_price2;?>],

	  slide: function(event, ui) {

            for (var i = 0; i < ui.values.length; ++i) {

                $("input.sliderValue[data-index=" + i + "]").val(rupiahtext(ui.values[i]));

				$(".statusfilterprice").val(1);

            }

        }

	});

	$("input.sliderValue").change(function() {

        var $this = $(this);

        $("#flat-slider").slider("values", $this.data("index"), $this.val());

    });



	

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

	

});

</script>



</body>

</html>