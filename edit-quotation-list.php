<?php
	@session_start();
	require("config/connection.php");
	require("config/myconfig.php");
	require("include/function.php");
	
	if(isset($_SESSION['user_token'])==''):
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;

	//member data
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
	$qummep = $db->query("SELECT `id`,`idmember_list`,`status` FROM `corporate_user` WHERE `id`='$Usertokenid'");
	$ros = $qummep->fetch_assoc();	
	$idmember = $ros['idmember_list']; $idusr = $ros['id'];
	
	if(isset($_GET['token'])): $token = replaceUrel($_GET['token']); else: $token = ''; endif;
	if($ros['status']==1):
		$que = $db->query("SELECT `id`,`tokenpay` FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' and `tokenpay`='$token'");
	else:
		$que = $db->query("SELECT `id`,`tokenpay` FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' and `id_user` = '$idusr' and `tokenpay`='$token'");
	endif;
	$row = $que->fetch_assoc();	
?>

<div class="popup-wrap popup-medium">
	<div class="popup-header">
    	<h2 class="f-pb">EDIT QUOTATION LIST - <?php echo '#DQ-'.sprintf('%06d',$row['id']).'';?></h2>
    </div><!-- .popup-header -->
    <div class="popup-content">
        <div style="padding-bottom:20px;">
        	<a href="<?php echo $GLOBALS['SITE_URL'];?>add-more-produk/<?php echo $row['tokenpay'];?>" class="btn btn-yellow f-psb no-margin nuke-fancied2">Tambah Produk +</a>
        </div>
       
        <table cellspacing="0" cellpadding="0" class="table-rnt">
           	<thead>
            <tr>
               	<td width="1%">No</td>
                <td width="50%">Item Name</td>
                <td width="10%">Qty</td>
                <td width="8%">Harga</td>
                <td width="12%">Action</td>
             </tr>
             </thead>
             <tbody>
				<?php
                    $nopage = 1;
                    $query = $db->query("SELECT * FROM `draft_quotation_detail` WHERE `tokenpay`='".$row['tokenpay']."' ORDER BY `id` ASC");
					$jumpage = $query->num_rows;
                    while($res = $query->fetch_assoc()):
                         $itemwarnalist = explode("#",$res['nama_detail']);
                         $warnaprod = $itemwarnalist[0];
                        
                         echo'<tr>';
                            echo'<td>'.$nopage.'</td>';
                            echo'<td><strong>'.$res['name'].'</strong><br />'.$res['sku'].' - '.$warnaprod.'</td>';
                            echo'<td>'.number_format($res['qty']).'</td>';
                            echo'<td>'.number_format($res['price']).'</td>';
                            echo'<td>
							<a href="'.$GLOBALS['SITE_URL'].'edit-item-list/'.$res['id'].'/'.$res['tokenpay'].'" class="nuke-fancied2 editlist">Edit</a>
							&nbsp;|&nbsp;';
							
							if($jumpage>1):
								echo'<a href="'.$GLOBALS['SITE_URL'].'delete-item-list/'.$res['id'].'/'.$res['tokenpay'].'" onclick="return confirm(\'Anda yakin untuk mengahpus item ini?\');" class="red-link">Hapus</a>';
							else:
								echo'&nbsp;<img src="'.$GLOBALS['SITE_URL'].'images/lock.png" />';
							endif;
							
							echo'</td>';
                         echo'</tr>';
                         $nopage++;	
                    endwhile;
                ?>
        </tbody>
         </table> 

    </div><!-- .popup-content -->
</div><!-- .popup-wrap -->