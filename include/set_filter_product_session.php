<?php 
	@session_start();
	
	$urlpage = $_POST['urlpage'];

	$brandListItem = $_POST['brandListItem'];

	$priceRange1 = $_POST['priceRange1'];

	$priceRange2 = $_POST['priceRange2'];

	

	$rat5 = $_POST['rat5'];

	$rat4 = $_POST['rat4'];

	$rat3 = $_POST['rat3'];

	$rat2 = $_POST['rat2'];

	$rat1 = $_POST['rat1'];

	$rat0 = $_POST['rat0'];

	
	$_SESSION['UrelPageidprod'] = $urlpage;

	//brand

	if($brandListItem==""): unset($_SESSION['filter_brandid']); else: $_SESSION['filter_brandid'] = $brandListItem; endif;

	

	//price range

	$_SESSION['filter_price1'] = $priceRange1; $_SESSION['filter_price2'] = $priceRange2;

	

	//ratting

	if($rat5==1): $_SESSION['filter_rate5'] = $rat5; else: unset($_SESSION['filter_rate5']); endif;

	if($rat4==1): $_SESSION['filter_rate4'] = $rat4; else: unset($_SESSION['filter_rate4']); endif;

	if($rat3==1): $_SESSION['filter_rate3'] = $rat3; else: unset($_SESSION['filter_rate3']); endif;

	if($rat2==1): $_SESSION['filter_rate2'] = $rat2; else: unset($_SESSION['filter_rate2']); endif;

	if($rat1==1): $_SESSION['filter_rate1'] = $rat1; else: unset($_SESSION['filter_rate1']); endif;

	if($rat0==1): $_SESSION['filter_rate0'] = $rat0; else: unset($_SESSION['filter_rate0']); endif;

	

	

	//attribute hasil

	$hsilatrr = $_POST['hsilatrr'];	

	if($hsilatrr==""): unset($_SESSION['master_attibutelist']); else: $_SESSION['master_attibutelist'] = $hsilatrr; endif; 

	

	

	//print session--

	echo $_SESSION['filter_brandid'];

	echo $_SESSION['filter_price1'];

	echo $_SESSION['filter_price2'];

	

	echo $_SESSION['filter_rate5'];

	echo $_SESSION['filter_rate4'];

	echo $_SESSION['filter_rate3'];

	echo $_SESSION['filter_rate2'];

	echo $_SESSION['filter_rate1'];

	echo $_SESSION['filter_rate0'];

	echo $_SESSION['master_attibutelist'];
	
	echo $_SESSION['UrelPageidprod'];

?>