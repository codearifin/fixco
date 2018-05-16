<?php

	require("../../config/connection.php");

	require("../../config/myconfig.php");

	require("function.php");

	if(isset($_GET['idheader'])): $idheader = $_GET['idheader']; else: $idheader = 0; endif;

	$query = $db->query("SELECT * FROM `quotation_header` WHERE `id`='$idheader' ") or die($db->error);

	$row = $query->fetch_assoc();


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

	<h2 class="f-gm f-rcr" style="font-size:16px; padding-bottom:10px;">Request Quotation Detail</h2>

	<p class="order-date">Creator Name : <?php echo $row['creator_name'];?></p>
	<p class="order-date">Creator Email : <?php echo $row['creator_email'];?></p>
    <p class="order-date">Created Date : <?php echo $row['created_date'];?></p> 


   <div style="clear:both; height:10px;"></div>             

    <div class="od-bottom">

    	<div class="odb-top">

            <h2 class="f-mb">Quotation Request List</h2>

			

            <?php
				$satuan = Array();
				$query_satuan = $db->query("SELECT * FROM `satuan_quotation` WHERE `publish` = 1") or die($db->error);

				while($satuan_temp = $query_satuan->fetch_assoc()):
					$satuan[$satuan_temp['id']] = $satuan_temp['satuan'];
				endwhile;

				$query_data = $db->query("SELECT * FROM `quotation_detail` WHERE `id_quotation_header` = $idheader") or die($db->error);

				while($data = $query_data->fetch_assoc()):
						echo'<div class="odb-child">

							<div class="img-wrap">'.getimagesdetail($data['image'],$UPLOAD_FOLDER).'</div>

							<div class="scb1-txt clearfix">

								<div class="scb1-1">

									<h3 class="f-rcr" style=" color:#111; font-size:14px;">'.$data['nama_produk'].'</h3>';

										

										echo '<span class="jcartlistitem">';

											echo $data['jumlah'].' - '.$satuan[$data['id_satuan']];

										echo '</span>';

																					

									echo'<p>Keterangan : '.$data['keterangan'].'</p>

								</div><!-- .scb1-1 -->

								

							</div><!-- .scb1-txt -->

						</div><!-- .cpt-child -->';				



				endwhile;			

			?>
        </div><!-- .odb-top -->

    </div><!-- .od-bottom -->

    

</div><!-- #order-detail -->