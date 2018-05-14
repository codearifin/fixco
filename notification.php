<?php

@session_start();

require('../config/connection.php');
	
	//Get POST data from Kredivo
	$data = json_decode(file_get_contents('php://input'), true);

	if($data != NULL)
	{
		//MERCHANT RESPONSE to Kredivo

		$message = array('status' => 'OK', 'message' => 'We receive your response.');

		$message = json_encode($message);


		// Mengirimkan response terhadap Kredivo
		echo $message;


		$tid 	= $data['transaction_id'];
		
		$sk 	= $data['signature_key'];  

		//END

		// Link sandbox 	: 'https://sandbox.kredivo.com/kredivo/v2/update? || Link real 		: 'https://api.kredivo.com/kredivo/v2/update?

		//UPDATE on Kredivo
		$tf 	= curl_init('https://sandbox.kredivo.com/kredivo/v2/update?transaction_id='.$tid.'&signature_key='.$sk);
		
		curl_setopt_array($tf, array(CURLOPT_RETURNTRANSFER => TRUE));

		$transaction 	= curl_exec($tf);

		if($transaction === false):

			die(curl_error($tf));

		else:

			//decode Kredivo response | Update or delete or anything based on the result
			$result 		= json_decode($transaction, true);

			$orderid 		= $result['order_id'];

			$status 		= $result['transaction_status'];

			//Transaction status check

			if($status == 'settlement'):

				$str 			= "UPDATE `order_header` SET `status_payment` = 'Confirmed' WHERE `id`='$orderid'";

			else:

				if($status == 'DENY'):

					$str 			= "UPDATE `order_header` SET `status_payment` = 'Denied' WHERE `id`='$orderid'";

				elseif($status == 'CANCEL'):
				
					$str 			= "UPDATE `order_header` SET `status_payment` = 'Canceled' WHERE `id`='$orderid'";


				elseif($status == 'EXPIRE'):
				
					$str 			= "UPDATE `order_header` SET `status_payment` = 'Expired' WHERE `id`='$orderid'";

				endif;

			endif;

			$query = $db->query($str) or die($db->error);

			curl_close($tf);

		endif;

		//END
		
	}

	echo "Kosong";

?>