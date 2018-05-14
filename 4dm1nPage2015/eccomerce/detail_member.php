<?php	
require("../../config/connection.php");
function getnamekotadetail($id){
	global $db;
	$query = $db->query("SELECT `nama_kota` FROM `ongkir` WHERE `id`='$id'");
	$row = $query->fetch_assoc();
	return ucwords($row['nama_kota']);
}

$id = $_GET['idmember'];
$query = $db->query("SELECT * FROM `member` WHERE `id`='$id'");
$row = $query->fetch_assoc();

$quepp = $db->query("SELECT * FROM `billing_address` WHERE `idmember`='$id'");
$data = $quepp->fetch_assoc();
?>
	<div class="nuke-wysiwyg" style="width:550px; min-height:300px; padding:20px;">
	  <?php echo ucwords($row['name'])." ".ucwords($row['lastname'])."<span style='opacity:0.5;float:right;'>Member Details</span><hr/>"; ?>
	  <table style="width:100%;margin:15px auto;">
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Register Date</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo $row['date'];?></td>
	  </tr>
      
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Member Name</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff"><strong>
		 <?php echo ucwords($row['name']);?> <?php echo ucwords($row['lastname']);?></strong></td>
	  </tr>
      
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Status</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		 <?php echo $row['status'];?></td>
	  </tr>	

          
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff">Address</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
        	<?php if($row['status_complete']==0): echo'Not complete'; else:?>
				<?php echo $data['address'];?><br />
                <?php echo getnamekotadetail($data['idcity']);?> <?php echo ucwords($data['kabupaten']);?> <br /><?php echo ucwords($data['provinsi']);?> - <?php echo $data['kodepos'];?>
            <?php endif;?>
        </td>
	  </tr>
      
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#ccc; font-weight:600;" colspan="2">Contact Person</td>
	  </tr>    
 
 	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff">Mobile</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff"><?php echo $row['phone'];?> / <?php echo $row['mobile_phone'];?></td>
	  </tr>           
      
 	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff">Email</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff"><?php echo $row['email'];?></td>
	  </tr>  

	  </table>
	</div>