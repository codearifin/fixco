<?php
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("function.php");
	
	if(isset($_GET['orderid'])): $orderid = $_GET['orderid']; else: $orderid = 0; endif;
	$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y') as tgl FROM `draft_quotation_header` WHERE `id`='$orderid' ") or die($db->error);
	$row = $query->fetch_assoc();	
	$totalorder =  $row['total_order']+$row['shippingcost'];
	if($row['phone_penerima']<>''): $Phonelabel = '('.$row['phone_penerima'].')'; else: $Phonelabel = ''; endif;
?>

	<style>
    .general-table {width:100%;}
    .general-table thead td {padding:10px 8px; font-weight:500; background:#444444; color:#fff;}
    .general-table tbody td {padding:8px 8px; border-top:1px solid #d1d1d1; vertical-align:top;}
    .submitbtn { cursor:pointer; border:none; background:#FF3366; padding:5px 10px 5px 10px; color:#fff;} 
    .submitbtn:hover { background:#FF9966;}
    .od-top h3 {color:#939598; font-weight:bold; letter-spacing:0.05em; margin-bottom:4px;}
    .od-top p {font-size:12px; line-height:1.5em; border:1px solid #e9e9ea; padding:8px 10px; margin-bottom:12px;}
    
    #order-detail .odb-top h2 {background:#444; letter-spacing:0.05em; text-transform:uppercase; color:#939598; padding:10px 15px; font-size:12px; margin-bottom:0;} 
    .odb-child {border:1px solid #d9dbdc; margin-bottom:-1px; padding:10px 15px; overflow:hidden;}
    .odb-child .img-wrap {float:left; width:35px; height:35px; border:1px solid #9e9e9e; position:relative; margin-right:5px;}
    .odb-child .img-wrap:hover {border:1px solid #aa1f26;}
    .odb-child .img-wrap img {display:block; max-width:90%; max-height:90%; position:absolute; margin:auto; top:0; right:0; bottom:0; left:0;}
    .odb-child .scb1-txt {margin-left:50px;}
    .odb-child .scb1-2 {margin:5px 0;}
    .odb-left, .odb-right { color:#aa1f26; letter-spacing:0.05em; font-size:14px;} 
    .odb-left {float:left;}
    .scb23, .odb-right {float:right;}
    </style>

<div id="order-detail" style="width:600px; padding:20px 20px 20px 20px;">
	<h2 class="f-gm f-rcr" style="font-size:16px; padding-bottom:10px;">Quotation Order ID <strong><?php echo '#DQ-'.sprintf('%06d',$row['id']).'';?></strong></h2>
    
    <p class="order-date">Order Date : <?php echo $row['tgl'];?></p>

    <div class="od-top" style="padding-top:13px;">
        <h3 class="f-rcr" style="font-weight:normal;">Shipping Address:</h3>
        <div>
  			<?php echo'<strong>'.$row['nama_penerima'].'</strong> '.$Phonelabel.'<br />';?>
			<?php
					echo $row['address_penerima'].'<br />';
					echo $row['kota_penerima'].', '. $row['kabupaten_penerima'].'<br />';
					echo $row['provinsi_penerima'].' - '.$row['country_penerima'].' '.$row['kodepos'];				
			?>       
        </div>
    </div><!-- .od-top -->
	
    <?php if($row['note']<>''):
		echo'<div class="od-top" style="padding-top:20px;">
			<h3 class="f-rcr" style="font-weight:normal;">Note</h3>
			<p>'.$row['note'].'</p>
		</div><!-- .od-top -->';
	endif;
	?>     

       
   <div style="clear:both; height:10px;"></div>             
    <div class="od-bottom">
    	<div class="odb-top">
            <h2 class="f-mb">Order Item</h2>
			
            <?php
				$totaloo = 0; $grtotal = 0; $satuanprod = '';
				$que33 = $db->query("SELECT * FROM `draft_quotation_detail` WHERE `tokenpay`='".$row['tokenpay']."' ORDER BY `id` ASC") or die($db->error);
				while($data = $que33->fetch_assoc()):
						if($data['nama_detail']<>''):
							$listdetail = explode("#",$data['nama_detail']);
							$namadetail = $listdetail[0];
						else:
							$namadetail = $data['nama_detail'];
						endif;
											
						echo'<div class="odb-child">
							<div class="img-wrap">'.getimagesdetail($data['idproduct'],$UPLOAD_FOLDER).'</div>
							<div class="scb1-txt clearfix">
								<div class="scb1-1">
									<h3 class="f-rcr" style=" color:#111; font-size:14px;">'.$data['name'].'</h3>';
										
										echo '<span class="jcartlistitem">';
											echo '<strong>'.$data['sku'].'</strong> - '.$namadetail;
										echo '</span>';
																					
									echo'<p>IDR '.number_format($data['price']).' x '.$data['qty'].'</p>
								</div><!-- .scb1-1 -->
								
								<div class="scb23">
									<div class="scb1-3">
										<span style=" font-size:14px;">IDR '.number_format($data['price']*$data['qty']).'</span>
									</div><!-- .scb1-3 -->
								</div><!-- .scb23 -->
								
							</div><!-- .scb1-txt -->
						</div><!-- .cpt-child -->';				

				endwhile;			
			?>                    

            <div class="odb-child clearfix">
            	<div class="odb-left" style="color:#666; font-size:13px;">Sub Total</div>
                <div class="odb-right" style="font-size:14px; color:#333;">IDR <?php echo number_format($row['total_order']);?></div>
            </div><!-- .odb-child -->

             
            <div class="odb-child clearfix">
            	<div class="odb-left" style="color:#666; font-size:13px;">Shipping Cost</div>
                <div class="odb-right" style="font-size:14px; color:#333;">IDR <?php echo number_format($row['shippingcost']);?></div>
            </div><!-- .odb-child -->
             
            <div class="odb-child clearfix">
               <div class="odb-left" style="color:#666; font-size:13px;">Grand Total</div>
                <div class="odb-right"><strong>IDR <?php echo number_format($totalorder);?></strong></div>
            </div><!-- .odb-child -->

            
        </div><!-- .odb-top -->
    </div><!-- .od-bottom -->
    
</div><!-- #order-detail -->