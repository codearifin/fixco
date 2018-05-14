<?php
	$page_access_token = 'AQB21MJqUu4P9kYGSWtyjAMKocidopaMjsYY89YB9C829iQXhR3DLqx_ZeFz4MgaD-biYvwQrQMh03LTAoe4uXyTKvR3QrOrXOb73pag5KY0x48Nnx7G8SsUTRm3iypweArt-Wqac9nOi6lkCiR4XD3ux6gPcvpIE2ZwAExYKJxSLv5d-oFDkrgCul1XkuxKyfIGkDd0euzdIY2JrFTwwAj547a_EYUPVwXlAIbR7SNa0qOYYGcI3swlpoyfnfzLrrNHipbsRXfKEbohz4dzFypFscS4kM08idhGaTSPYtKqOCYDlhLsUAM1YI1NngrvmpFDlPVVq-0L5ogphCs7Mn1X';
	$page_id = '1087994144639327';
	
	$post_url = 'https://graph.facebook.com/'.$page_id.'/feed';
	
	$data['access_token'] = $page_access_token;
	$data['picture'] = "http://www.example.com/image.jpg";
	$data['link'] = "http://www.example.com/";
	$data['message'] = "Your message";
	$data['caption'] = "Caption";
	$data['description'] = "Description";
	

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $post_url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$return = curl_exec($ch);
	curl_close($ch);
	
	var_dump($return);
?>	