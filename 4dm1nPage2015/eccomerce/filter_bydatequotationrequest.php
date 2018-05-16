<?php

@session_start();

$startdate = $_POST['startdate'];

$enddate = $_POST['enddate'];



if($startdate<>'' and $enddate<>''):

	$_SESSION['startdate'] = $startdate;

	$_SESSION['enddate'] = $enddate;

else:

	unset($_SESSION['startdate']);

	unset($_SESSION['enddate']);

endif;

echo'<script language="JavaScript">';

	echo'window.location="../quotation_request.php";';

echo'</script>';

?>