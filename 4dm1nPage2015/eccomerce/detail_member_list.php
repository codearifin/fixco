<?php	
@session_start();
require("../../config/connection.php");
require("../../config/myconfig.php");

		
	function getnamakota($id){
		global $db;
		$query = $db->query("SELECT `nama_kota` FROM `ongkir` WHERE `id`='$id'");
		$res = $query->fetch_assoc();
		return ucwords($res['nama_kota']);
	}	
	
	function getmemberaddress($idmember){
		global $db;
	
		$output = ''; 
		$queery = $db->query("SELECT * FROM `member` WHERE `id`='$idmember' ");
		$data = $queery->fetch_assoc();
				
		$que = $db->query("SELECT * FROM `billing_address` WHERE `idmember`='$idmember' ");
		$row = $que->fetch_assoc();
		
        $output.='<p>'.$row['address'].'<br />';
		$output.=''.getnamakota($row['idcity']).' - '.ucwords($row['kabupaten']).' <br /> '.ucwords($row['provinsi']).' - '.$row['kodepos'].'';
        $output.='</p>';
		echo $output;	
	}
	
if(isset($_GET['iddata'])): $orderid = $_GET['iddata']; else: $orderid = 0; endif;
$query = $db->query("SELECT * FROM `member` WHERE `id`='$orderid' ") or die($db->error);
$row = $query->fetch_assoc();
?>
	<div class="nuke-wysiwyg" style="width:500px; height:auto; padding:20px;">
	  <?php echo"<strong>".$row['name']." ".$row['lastname']."</strong><span style='opacity:0.5;float:right;'>Detail Member</span><hr/>"; ?>
	  <table style="width:100%;margin:15px auto;">
	
     
          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:transparent">Register Date</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#f6f6f6"><?php echo $row['date'];?></td>
          </tr>
          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">Member Name</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent"><?php echo $row['name'];?> <?php echo $row['lastname'];?></td>
          </tr>
          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">Email</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent"><?php echo $row['email'];?></td>
          </tr>      
          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">Contact</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent"><?php echo $row['phone'];?> / <?php echo $row['mobile_phone'];?></td>
          </tr> 

          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">Address</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent">
            	<?php
					$quepp = $db->query("SELECT * FROM `billing_address` WHERE `idmember`='$orderid'");
					$jumpage = $quepp->num_rows;
					if($jumpage>0):
						getmemberaddress($orderid);
					else:
						echo'No complete data.';
					endif;	
				?>
            </td>
          </tr> 
          
          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">Status</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent">
            <?php
					if($row['status']=="Active"):
						echo'<span style="font-size:11px; font-weight:600; padding:3px 4px 3px 4px; text-align:center; display:inline-block; width:52px; background:#00CC33; color:#fff;">Active</span>';
					else:
						echo'<span style="font-size:11px; font-weight:600; padding:3px 4px 3px 4px; text-align:center; display:inline-block; width:52px; background:#FF6633; color:#fff;">In Active</span>';
					endif;			
			?>
            </td>
          </tr> 

          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">Type Member</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent">
            <?php
				if($row['member_category']=="CORPORATE MEMBER"):
					echo'<span style="font-size:11px; font-weight:600; padding:3px 4px 3px 4px; text-align:center; display:inline-block; width:120px; background:#FF6633; color:#fff;">CORPORATE MEMBER</span>';
				else:
					echo'<span style="font-size:11px; font-weight:600; padding:3px 4px 3px 4px; text-align:center; display:inline-block; width:120px; background:#00CC33; color:#fff;">REGULAR MEMBER</span>';
				endif;		
			?>
            </td>
          </tr> 
                  
                  
          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#ccc" colspan="2">Company Info</td>
          </tr>  

            	<?php
					$queppcom = $db->query("SELECT * FROM `member_membership_data` WHERE `idmember`='$orderid'");
					$jumpage2 = $queppcom->num_rows;
					if($jumpage2>0):
						$ros = $queppcom->fetch_assoc();
					?>
                    
                      <tr>
                        <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">Company</td>
                        <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent"><?php echo $ros['company_name'];?></td>
                      </tr>  

                      <tr>
                        <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">NPWP</td>
                        <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent"><?php echo $ros['npwp'];?></td>
                      </tr> 

                      <tr>
                        <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">File Upload</td>
                        <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent">
                        	<?php if($ros['file_member']!=""):?><a href="<?php echo $GLOBALS['UPLOAD_FOLDER'];?><?php echo $ros['file_member'];?>" target="_blank">View File</a><?php else: echo'No File'; endif;?>
                        </td>
                      </tr> 
                                                                    
                    <?php		
					else:
						  echo'<tr>
         					    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#f6f6f6" colspan="2">No Records</td>
       						  </tr>';
					endif;	
				?>                           
                          
	  </table>
	</div>