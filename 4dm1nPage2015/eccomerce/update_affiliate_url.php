<?php
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");
	
	if(isset($_GET['iddata'])): $iddata = $_GET['iddata']; else: $iddata = 0; endif;
	$query = $db->query("SELECT 
	
	`affiliate_member`.`id` as aid,
	`affiliate_member`.`token_affiliate` as atoken_affiliate,
	`affiliate_member`.`status` as astatus,
	
	`member`.`name` as mname,
	`member`.`lastname` as mlastname
	
	FROM `affiliate_member` LEFT JOIN `member` ON `member`.`id` = `affiliate_member`.`idmember_aid` WHERE `affiliate_member`.`id` = '$iddata' ") or die($db->error);
	
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
     <form action="eccomerce/do_update_affiliate_member.php" method="post"> 
     	<input type="hidden" name="iddata" value="<?php echo $row['aid'];?>" />
        
	  <?php echo '<strong>'.$row['mname'].' '.$row['mlastname'].'</strong>'."<span style='opacity:0.5;float:right;'>Affiliate Member</span><hr/>"; ?>
	  <table style="width:100%;margin:15px auto;">
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Member Name</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo'<strong>'.$row['mname'].' '.$row['mlastname'].'</strong>';?></td>
	  </tr>
      

	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Status</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
            <select name="status_list" class="geninput">
            	<option value="1" <?php if($row['astatus']==1): echo'selected="selected"'; endif;?>>Active</option>
                <option value="0" <?php if($row['astatus']==0): echo'selected="selected"'; endif;?>>In Active</option>
            </select>
        </td>
	  </tr>	    
 
  	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">&nbsp;</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
          <input type="submit" name="submit" value="SUBMIT" class="submit" onclick="return confirm('Anda yakin untuk update status member ini?');" />
        </td>
	  </tr>	    
                 	
	  </table>
       </form> 
	</div>
     