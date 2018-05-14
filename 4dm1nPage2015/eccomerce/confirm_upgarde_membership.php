<?php
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");
	
	if(isset($_GET['iddata'])): $iddata = $_GET['iddata']; else: $iddata = 0; endif;
	$query = $db->query("SELECT 
	
	`member_membership_data_payment`.`id` as mid,
	 DATE_FORMAT(`member_membership_data_payment`.`date`, '%d/%m/%Y') as mdate,
	`member_membership_data_payment`.`idmember` as midmember,
	`member_membership_data_payment`.`iddata_membership` as middata_membership,
	`member_membership_data_payment`.`idbank` as midbank,
	`member_membership_data_payment`.`idtype_membership` as midtype_membership,
	`member_membership_data_payment`.`metode_payment` as mmetode_payment,
	`member_membership_data_payment`.`amount` as mamount,
	`member_membership_data_payment`.`status` as mstatus,
	
	`member_membership_data`.`id` as pid,
	`member_membership_data`.`company_name` as pcompany_name,
	`member_membership_data`.`npwp` as pnpwp,
	`member_membership_data`.`file_member` as pfile_member
	
			
	
	FROM `member_membership_data_payment` LEFT JOIN `member_membership_data` ON `member_membership_data_payment`.`iddata_membership` = `member_membership_data`.`id` 
	
	WHERE `member_membership_data_payment`.`id` = '$iddata' ");	
	
	$row = $query->fetch_assoc();
	
	
	//select price top up
	$quioooopp = $db->query("SELECT `saldo_price_for_member` FROM `membership_deposit_adm` WHERE `id` = '".$row['midtype_membership']."' ");
	$data = $quioooopp->fetch_assoc();
	$jumlahdeposit = $data['saldo_price_for_member'];
?>

<style>
	select.geninput {padding:6px 5px 6px 5px; width:220px; font-size:14px; font-family:calibri; font-weight:600;}
	textarea.geninput2 {padding:10px 8px 10px 8px; width:90%; height:35px; font-size:14px; font-family:calibri;}
	input.submit {border:none; text-align:center; padding:10px 15px 10px 15px; cursor:pointer;}
	input.submit:hover { background:#003399; color:#fff;}
</style>

<div class="nuke-wysiwyg" style="width:550px; min-height:330px; padding:20px;">
     <form action="eccomerce/do_update_membership.php" method="post"> 
     	<input type="hidden" name="iddata" value="<?php echo $row['mid'];?>" />
        <input type="hidden" name="iddataadm" value="<?php echo $row['midtype_membership'];?>" />
        
	  <?php echo '<strong>'.getnamegenal(" `id`='".$row['midmember']."' ", "member","name").' '.getnamegenal(" `id`='".$row['midmember']."' ", "member","lastname").'</strong>'."<span style='opacity:0.5;float:right;'>Upgrade Membership</span><hr/>"; ?>
	  <table style="width:100%;margin:15px auto;">
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Member Name</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo'<strong>'.getnamegenal(" `id`='".$row['midmember']."' ", "member","name").' '.getnamegenal(" `id`='".$row['midmember']."' ", "member","lastname").'</strong>';?></td>
	  </tr>
      
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Company Name</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		 <?php echo'<strong>'.$row['pcompany_name'].'</strong>'; if($row['pnpwp']<>''): echo'<br />NPWP: '.$row['pnpwp'].''; endif;?>
         </td>
	  </tr>
 
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Top Up Date</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		 <?php echo $row['mdate'];?></td>
	  </tr>	
  
  	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Jumlah Deposit</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		 <?php echo'Rp. '.number_format($row['mamount']).'';?></td>
	  </tr>	

  	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Jumlah Deposit Yang Akan Ditambahkan</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		 <?php echo'Rp. '.number_format($jumlahdeposit).'';?></td>
	  </tr>	
                
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Type Deposit</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		 <?php echo''.getnamegenal(" `id`='".$row['midtype_membership']."' ", "membership_deposit_adm","title").'';?></td>
	  </tr>	


	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Payment</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		 <?php 
		 		echo'<strong>'.$row['mmetode_payment'].'</strong>';
				echo'<br />'.getnamegenal(" `id`='".$row['midbank']."' ", "bank_account","bank_name").' - '.getnamegenal(" `id`='".$row['midbank']."' ", "bank_account","account_number").' 
				an: '.getnamegenal(" `id`='".$row['midbank']."' ", "bank_account","account_holder");
		 ?></td>
	  </tr>	 
      
     
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Status</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
            <select name="status_list" class="geninput">
            	<option value="Confirmed" selected="selected">Confirmed</option>
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
          <input type="submit" name="submit" value="SUBMIT" class="submit" onclick="return confirm('Anda yakin untuk confirm status member ini?');" />
        </td>
	  </tr>	    
                 	
	  </table>
       </form> 
	</div>
     