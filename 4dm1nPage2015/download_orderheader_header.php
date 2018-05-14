   <form action="lib/download_orderheader_header.php" method="post" class="general-form" id="voucher_doaddform" enctype="multipart/form-data">
   
    <table cellspacing="0" cellpadding="0" class="browse-table">
  

        <tr>
            <td class="td1"><label for="dash-datepost">Periode (Start)</label></td>
            <td>
            	<?php date_default_timezone_set('Asia/Jakarta'); $dateNow = date("d/m/Y");?>
                <!--<input id="dash-datepost" type="text" name="datepost" value="<?php echo $dateNow;?>" class="date-pick dp-applied valid" style="width:80px;" />
                <a href="#" class="dp-choose-date" title="Choose Date">Choose Date</a>-->
                <input type="date" name="datepost">
            </td>
        </tr>

         <tr>
            <td class="td1"><label for="dash-datepost">Periode (End)</label></td>
            <td>
                <!--<input id="dash-datepost2" type="text" name="datepost2" value="<?php echo $dateNow;?>" class="date-pick" style="width:80px;" />-->
            	<input type="date" name="datepost2">
            </td>
        </tr>                                                 
      
         <tr>
            <td class="td1"></td>
            <td>
                <div class="btn-area clearfix">
                    <input type="submit" value="DOWNLOAD EXCEL" class="submit-btn left" name="submit" />
                </div><!-- .btn-area -->
            </td>
        </tr>
    </table>
</form>