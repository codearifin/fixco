<?php 
	@session_start();
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("function.php");
?>
	<div class="bg-luar-popup" style="width:350px; padding:20px; background:#fff;">
   		<div class="product-detail-wrapper" style="width:100%;">
            <div class="right-prodlsit-detail bg-luar-popup-2" style="width:100%;">
            	 <h1 style="font-size:18px; font-family:calibri;">Browse by member</h1>
                
                <div class="detail-information">
                	<strong style="font-family:calibri; font-size:14px; padding-bottom:30px; display:block; padding-bottom:10px;">Search member :</strong>
         
                            	<table cellpadding="0" cellspacing="0" class="pdpt-table">
                                    <tr>
                                        <td class="td1"><input type="text" name="membername" class="membername" style="padding:7px 5px 7px 5px; width:250px;" /></td>
                                    </tr>
 
                                     <tr>
                                        <td class="td1">
                                        	<a href="#" class="carimemberlist" style="display:block; margin-top:10px; background:#000; color:#fff; padding:5px 15px 5px 15px; width:45px;">Search</a>
                                        </td>
                                    </tr>
                                                                       
                                </table>
                                
                                <div style="padding-top:20px; line-height:1.6em;" class="memberdata_list">
                                	
                                </div>
	
                            </div><!-- .btn-area -->
                        </div><!-- .btn-area-wrap -->
           
                </div><!--.detail-information-->
                
            </div><!--.right-prodlsit-detail-->
            <div class="clear"></div>
        </div><!--.product-detail-wrapper-->
    </div><!--.bg-luar-popup-->  

<script type="text/javascript">
$(document).ready(function() { 

	$("a.carimemberlist").click(function(e) {
		var keyword = $(".membername").val();
		$.post("eccomerce/carimember_list_data.php", {"keyword": keyword},
		function(data) {				 
			$(".memberdata_list").html(data);
		}); 
		e.preventDefault();	 
	});	
	
});	
</script>