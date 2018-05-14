<?php
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");
	
	if(isset($_GET['iddata'])): $iddata = $_GET['iddata']; else: $iddata = 0; endif;
	$query = $db->query("SELECT 
	
	`claim_komis_member`.`id` as cid,
	`claim_komis_member`.`bank_transfer` as cbank_transfer,
	`claim_komis_member`.`total` as ctotal,
	`claim_komis_member`.`status_payment` as cstatus_payment,
	DATE_FORMAT(`claim_komis_member`.`date`, '%d/%m/%Y %H:%i:%s') as mdate,
	
	`member`.`name` as mname,
	`member`.`lastname` as mlastname
	
	FROM `claim_komis_member` LEFT JOIN `member` ON `member`.`id` = `claim_komis_member`.`idmember_claim` 
	 
	WHERE `claim_komis_member`.`id` = '$iddata' ");
	$row = $query->fetch_assoc();
?>

<style>
	select.geninput {padding:6px 5px 6px 5px; width:220px; font-size:14px; font-family:calibri; font-weight:600;}
	textarea.geninput2 {padding:10px 8px 10px 8px; width:90%; height:35px; font-size:14px; font-family:calibri;}
	input.submit {border:none; text-align:center; padding:10px 15px 10px 15px; cursor:pointer;}
	input.submit:hover { background:#003399; color:#fff;}
	input.input-text { border:1px solid #ccc; padding:10px 7px 10px 7px; width:90%; font-family:calibri; font-size:14px; }
</style>

<div class="nuke-wysiwyg" style="width:550px; min-height:130px; padding:20px;">
     <form action="eccomerce/do_update_claim_komis_member.php" method="post"> 
     	<input type="hidden" name="iddata" value="<?php echo $row['cid'];?>" />
        
	  <?php echo '<strong>'.$row['mname'].' '.$row['mlastname'].'</strong>'."<span style='opacity:0.5;float:right;'>Commission Withdrawal</span><hr/>"; ?>
	  <table style="width:100%;margin:15px auto;">
	 
    
      <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Date</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo'<strong>'.$row['mdate'].'</strong>';?></td>
	  </tr>

      <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Claim ID</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo'<strong style="font-size:14px;">#'.sprintf('%06d',$row['cid']).'</strong>';?></td>
	  </tr>
            
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Member Name</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo'<strong>'.$row['mname'].' '.$row['mlastname'].'</strong>';?></td>
	  </tr>
      
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Total Claim</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		Rp. <?php echo number_format($row['ctotal']);?></td>
	  </tr>

	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Bank Account</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo $row['cbank_transfer'];?></td>
	  </tr>




	  <tr>
	    <td style="border:1px solid #adadad;padding:10px 10px;text-align:left;background:#fff;">Status</td>
		<td style="border:1px solid #adadad;padding:10px 10px;text-align:left;border-left:none;background:#fff">
            <select name="status_list" class="geninput">
            	<option value="Paid">Paid</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </td>
	  </tr>	    

	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff; vertical-align:top;">Note to Member</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
           <textarea name="notemember" class="geninput2"></textarea>
        </td>
	  </tr>	 
       
  	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">&nbsp;</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
          <input type="submit" name="submit" value="SUBMIT" class="submit" onclick="return confirm('Anda yakin untuk update claim list member ini?');" />
        </td>
	  </tr>	    
                 	
	  </table>
       </form> 
	</div>
     