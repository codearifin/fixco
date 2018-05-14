<?php
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");
	
	if(isset($_GET['iddata'])): $iddata = $_GET['iddata']; else: $iddata = 0; endif;
	$query = $db->query("SELECT 
	
	`deposit_member_corporatelist_konfirmasi`.`id` as cid,
	`deposit_member_corporatelist_konfirmasi`.`bank` as cbank,
	`deposit_member_corporatelist_konfirmasi`.`account_holder` as caccount_holder,
	`deposit_member_corporatelist_konfirmasi`.`amount` as camount,
	`deposit_member_corporatelist_konfirmasi`.`bukti_trf` as cbukti_trf,
	`deposit_member_corporatelist_konfirmasi`.`status` as cstatus,
	DATE_FORMAT(`deposit_member_corporatelist_konfirmasi`.`date_posting`, '%d/%m/%Y %H:%i:%s') as mdate,
	DATE_FORMAT(`deposit_member_corporatelist_konfirmasi`.`date`, '%d/%m/%Y') as mdate2,
	
	`member`.`name` as mname,
	`member`.`lastname` as mlastname
	
	FROM `deposit_member_corporatelist_konfirmasi` LEFT JOIN `member` ON `member`.`id` = `deposit_member_corporatelist_konfirmasi`.`idmember` 
	 
	WHERE `deposit_member_corporatelist_konfirmasi`.`id` = '$iddata' ");
	$row = $query->fetch_assoc();
?>


<div class="nuke-wysiwyg" style="width:550px; min-height:130px; padding:20px;">
     <form action="eccomerce/do_update_topdepositmember.php" method="post"> 
     	<input type="hidden" name="iddata" value="<?php echo $row['cid'];?>" />
        
	  <?php echo '<strong>'.$row['mname'].' '.$row['mlastname'].'</strong>'."<span style='opacity:0.5;float:right;'>Top Up Deposit</span><hr/>"; ?>
	  <table style="width:100%;margin:15px auto;">
	 
    
      <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Posting Date</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo''.$row['mdate'].'';?></td>
	  </tr>

	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Member Name</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo'<strong>'.$row['mname'].' '.$row['mlastname'].'</strong>';?></td>
	  </tr>

       <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Bank Name</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo''.$row['cbank'].'';?></td>
	  </tr>

	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Account Holder</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo''.$row['caccount_holder'].'';?></td>
	  </tr> 
 
       <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Trf Date</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo''.$row['mdate2'].'';?></td>
	  </tr>
                 
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Total Trf</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		Rp. <?php echo number_format($row['camount']);?></td>
	  </tr>

	  <tr>
	    <td style="border:1px solid #adadad;padding:10px 10px;text-align:left;background:#fff;">Confirm Deposit Value</td>
		<td style="border:1px solid #adadad;padding:10px 10px;text-align:left;border-left:none;background:#fff">
            <input type="text" name="totaldepo" value="<?php echo number_format($row['camount']);?>" onkeyup="rupiah(this)" style="padding:5px 5px 5px 5px; width:120px;" />
        </td>
	  </tr>	

	  <tr>
	    <td style="border:1px solid #adadad;padding:10px 10px;text-align:left;background:#fff;">Status</td>
		<td style="border:1px solid #adadad;padding:10px 10px;text-align:left;border-left:none;background:#fff">
            <select name="status_list" style="padding:4px 5px 4px 5px; width:150px;">
            	<option value="1">Confirmed</option>
                <option value="2">Cancelled</option>
            </select>
        </td>
	  </tr>	    

	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Note to Member</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
           <textarea name="notemember" style="padding:10px 5px 10px 5px; width:90%; height:30px;"></textarea>
        </td>
	  </tr>	 
       
  	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">&nbsp;</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
          <input type="submit" name="submit" value="SUBMIT" style="padding:5px; cursor:pointer;" onclick="return confirm('Anda yakin untuk confirm this top up member ini?');" />
        </td>
	  </tr>	    
                 	
	  </table>
       </form> 
	</div>
     