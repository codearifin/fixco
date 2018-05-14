<?php 
	require('../../config/connection.php');
	require('../../config/myconfig.php');

if(isset($_POST['submit'])){
	$anggotanya_member_id = $_POST['anggotanya_member_id'];
	$member_id = $_POST['member_id'];
	$komisi_persen = $_POST['komisi_persen'];
	
	if($anggotanya_member_id==$member_id):
		echo'<script language="JavaScript">';
			echo'window.location="../affiliate_memberlist.php?msg=Insert fail, data already exist.";';
		echo'</script>';			
	else:
		
		$quipp = $db->query("SELECT `id` FROM `affiliate_memberid` WHERE `member_id` = '$member_id' and `anggotanya_member_id` = '$anggotanya_member_id' ");
		$jumpage = $quipp->num_rows;
		if($jumpage>0):
			echo'<script language="JavaScript">';
				echo'window.location="../affiliate_memberlist.php?msg=Insert fail, data already exist.";';
			echo'</script>';		
		else:
			$query = $db->query("INSERT INTO `affiliate_memberid` (`member_id`,`anggotanya_member_id`,`komisi_persen`) VALUES ('$member_id','$anggotanya_member_id','$komisi_persen')");			
			echo'<script language="JavaScript">';
				echo'window.location="../affiliate_memberlist.php?msg=Insert successful.";';
			echo'</script>';
		endif;
	
	endif;
}		
?>
