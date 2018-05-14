
Bni Hashing 
---------------------------------------------

1. Instalation 

require_once __DIR__'/BniHashing.php'

2. Usage
	
	a. Encrypt Data
	example : 
		$data = array(
			"client_id" => "001",
			"trx_amount" => "100000",
			"customer_name" => "Mr. X",
			"customer_email" => "xxx@email.com",
			"customer_phone" => "08123123123",
			"virtual_account" => "8001000000000001",
			"trx_id" => "1230000001",
			"type" => "createBilling",
			"datetime_expired" => "2015-07-01 16:00:00",
			"type" => "createBilling"
		);

		$client_id = '001'; //client id from BNI
		$secret_key = 'ea0c88921fb033387e66ef7d1e82ab83'; // secret key from BNI
		
		$hashdata = BniHashing::hashData($data, $client_id, $secret_key);

		result : "GEhHGEwbHh0WE0QNA0ZPTU1VCkVPejghNxFFGgQ9NVxVDnBBAVddWFYDUTQRFBMTGRc3EwgDeFR5BE8KMANACTs3PgJ2XltdUk57BT9IUURRVDcgCQ0OYFRGA0lLfkBLUwIzDTVLXlxWUQN3U0JTTFdVewggOEYfRhNIGhRERBoWOD0CClFaXldCA3FBR0ZSXlUJCSA3TxdEE0UYE0FCGRNFQhBDGgoVBVUICz9MSAUiCkYYGkVGGEQRRhkEPjRcXQV2A00KTFtHQwp3I0xPUFFVfQgSOHpICUYJUk92ck1bBnpSeUwKJAQTR0MVERMaFhdGBxdLURdEHEUYBQ4";

	b. Decrypt(Parse) Data
	example :
		$strings = "GkdDFUMcHh0WE0QNA1ZXRVxcCQggOEYXRQNBC0ZyB0kFUAwCCFpgSUtFOUwCFRUWGRdFFxZFSAlABAtRVQUHSk90c0N2WF1XVwNQNRgTFBQYGEUWF0VGGEQRRhkEPjRLWQgFUABNW0hQQwN3Ax0FMVoVNj4IQjhXdloCTlAFclZXeDMaNhgZHBUVTEgXHBwFFQl5SFp6C1ABR3RYRAp_TlEJNBo1GxgaGA5GSQ0VFwMaG08YGk9GITYNOFxUCnFJUQQGTwcKIwsTEkZCERMFEApXd19TewRbdEICWFd_BwodOEIQRBgYGgReFA";

		$client_id = '001'; //client id from BNI
		$secret_key = 'ea0c88921fb033387e66ef7d1e82ab83'; // secret key from BNI
		
		$parsedata = BniHashing::parseData($strings, $client_id, $secret_key);
		
		result : 
		array(
			"virtual_account" => "8001000000000001",
			"customer_name" => "Mr. X",
			"payment_ntb" => "0123456789",
			"payment_amount" => "100000"
			"datetime_payment" => "2015-06-23 23:23:09",
			"trx_amount" => "100000",
			"va_status” => "2",
		)
