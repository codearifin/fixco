<?php

	$quippml = $db->query("SELECT `list_id`, `apikey` FROM `mailchimp_setting` ");

	$dataml = $quippml->fetch_assoc();

    $apikey = $dataml['apikey'];

    $listId = $dataml['list_id']; 

    $apiUrl = 'http://api.mailchimp.com/1.3/';

?>