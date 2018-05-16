<?php 
@session_start();
if(isset($_SESSION['error_msg'])): $statalert = $_SESSION['error_msg']; else: $statalert = ''; endif;
	
	
if($statalert=="error-capctha"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("FAILED, WRONG CAPTCHA!", "Please input the correctly!", "error");
		});
	</script>';

elseif($statalert=="member_corporate"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("FAILED", "Login gagal, Anda terdaftar sebagai corporate member! Silahkan login sebagai corporate member.", "error");
		});
	</script>';
	
elseif($statalert=="member_regular"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("FAILED", "Login gagal, Anda terdaftar sebagai regular member! Silahkan login sebagai regular member.", "error");
		});
	</script>';
	
		
elseif($statalert=="error-emailaddress"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("FAILED", "Email Anda sudah terdaftar.", "error");
		});
	</script>';

elseif($statalert=="success-newsletter"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Email Anda berhasil didaftarkan.", "success");
		});
	</script>';
	
elseif($statalert=="savequoteloist"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Save draft quotation berhasil.", "success");
		});
	</script>';	
	
elseif($statalert=="saveprodoke"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Produk berhasil ditambahkan ke quotation list.", "success");
		});
	</script>';	

elseif($statalert=="updateprodoke"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Edit produk quotation list berhasil.", "success");
		});
	</script>';	
	
elseif($statalert=="deleteprodoke"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Produk quotation berhasil dihapus.", "success");
		});
	</script>';	
		
elseif($statalert=="savepasswpord"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Password baru berhasil disimpan.", "success");
		});
	</script>';	
	
elseif($statalert=="savedeposit"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Top Up Deposit Anda akan segera kami proses.", "success");
		});
	</script>';	
	
elseif($statalert=="saveuseroke"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Tambah user berhasil dilakukan.", "success");
		});
	</script>';	
	
elseif($statalert=="toikenUrelfail"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("FAILED URL", "Please check your URL, this URL invalid.", "error");
		});
	</script>';	

elseif($statalert=="uploadgagal"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("FAILED", "Data membership Anda sudah ter-registrasi sebelumnya.", "error");
		});
	</script>';	


elseif($statalert=="save_WITHDRAWAL_fail"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("FAILED", "Silahkan cek kembali Total Availability Commission / Total Withdrawal Anda.", "error");
		});
	</script>';	

elseif($statalert=="save_WITHDRAWAL_failpass"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("FAILED", "Silahkan cek kembali password yang anda masukan salah", "error");
		});
	</script>';	
		
elseif($statalert=="save_WITHDRAWAL"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Terima kasih, Kami akan segera memproses withdrawal Anda.", "success");
		});
	</script>';
	
elseif($statalert=="save_redeemsukses"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Terima kasih, Kami akan segera memproses redeem reward anda.", "success");
		});
	</script>';
	
	
elseif($statalert=="savebayaroke"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Terima kasih, Kami akan segera memproses pembayaran anda.", "success");
		});
	</script>';
	
elseif($statalert=="success-ulasan"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Terima kasih untuk ulasan anda, Kami akan segera memproses ulasan anda.", "success");
		});
	</script>';
		
elseif($statalert=="success-sendcontact"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Your message has been sent. We will contact you shortly!", "success");
		});
	</script>';

elseif($statalert=="token-failed"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("FAILED, TOKEN FORM!", "Please refresh your computer and try again.", "error");
		});
	</script>';	
	
		
elseif($statalert=="email-failed"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("UNSUCCESSFUL", "Your email is already registered.", "error");
		});
	</script>';		
	
elseif($statalert=="registersuccess"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Please Login and complete your personal data beforehand!", "success");
		});
	</script>';	

elseif($statalert=="registersuccess_corporate"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Terima Kasih, Kami akan segera memproses data anda.", "success");
		});
	</script>';		
	
elseif($statalert=="login-failed"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("LOGIN UNSUCCESSFUL", "Please confirm your email and password combination, and make sure you have registered.", "error");
		});
	</script>';		

elseif($statalert=="login-failed2"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("LOGIN UNSUCCESSFUL", "Please make sure you status account is active.", "error");
		});
	</script>';	


elseif($statalert=="email-invalid"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("UNSUCCESSFUL", "Please confirm your email address.", "error");
		});
	</script>';	

elseif($statalert=="password-reseted"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Please check your email to retrieve your new password.", "success");
		});
	</script>';				


elseif($statalert=="logintwiitter_failed"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("UNSUCCESSFUL", "Please allow permission this app in your google plus account.", "error");
		});
	</script>';	

elseif($statalert=="login-first"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("FAILED LOGIN", "Please login first before you accsess this page.", "error");
		});
	</script>';			

elseif($statalert=="empty-cart"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("ERROR", "Your shopping bag is empty.", "error");
		});
	</script>';	
	
elseif($statalert=="update-oke"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Save data successful.", "success");
		});
	</script>';

elseif($statalert=="delete-oke"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Delete data successful.", "success");
		});
	</script>';	

elseif($statalert=="ordercancel"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Your order has been cancelled.", "success");
		});
	</script>';


elseif($statalert=="konfirmasigagal"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("ERROR", "Confirm payment fail, Please check your Order ID and payment status your order.", "error");
		});
	</script>';

elseif($statalert=="konfirmasioke"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Confirm payment successful, Please waiting approval.", "success");
		});
	</script>';
elseif($statalert=="addquotationoke"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("SUCCESSFUL", "Request for quotation has been successfully submitted.", "success");
		});
	</script>';
elseif($statalert=="addquotationgagal"):

	echo'<script type="text/javascript">
		$(document).ready(function() {
			 swal("ERROR", "Request for quotation submission failed, Please try again.", "error");
		});
	</script>';
		
endif;

if(isset($_SESSION['error_msg'])): unset($_SESSION['error_msg']); endif;
?>