<style>
	.form-submitcari select { padding:6px 5px 6px 5px; width:250px;}	
	.form-submitcari input { text-align:center; cursor:pointer; background:#006699; color:#fff; padding:6px 5px 6px 5px; width:140px; border:none;}
</style>	
    <div style="width:550px; padding:10px 20px 10px 20px;">      
            	<div style="padding-top:20px; padding-bottom:20px; font-weight:600; font-size:16px;">DOWNLOAD ORDER SUMMARY</div>  
                <form action="eccomerce/do_download_orderheader_summary.php" method="post" class="form-submitcari" enctype="multipart/form-data">
                   
                    <table cellspacing="0" cellpadding="0">
                  
                        <tr>
                            <td style="padding-bottom:10px; width:150px;"><label for="dash-datepost">Periode (Start)</label></td>
                            <td style="padding-bottom:10px;">
										<select name="tgl_d" style="width:60px;">
											<?php for($x = 1; $x<32; $x++){
													echo'<option value="'.sprintf('%02d',$x).'">'.sprintf('%02d',$x).'</option>';
											      }
											?>
										</select>
										<select name="tgl_m" style="width:120px;">
											<option value="01" selected="selected">January</option>
											<option value="02">February</option>
											<option value="03">March</option>
											<option value="04">April</option>
											<option value="05">May</option>
											<option value="06">June</option>
											<option value="07">July</option>
											<option value="08">August</option>
											<option value="09">September</option>
											<option value="10">October</option>
											<option value="11">November</option>
											<option value="12">December</option>
										</select>
										<select name="tgl_y" style="width:75px;">
											<?php  
												 $yearnow = date("Y");
												 $yearnow_plus = $yearnow+1;
												 $yearnow_min = $yearnow-1;
												 
												 for($zz = ( $yearnow_min-10 ); $zz < $yearnow_min ; $zz++){
													echo'<option value="'.$zz.'">'.$zz.'</option>';
											      }
											     echo'<option value="'.$yearnow_min.'">'.$yearnow_min.'</option>';
												 echo'<option value="'.$yearnow.'" selected="selected">'.$yearnow.'</option>';
												 echo'<option value="'.$yearnow_plus.'">'.$yearnow_plus.'</option>';
											?>
										</select>	
                            </td>
                        </tr>

                         <tr>
                            <td style="padding-bottom:10px; width:150px;"><label for="dash-datepost">Periode (End)</label></td>
                            <td style="padding-bottom:10px;">
										<select name="tgl_d2" style="width:60px;">
											<?php for($x = 1; $x<32; $x++){
													echo'<option value="'.sprintf('%02d',$x).'">'.sprintf('%02d',$x).'</option>';
											      }
											?>
										</select>
										<select name="tgl_m2" style="width:120px;">
											<option value="01" selected="selected">January</option>
											<option value="02">February</option>
											<option value="03">March</option>
											<option value="04">April</option>
											<option value="05">May</option>
											<option value="06">June</option>
											<option value="07">July</option>
											<option value="08">August</option>
											<option value="09">September</option>
											<option value="10">October</option>
											<option value="11">November</option>
											<option value="12">December</option>
										</select>
										<select name="tgl_y2" style="width:75px;">
											<?php  
												 for($zzx = ( $yearnow_min-10 ); $zzx < $yearnow_min ; $zzx++){
													echo'<option value="'.$zzx.'">'.$zzx.'</option>';
											      }
											     echo'<option value="'.$yearnow_min.'">'.$yearnow_min.'</option>';
												 echo'<option value="'.$yearnow.'" selected="selected">'.$yearnow.'</option>';
												 echo'<option value="'.$yearnow_plus.'">'.$yearnow_plus.'</option>';
											?>
										</select>	
                            </td>
                        </tr>                                     

                        <tr>
                            <td style="padding-bottom:10px;"><label for="dash-urel">Status Order</label></td>
                            <td style="padding-bottom:10px;">
                                <select name="status_payment" style="width:170px;">
                                    <option value="ALL" selected="selected">ALL</option>
                                    <option value="Pending On Payment">Pending On Payment</option>
                                    <option value="Waiting">Waiting Confirm</option>
                                    <option value="Confirmed">Paid</option>
                                    <option value="Cancelled">Cancelled</option>
                                    <option value="Transaction Fail">Fail</option>
                                    <option value="Auto Cancelled">Auto Cancelled</option>
                                </select>
                            </td>
                        </tr>     
                                
                        <tr>
                            <td style="padding-bottom:10px;"><label for="dash-urel2">Metode Pembayaran</label></td>
                            <td style="padding-bottom:10px;">
                                <select name="payment_metod" style="width:170px;">
                                    <option value="ALL">ALL</option>
                                    <option value="BANK TRANSFER">BANK TRANSFER</option>
                                    <option value="Veritrans">Veritrans</option>
                                </select>
                            </td>
                        </tr>                                                  
                      
                         <tr>
                            <td style="padding-top:10px;"></td>
                            <td style="padding-top:10px;">
                                <div class="btn-area clearfix">
                                    <input type="submit" value="DOWNLOAD EXCEL" class="submit-btn left" name="submit" />
                                </div><!-- .btn-area -->
                            </td>
                        </tr>
                    </table>
                </form>
</div>