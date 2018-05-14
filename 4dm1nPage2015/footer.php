    <div id="cms-footer">
    	Copyright &copy; 2015 <a href="http://nukegraphic.com/" title="Nukegraphic - Professional Web Design and Development" target="_blank">Nukegraphic</a>. All rights reserved.
    </div><!-- #cms-footer -->
    
</div><!-- #centered -->
</body>
</html>

<?php if(isset($_SESSION['flashdata'])) { ?>
	<!-- ALERTIFY -->
	<script src="js/alertifyjs/alertify.min.js"></script>
	<link rel="stylesheet" href="js/alertifyjs/css/alertify.min.css" />
	<link rel="stylesheet" href="js/alertifyjs/css/themes/default.min.css" />
	<script>
		$('document').ready(function(){
		alertify.alert('<?php echo ($_SESSION['flashdata']['type'] == 'error' ? "alert":"confirm")?>').set({
			transition:'flipx',
			title:'<?php echo ($_SESSION['flashdata']['type'] == 'error' ? "Alert":"Confirm")?>',message: '<?php echo $_SESSION['flashdata']['message']?>'}).show();
		});
	</script>
	<?php 
	unset($_SESSION['flashdata']);
} ?>