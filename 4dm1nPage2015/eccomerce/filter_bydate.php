<?php
@session_start();
$idmember = $_POST['idmember'];
$startdate = $_POST['startdate'];
$enddate = $_POST['enddate'];

if($startdate<>'' and $enddate<>''):
	$_SESSION['startdate'] = $startdate;
	$_SESSION['enddate'] = $enddate;
else:
	unset($_SESSION['startdate']);
	unset($_SESSION['enddate']);
endif;

if($idmember > 0):
	echo'<script language="JavaScript">';
		echo'window.location="../ordermember.php?idmember='.$idmember.'";';
	echo'</script>';	
else:
	echo'<script language="JavaScript">';
		echo'window.location="../ordermember.php";';
	echo'</script>';	
endif;
?>