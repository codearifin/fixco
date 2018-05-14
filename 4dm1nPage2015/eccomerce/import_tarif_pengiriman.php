    <div style="margin:0 auto; width:600px; margin-top:40px; font-family:Arial; font-size:12px;">
       
            	<form action="do_import_data_pengiriman.php" method="post" class="general-form" id="addproduct_form" enctype="multipart/form-data">
                    <table cellspacing="0" cellpadding="0" class="browse-table">
  
                        <tr>
                            <td colspan="2"><strong>Import Tarif Pengiriman</strong></td>
                        </tr>                           
                        
                        <tr>
                            <td style="padding-right:40px;  padding-top:10px;">Upload Excel File [.CSV Only ]</td>
                            <td>
                                <input type="file" name="images" />
                            </td>
                        </tr>

                        <tr>
                            <td class="td1"></td>
                            <td style="padding-top:10px;">
                                <div class="btn-area clearfix">
                                    <input type="submit" value="IMPORT DATA" name="submit" style="background:#006699; color:#fff; border:none; cursor:pointer; padding:8px 10px;" />
                                </div><!-- .btn-area -->
                                <div style="color:#FF3333; padding-top:10px; font-size:12px;">
                                	<?php if(isset($_GET['msg'])): echo $_GET['msg']; endif;?>
                                </div>
                            </td>
                        </tr>
                                                
                    </table>
                </form>
     </div>           
