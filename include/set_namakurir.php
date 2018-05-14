<?php 

@session_start();	

$namakurir = $_POST['namakurir'];

if($namakurir=="OTHER"){

	$_SESSION['kuririd_pilih'] = "OTHER";

	$_SESSION['kuririd_pilih_lainnya'] = '';

}else{

	$_SESSION['kuririd_pilih'] = $namakurir;

	unset($_SESSION['kuririd_pilih_lainnya']);

}

echo $_SESSION['kuririd_pilih'];

?>