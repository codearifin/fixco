<?php include("header.php");

	if(isset($_SESSION['user_token'])==''):
		$_SESSION['error_msg']='login-first';
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;
	//member data
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
	$query = $db->query("SELECT * FROM `member` WHERE `tokenmember`='$Usertokenid'");
	$res = $query->fetch_assoc();
	$idmember = $res['id'];	
	$totalpointmember = gettotalpintmemberTotal($idmember);


	$file = "".$GLOBALS['SITE_URL']."redeem-point";	
	$filter_cari = '';
	$session_sizename = 'alprod_sizeidredeem';

	$batas = 20;
	if(isset($_GET['halaman'])):
		$halaman = $_GET['halaman'];
	else:
		$halaman = 1;	
	endif;
	
	if(empty($halaman)):
		$posisi=0;
		$halaman=1;	
	else: 
		$posisi=($halaman-1) * $batas; 
	endif; 
	
	if(isset($_SESSION['sortfilterProdredeem'])): $sortitem = $_SESSION['sortfilterProdredeem']; else: $sortitem =''; endif;
	if($sortitem<>''):
		$itemexlode = explode("#",$sortitem);
		$sortprodutby = ' `'.$itemexlode[0].'` '.$itemexlode[1].' ';
	else:
		$sortprodutby = ' `sortnumber` DESC ';
	endif;

	$sql2 = $db->query("SELECT `id` FROM `redeem_product` WHERE 1=1 and `publish`=1 ORDER BY ".$sortprodutby." ") or die($db->error);
	$jmldata = $sql2->num_rows;
	$jmlhalaman = ceil($jmldata/$batas); 
			
	//main query
	$query = $db->query("SELECT * FROM `redeem_product` WHERE 1=1 and `publish`=1 ORDER BY ".$sortprodutby." LIMIT $posisi,$batas") or die($db->error);		
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li class="f-pb">Point Reward</li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section">
	<div class="container">
    	<div class="row">
        	<aside id="template-sidebar" class="ts-ads-wrap">
                <div class="ts-child">
                	<h3 class="f-pb">AKUN SAYA</h3>
                    <ul class="ts-menu">
                         <?php include("side_member.php");?>
                    </ul><!-- .ts-menu -->
                </div><!-- .ts-child -->
            </aside><!-- #product-sidebar -->
            <div class="template-wrap">
                <h1 class="f-pb">Point Reward</h1>
                <div class="red-tabbing">
                	<a href="<?php echo $GLOBALS['SITE_URL'];?>point-reward">History Point Reward</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>redeem-point" class="active">Redeem Point</a>
                    <a href="<?php echo $GLOBALS['SITE_URL'];?>redeem-history">Redeem History</a>
                </div><!-- .red-tabbing -->
               
                <div class="table-sort">
                	<label>Sortir berdasarkan:</label>
                    <div class="input-wrap">
                    	<div class="select-style">
                            <select class="generalsortprodlist">
                               <option value="" selected="selected">- Sort By -</option>
                               <option value="name#asc" <?php if($sortitem=="name#asc"): echo'selected="selected"'; endif;?>>By Name, Ascending</option>
                               <option value="name#desc" <?php if($sortitem=="name#desc"): echo'selected="selected"'; endif;?>>By Name, Descending</option>
                               <option value="sortnumber#desc" <?php if($sortitem=="sortnumber#desc"): echo'selected="selected"'; endif;?>>Newest</option>
                               <option value="sortnumber#asc" <?php if($sortitem=="sortnumber#asc"): echo'selected="selected"'; endif;?>>Oldest</option>
                               <option value="point_redeem#asc" <?php if($sortitem=="point_redeem#asc"): echo'selected="selected"'; endif;?>>Point, Low to High</option>
                               <option value="point_redeem#desc" <?php if($sortitem=="point_redeem#desc"): echo'selected="selected"'; endif;?>>Point, High to Low</option>
                            </select>
                            <input type="hidden" name="name_sort" class="name_sortuid" value="sortfilterProdredeem" />  
                        </div><!-- .select-style -->
                    </div><!-- .input-wrap -->
                </div><!-- .table-sort -->
                
                <div class="redeem-list">
                
                			<?php while($row = $query->fetch_assoc()):
									 echo'<div class="rlc">
												<div class="rlc-content">
												  <div class="rlc1">
													<a href="'.$GLOBALS['SITE_URL'].'product-redeem-detail/'.replace($row['name']).'/'.$row['id'].'" class="nuke-fancied2"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['name'].'" /></a>
												  </div><!-- .rlc1 -->
													<div class="rlc2">
														<h3 class="f-pb"><a href="'.$GLOBALS['SITE_URL'].'product-redeem-detail/'.replace($row['name']).'/'.$row['id'].'" class="nuke-fancied2">'.$row['sku_product'].' - '.$row['name'].'</a></h3>
														<span class="f-red f-pb">'.number_format($row['point_redeem']).' point</span>';
														
														if($row['stock']>0):
															if($row['point_redeem'] > $totalpointmember):
																echo'<a class="btn btn-red no-margin f-psb">CHECK POINT</a>';
															else:
																echo'<a href="'.$GLOBALS['SITE_URL'].'konfirmasi-redeem/'.replace($row['name']).'/'.$row['id'].'" class="btn btn-red no-margin f-psb">REDEEM</a>';
															endif;
															
														else:
															echo'<a class="btn btn-red no-margin f-psb soldoutbtn">SOLD</a>';
														endif;	
														
													echo'</div><!-- .rlc2 -->	
												</div><!-- .rlc-content -->
											</div><!-- .rlc -->';
                                 endwhile;
                                 if($jmldata < 1): echo'<span class="notfound">Record not found.</span>'; endif;
                             ?>	

                </div><!-- .redeem-list -->
                <br>
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
                
                
                
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>

<script>
	(function($){
		function rlcHeight() {
			var heights = new Array();
			
			// Loop to get all element heights
			$('.rlc-content').each(function() {	
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
			$('.rlc-content').each(function() {
				if($(window).width() > 539) {
					$(this).css('height', max);
				} else {
					$(this).css('height', 'auto');
				}
			});	
		}
	
		$(document).ready(function() {
		  $(".menu10").addClass("active");	
	    });	
	
		$(window).load(function() {
			// Fix heights on page load
			rlcHeight();
	
			// Fix heights on window resize
			$(window).resize(function() {
				// Needs to be a timeout function so it doesn't fire every ms of resize
				setTimeout(function() {
					rlcHeight();
				}, 120);
			});
		});
	})(jQuery);
</script>

</body>
</html>