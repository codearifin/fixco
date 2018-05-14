<?php

	@session_start();

	require("../../config/connection.php");

	require("../../config/myconfig.php");

	require("../function_rnt.php");

	require("webconfig-parameters.php");

	

	$idmember = $_POST['memberid'];
	
	$quiopp = $db->query("SELECT * FROM `member` WHERE `id` = '$idmember' ");	
	$data = $quiopp->fetch_assoc();
	$namemember = $data['name'];
	$lastname = $data['lastname'];
	$emailmember = $data['email'];
	

	$quip304 = $db->query("SELECT `id` FROM `affiliate_member` WHERE `idmember_aid` = '$idmember' ");
	$jumdata = $quip304->num_rows;				
	if($jumdata>0):

			echo'<script language="JavaScript">';

				echo'window.location="../affiliate_list.php?msg=This member already registred affiliate.";';

			echo'</script>';

	else:	

			$quppe = $db->query("SELECT max(`id`) as lastid FROM `affiliate_member` ");
			$resdata = 	$quppe->fetch_assoc();
			$latestid = $resdata['lastid']+1;
			$tokenpaymntid = $_POST['uniqcode'];				

			$quipp = $db->query("SELECT `id` FROM `affiliate_member` WHERE `token_affiliate` = '$tokenpaymntid' ");
			$jumdata2 = $quipp->num_rows;				
			if($jumdata2>0):
				echo'<script language="JavaScript">';	
					echo'window.location="../affiliate_list.php?msg=This uniq code already registred affiliate.";';
				echo'</script>';			
			else:
		
				$quipst4 = $db->query("INSERT INTO `affiliate_member` (`id`,`idmember_aid`,`token_affiliate`,`status`) VALUES ('$latestid','$idmember','$tokenpaymntid','1') ");
				require("email_orderan/affiliate_member_email_submit.php");
	
				echo'<script language="JavaScript">';
					echo'window.location="../affiliate_list.php?msg=Insert data successful.";';
				echo'</script>';
			endif;
										  

	endif;	
?>                    