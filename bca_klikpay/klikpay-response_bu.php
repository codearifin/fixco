<?php
@session_start();
include("../include/connection.php");

(string) $output = "";
$statuspayment = $_POST['transactionStatus'];
$idtrx_odr = $_POST['merchantTransactionID'];
if($statuspayment=="APPROVED"):
	$quep = mysql_query("UPDATE `order_header` SET `status_payment`='Confirmed' WHERE `bca_tokenid`='$idtrx_odr' ");
endif;
echo'<script type="text/javascript">window.location="/visa-master-confirmation/'.$idtrx_odr.'/'.$statuspayment.'"</script>';

/*
foreach ($_POST as $key => $value) {

	$output .= "  <tr>\n";

	$output .= "    <td>". $key ."</td>\n";

	$output .= "    <td>". $value ."</td>\n";

	$output .= "  </tr>\n";

}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title>Speed Order Example Response</title>

<style type="text/css">

<!--

table td {

    font-family: "Trebuchet MS", Tahoma, Arial;

    font-size: 10pt;

    padding:0px;

    margin:0px;

    border-bottom: 1px solid #ddd;

}

table td.tabhead {

    font-weight: bold;

    background-color: #ddd;

    text-align: center;

    min-width: 200px;

}

-->

</style>

</head>

<body>

<div id="Content">

<table>

  <tr>

    <td class="tabhead">Parameters</td>

    <td class="tabhead">Value</td>

  </tr>

<?php echo $output; ?>

</table>

</div>

</body>

</html>

*/

?>

