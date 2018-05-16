<?php 
	//config goes here
	$config_query = $db->query("SELECT * FROM `web_config` WHERE `id`=1");
	$row_config = $config_query->fetch_assoc();
	
	$emailconfig		= $row_config['email'];
	$companyconfig		= $row_config['company'];
	$nameconfig			= $row_config['name'];
	$company_address	= $row_config['company_address'];
	$phonepig			= $row_config['phone'];
	$faxpig				= $row_config['fax'];	
	$whatsapppig		= $row_config['whatsapp'];
	$pinbbpig			= $row_config['pinbb'];	
	$picconfig			= $row_config['logo_image'];
		
	$facebook_link 		= $row_config['facebook_link'];
	$twitter_link		= $row_config['twitter_link'];
	$instagram_link		= $row_config['instagram_link'];
	$youtube_link		= $row_config['youtube_link'];
	
	$googleplusmapcode		= $row_config['google_map'];
	$point_reward_syarat	= $row_config['1_point_reward'];
	$draft_quotation_expiry = $row_config['draft_quotation_expiry'];
	$handling_fee 			= $row_config['handling_fee'];
	
	$commission_persenpig = $row_config['commission_persen'];
	
	
	//EMAIL SUBJECT FOR EMAIL SENDING SYSTEM
	$email_from			= 'From: Fixcomart <info@fixcomart.com>';	
	$inquiry_subject	= 'New inquiry from your website';
	$inquiry_subject_register	= 'Member Registration On Fixcomart';

	$orderemailmember   = "Order Notification On ".$nameconfig."";
	$ordecanceltext     = "Order Cancel Notification On ".$nameconfig."";
	$confirmemailtext   = "Order Confirmed Notification On ".$nameconfig."";
	$autocanceltext     = "Auto Cancel Notification On ".$nameconfig."";
	$depositconfirmemailtext   = "Top Up Deposit Notification On ".$nameconfig."";
	$upgrademembership   = "Upgrade Membership Notification On ".$nameconfig."";

	$registeremail_text = 'Registration Notification On '.$nameconfig.'';
	$registeremail_textcorporate = 'Registration Corporate Notification On '.$nameconfig.'';
	$resetpassid_text = 'We\'ve resetted your password On '.$nameconfig.'';
	$quotationrequest_text = 'Quotation Request Notification On '.$nameconfig;
 ?>