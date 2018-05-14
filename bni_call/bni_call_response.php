<?php
include("../config/connection.php");
//include_once __DIR__ . "/BniHashing.php";
include_once "BniHashing.php";


// FROM BNI
// Demo
// $client_id = '337';
// $secret_key = '9474463622ad34801ffe28aab9f23580';

$client_id = '53860';
$secret_key = '3b65c18b24e25fb469018b3a27bfb022';

// URL utk simulasi pembayaran: http://dev.bni-ecollection.com/


$data = file_get_contents('php://input');

$data_json = json_decode($data, true);

if (!$data_json) {
    // handling orang iseng
    //echo '{"status":"999","message":"jangan iseng :D"}';
    exit;
}
else {
    if ($data_json['client_id'] === $client_id) {
        $data_asli = BniHashing::parseData(
            $data_json['data'],
            $client_id,
            $secret_key
        );

        //$temp = json_encode($data_asli);
        //$query = $db->query("INSERT INTO `pages` (`description`) VALUES ('$temp')");

        if (!$data_asli) {
            // handling jika waktu server salah/tdk sesuai atau secret key salah
            //echo '{"status":"999","message":"waktu server tidak sesuai NTP atau secret key salah."}';
            exit;
        }else {
            $trx_id = $data_asli['trx_id'];
            $va_bni = $data_asli['virtual_account'];
            $query = $db->query("UPDATE `order_header` 
                                 SET `status_payment` = 'Confirmed', `va_bni` = '$va_bni'
                                 WHERE `id`= '$trx_id'");
            exit;
        }




    }
}
