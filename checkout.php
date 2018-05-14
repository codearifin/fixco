<?php include("header.php"); 

$jml_item = $jcart->items_num();
if(isset($_SESSION['user_token'])==''):
	echo '<script language="JavaScript">window.location="'.$GLOBALS['SITE_URL'].'shopping-cart";</script>';		
endif;

if($_SESSION['user_statusmember']=="REGULAR MEMBER"):
	//no action
else:
	echo '<script language="JavaScript">window.location="'.$GLOBALS['SITE_URL'].'finishorder-corporate";</script>';		
endif;

if($jml_item<1):
	echo '<script language="JavaScript">window.location="'.$GLOBALS['SITE_URL'].'shopping-cart";</script>';	
endif;

if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
$query = $db->query("SELECT * FROM `member` WHERE `tokenmember`='$Usertokenid'");
$res = $query->fetch_assoc();
$idmember = $res['id']; $memberName = $res['name'].' '.$res['lastname'];
$status_complete = $res['status_complete'];
if($status_complete==0):
	echo '<script language="JavaScript">window.location="'.$GLOBALS['SITE_URL'].'my-account";</script>';
endif;	
?>
</head>

<body>

<?php include("head.php"); ?>

<section id="breadcrumbs">
	<div class="container">
    	<div class="row">
        	<ul class="breadcrumbs">
            	<li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>
                <li><a href="<?php echo $GLOBALS['SITE_URL'];?>shopping-cart">Shopping Cart</a></li>
                <li class="f-pb">Checkout</li>
            </ul><!-- .breadcrumbs -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- #breadcrumbs -->

<section id="template-page" class="section one-column">
	<div class="container">
    	<div class="row">
        	<div class="template-wrap">
            	<ul class="top-bc-wrap">
                	<?php include("cart-list-link.php");?>
                </ul><!-- .top-bc-wrap -->
                <h1 class="f-pb">Shopping Cart</h1>
                
                <!-- SHOPPING CART COPY START HERE -->
                <div class="checkout-page clearfix">
                    <div class="cp-top">
                        <h2 class="f-pb">Ringkasan Pembelanjaan</h2>
                           
                        <?php
                                $totalsatuan = 0; $warnaprod = ''; $EURsize = ''; $UKsize = ''; $beratbarang = 0; $JumlahBeratbrg = 0; $grandTotal = 0; $stokalert = 0; $jumlahstokitem = 0;
                                $jumlahstokitem = 0; $ongkirPrice = 0; $diskonPrice = 0; $totaldiskoncari = 0; $status_voucher_oke = 0;	$listbrandprod = ''; $brandID = 0; $bonus_product = '';
								$totaldiskoncari_grandtotal = 0; $listproductid = '';
												
                                foreach ($jcart->get_contents() as $item){
                    
                                    $itemwarnalist = explode("#",$item['nama_paket']);
                                    $warnaprod = $itemwarnalist[0];
                            
                                    //total price
                                    $totalsatuan = $item['price']*$item['qty'];
                                    //weight
                                    $beratbarang = getberatbarangTunas($item['id'],$item['qty']);
                                    $JumlahBeratbrg =  $JumlahBeratbrg + $beratbarang;
                                    //grand total
                                    $grandTotal = $grandTotal+($item['qty']*$item['price']);
    
                                    //check stock
                                    $jumlahstokitem = getstockitemprod($item['id'],$item['id_prod']);
                                    
                                    if($item['qty']>$jumlahstokitem):
                                        $stokalert = 1;
                                        $stylebg = 'style="background:#f1f1e8;"';
                                    else:
                                        $stylebg = '';		
                                    endif;
                                    
                                    
                                    if(isset($_SESSION['voucher_redeeemID'])<>''): 
                                        $totaldiskoncari = getdiskonmember($_SESSION['voucher_redeeemID'],$item['id_prod'],$item['qty'],$item['price'],$idmember);
                                        $diskonPrice = $diskonPrice + $totaldiskoncari;
										
										$brandID = getidbarndproductlist($item['id_prod']);
										if($brandID>0): $listbrandprod.=$brandID.'#'; endif;
										
										$listproductid.=$item['id_prod'].'#';
										
                                    endif;
                                    
                                    echo'<div class="cpt-child" '.$stylebg.'>';
                                            echo'<div class="img-wrap"><a href="'.$GLOBALS['SITE_URL'].''.$item['url'].'" target="_blank"><img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$item['description'].'" /></a></div>';
                                            echo'<div class="scb1-txt clearfix">';
                                                echo'<div class="scb1-1">';
                                                    echo'<h3 class="f-pr"><a href="'.$GLOBALS['SITE_URL'].''.$item['url'].'" target="_blank">'.$item['name'].'</a>';
                                                    
                                                    echo '<span class="jcartlistitem">';
                                                        echo '<br /><strong>'.$item['skuprod'].'</strong> - '.$warnaprod.'';
                                                    echo '</span></h3>';
                            
                                                    echo'<p>'.number_format($item['price']).' x '.$item['qty'].'</p>';											
                                                    if($item['qty']>$jumlahstokitem):
                                                        echo'<span class="noteorder">Please insert <strong>'.$jumlahstokitem.'</strong> items or less</span>';
                                                    endif;	
                                                echo'</div><!-- .scb1-1 -->';
    
                                                echo'<div class="scb23">';
                                                    echo'<div class="scb1-3">';
                                                        echo'<strong><span></span>IDR '.number_format($totalsatuan).'</strong>';
                                                    echo'</div><!-- .scb1-3 -->';
                                                echo'</div><!-- .scb23 -->';
                                            echo'</div><!-- .scb1-txt -->';
                                    echo'</div><!-- .cpt-child -->';												
                                }
                                
								if(isset($_SESSION['voucher_redeeemID'])<>''): 
									  $bonus_product = cek_statusgetdiskonmemberGift($_SESSION['voucher_redeeemID'],$idmember,$listbrandprod);
									  echo $bonus_product;
								endif;
    
								//cek voucher status--
                                if(isset($_SESSION['voucher_redeeemID'])<>''):
    								if($diskonPrice>0 or $bonus_product!=""): $status_voucher_oke = 1; else:
    								
    									//cek voucher grand total
    									$totaldiskoncari_grandtotal = getdiskonmember_grandtotal($_SESSION['voucher_redeeemID'],$grandTotal,$idmember,$listbrandprod,$listproductid);									
    									$diskonPrice = $totaldiskoncari_grandtotal;
    									if($diskonPrice>0): $status_voucher_oke = 1; endif;
    									
    								endif;	
                                endif;
								
                                //weight in kg
                                $JumlahBeratbrgProd = ceil($JumlahBeratbrg/1000);

                                //get ongkir
								if(!isset($_SESSION['kuririd_pilih'])): $_SESSION['kuririd_pilih'] = getkurirpertama(); endif;
								if(isset($_SESSION['kuririd_pilih_lainnya'])): $kurir_lainnya = $_SESSION['kuririd_pilih_lainnya']; else: $kurir_lainnya = ''; endif;
                        		
                                if(isset($_SESSION['member_shiipinid'])): $idabook = $_SESSION['member_shiipinid']; else: $idabook = 0; endif;
                                
								if($idabook=="" or $idabook < 1):
                                    $idcitymember = getidcitymember($idmember);
                                else:
                                    $idcitymember = getcodecityabook($_SESSION['member_shiipinid'],$idmember);
                                endif;
								
								$ongkirPrice = getongkirdatalist($idcitymember,$JumlahBeratbrgProd,$_SESSION['kuririd_pilih'],$grandTotal);	
                                $totalfixorder = ( ($grandTotal-$diskonPrice) + $ongkirPrice + $handling_fee);			
                        ?>
                        <br>
                        
                        <div class="oc-sub">
                            <h2 class="f-pb">Kupon Belanja</h2>
                            <div class="oc-sub-content">
                                <ul class="oc-coupon">
                            	<?php if(isset($_SESSION['voucher_redeeemID'])<>''): 
											echo'<li><span class="d-coupon"><strong>'.$_SESSION['voucher_redeeemID'].'</strong></span> <a href="" title="" class="remove_code_voucher">Remove</a></li>';  
									  endif;
								?>
                                </ul><!-- .oc-coupon -->
                                <form action="" method="post" class="general-form voucher-form" id="voucher_idform">
                                    <div class="form-group">
                                        <div class="input-wrap">
                                            <input type="text" placeholder="Masukkan kode kupon Anda di sini" name="vouchercode" class="vouchercode" />
                                        </div><!-- .input-wrap -->
                                    </div><!-- .form-group -->
                                    <div class="form-button aright">
                                       <img src="<?php echo $GLOBALS['SITE_URL'];?>images/load_page.gif" class="loadimgvou" style="display:none;" />  <input type="submit" value="SUBMIT" class="btn btn-red no-margin f-psb" name="voucheruid"  />
                                    </div><!-- .form-button -->
                                </form>	
                            </div><!-- .oc-sub-content -->
                        </div><!-- .oc-sub -->
                        <div class="kodevocher_alert">
							<?php if( isset($_SESSION['voucher_redeeemID'])<>'' and $status_voucher_oke < 1 ): echo'Maaf kode voucher ini tidak dapat digunakan untuk item product ini.'; endif;?>
							<?php if($bonus_product!=""): echo'Selamat Anda mendapatkan bonus produk gratis!'; endif;?>
                       </div>  
                    </div><!-- .cp-top -->
                    
                    
                    <div class="cp-bottom">
                    <form action="<?php echo $GLOBALS['SITE_URL'];?>saveorder" method="post" class="general-form checkout-form" id="saveorder_formid">
                        <?php $newTokenidspayment = generateFormTokenorderform('BelanjaDusdusan.comSpayment2014');?>
                        <input type="hidden" name="kodetokenid" value="<?php echo $newTokenidspayment;?>" />
                                                
                            <div class="cpb-child">
                                <h2 class="f-pb">1. Jasa Pengiriman</h2>
                                <div class="form-group">
                                    <div class="input-wrap">
                                        <div class="select-style">
										<select name="kurir_listid" class="kurir_listid">
                                    		<?php getkurirlistname($_SESSION['kuririd_pilih']);?>
                                            <option <?php if($_SESSION['kuririd_pilih']=="COD"): echo'selected="selected"'; endif;?> value="COD">COD at FixcoMart Store</option>
                                            <option <?php if($_SESSION['kuririd_pilih']=="OTHER"): echo'selected="selected"'; endif;?> value="OTHER">Masukan kurir yang Anda inginkan</option>
                               			</select>
                                        </div><!-- .select-style -->
                                    </div><!-- .input-wrap -->
                                </div><!-- .form-group -->
                               
                               <div class="form-group" <?php if($_SESSION['kuririd_pilih']=="OTHER"): echo''; else: echo'style="display:none;"'; endif;?> id="kurir_lainnya_wrapper">
                                   <label class="f-psb">Masukan Nama Kurir</label>
                                    <div class="input-wrap">
                                        <input type="text" name="kurir_lainnya" class="kurir_lainnya" value="<?php echo $kurir_lainnya;?>" />
                                    </div><!-- .input-wrap -->
                               </div><!-- .form-group -->
                                     
       
                            </div><!-- .cpb-child -->
                            
                            <div class="cpb-child" <?php if($_SESSION['kuririd_pilih']=="COD"): echo'style="display:none;"'; else: echo ''; endif;  ?>>
                                <h2 class="f-pb">2. Informasi Pengiriman</h2>
                                <div class="ecat">
                                    <div class="form-group">
                                        <label class="f-psb">Email</label>
                                        <div class="input-wrap">
                                            <input type="text" value="<?php echo $res['email'];?>" readonly="readonly" name="emailmember" />
                                        </div><!-- .input-wrap -->
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <label class="f-psb">Pesan Pengiriman (opsional)</label>
                                        <div class="input-wrap">
                                            <textarea placeholder="Your shipping notes" name="notemember" class="notemember"><?php if(isset($_SESSION['notememberlist'])): echo $_SESSION['notememberlist']; endif;?></textarea>
                                        </div><!-- .input-wrap -->
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <label class="f-psb">Alamat Pengiriman <img src="<?php echo $GLOBALS['SITE_URL'];?>images/load_page.gif" class="selectshipporder" style="display:none;" /></label>
                                    </div><!-- .form-group -->
                                </div><!-- .ecat -->
                                
                                <div class="address-accordion">
                                  <?php 
										if($idabook=="" or $idabook < 1):
											$Klass = ' opened ';
											$Klass_1 = ' style="display:block;" ';
											$Klass_2 = '  checked="checked" ';

											$BKlass = '';
											$BKlass_1 = '';											

										
									else:
											$Klass = '';
											$Klass_1 = '';
											$Klass_2 = '';	

											$BKlass = ' opened ';
											$BKlass_1 = ' style="display:block;" ';																															
										endif;
									?>
                                    
                                    <div class="aa-child">
                                        <a href="#" class="aa-toggle <?php echo $Klass;?>">Gunakan Alamat Penagihan</a>
                                        <div class="aac-child" <?php echo $Klass_1;?>>
                                            <div class="aacc">
                                                <input type="radio" id="alp1" name="alp" class="rme-radio addressmember-list" <?php echo $Klass_2;?> />
                                                <label for="alp1" class="rme-label">
                                                     <?php getmemberaddress($idmember);?>
                                                </label>
                                            </div><!-- .aacc -->
                                        </div><!-- .aac-child -->
                                    </div><!-- .aa-child -->
                                    
                                    <div class="aa-child">
                                        <a href="#" class="aa-toggle <?php echo $BKlass;?>">Pilih dari Alamat Tersimpan</a>
                                        <div class="aac-child" <?php echo $BKlass_1;?>>
                                            <?php getaddressbookdetail($idmember,$idabook);?>
                                            <a href="<?php echo $GLOBALS['SITE_URL'];?>add-new-address-checkout" class="nuke-fancied2 btn-add-address f-psb">Add Address</a>
                                        </div><!-- .aac-child -->
                                    </div><!-- .aa-child -->
                                </div><!-- .address-accordion -->
                                
           
                                <table class="ringkasan-table" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="f-pb">Order Summary <span class="total-order-wrapper" id="<?php echo $grandTotal;?>-<?php echo $ongkirPrice;?>"></span></th>
                                        </tr>
                                    </thead>
                                  
                                    <tbody>
                                    <tr>
                                        <td class="td1">Sub. Total</td>
                                        <td class="td2">Rp. <?php echo number_format($grandTotal);?></td>
                                    </tr>
                               	   
                                    <tr>
                                        <td class="td1">Handling Fee</td>
                                        <td class="td2">Rp. <?php echo number_format($handling_fee);?></td>
                                    </tr>                                    
                                    
                                    <tr>
                                        <td class="td1">Shipping Cost [ <?php echo number_format($JumlahBeratbrgProd);?> kg ]</td>
                                        <td class="td2"><span class="ongkir_span"><?php if($ongkirPrice==0): echo'Rp. 0'; else:?>Rp. <?php echo number_format($ongkirPrice); endif;?></span></td>
                                    </tr>


                                    <tr class="ada_voucherlist" <?php if($diskonPrice < 1): echo'style="display:none;"'; endif;?>>
                                         <td class="td1 diskon">Voucher (<strong id="codeUvoucher"><?php if(isset($_SESSION['voucher_redeeemID'])): echo $_SESSION['voucher_redeeemID']; endif;?></strong>)</th>
                                         <td class="td2 diskon"><strong>- Rp. <span id="totalvoucherPirce"><?php echo number_format($diskonPrice);?></span></strong></td>

                                   </tr>
                                                                       
                                    <tr>
                                        <td class="td1 total_paylist"><strong>GRAND TOTAL</strong></td>
                                        <td class="td2 total_paylist"> <strong>Rp. <span id="grandtotal_belanjaid"><?php echo number_format($totalfixorder);?></span></strong></td>
                                    </tr>
                                    </tbody>
                                    
                                </table>
                                <p style="padding-top:10px; display:block; font-size:.875em;">* harga yang tertera sudah termasuk PPN.</p>
                            </div><!-- .cpb-child -->
                            
                            
                            <div class="cpb-child last-child">
                                <h2 class="f-pb" id="paymethod"><?php if($_SESSION['kuririd_pilih']=="COD"): echo'2.'; else: echo '3.'; endif;?> Metode Pembayaran</h2>
                                <div class="payment-accordion">
                                    
                                    <div class="pa-child first-child" <?php if($_SESSION['kuririd_pilih']=="COD"): echo'style="display:none;"'; else: echo ''; endif;?>>
                                        <a href="#" class="pa-toggle opened">BANK TRANSFER</a>
                                        <div class="pac-child">
                                          	<?php bannklist();?>
                                        </div><!-- .pac-child -->
                                    </div><!-- .pa-child -->
																
                                  <?php if($idmember==6):?>
                                  <div class="pa-child">
                                       <a href="#" class="pa-toggle">BCA KlikPay</a>
                                        <div class="pac-child">
                                             <div class="pacc"> 
                                                <input type="radio" id="bcaklikPay" name="banktransfer" value="BCA KlikPay" class="rme-radio banktransfer" />
                                                    <label for="bcaklikPay" class="rme-label">
                                                        <img src="<?php echo $GLOBALS['SITE_URL'];?>images/bcalogo.png" alt="" style="width:160px;" />
                                                        <p><strong>BCA KlikPay.</strong></p>
                                                        <p>Payment will be redirected to BCA KlikPay Payment page.</p>
                                                        <p>&nbsp;</p>
                                                 </label>
                                             </div><!--.pacc-->
                                    </div><!-- .pa-child -->
                                  </div><!-- .pa-child -->
                                  <?php endif;?>
 
 
                                  <?php if($idmember==6):?>
                                  <div class="pa-child">
                                       <a href="#" class="pa-toggle">BNI eCollection</a>
                                        <div class="pac-child">
                                             <div class="pacc"> 
                                                <input type="radio" id="bniEcall" name="banktransfer" value="BNI eCollection" class="rme-radio banktransfer" />
                                                    <label for="bniEcall" class="rme-label">
                                                        <img src="<?php echo $GLOBALS['SITE_URL'];?>images/logo_bni_ecollection.png" alt="" style="width:130px;" />
                                                        <p><strong>BNI eCollection.</strong></p>
                                                        <p>&nbsp;</p>
                                                 </label>
                                             </div><!--.pacc-->
                                    </div><!-- .pa-child -->
                                  </div><!-- .pa-child -->
                                  <?php endif;?>

                                                                    
                                    <div class="pa-child" <?php if($_SESSION['kuririd_pilih']=="COD"): echo'style="display:none;"'; else: echo''; endif;?>>
                                       <a href="#" class="pa-toggle">KARTU KREDIT</a>
                                        <div class="pac-child">
                                                <input type="radio" id="veritrans_uid" name="banktransfer" value="Veritrans" class="rme-radio banktransfer" />
                                                <label for="veritrans_uid" class="rme-label">
                                                    <img src="<?php echo $GLOBALS['SITE_URL'];?>images/veritrans.png" alt="" style="width:160px;" />
                                                </label>
                                                
                                                <br /><br />
                                                <img src="<?php echo $GLOBALS['SITE_URL'];?>images/veritrans_bank.png" alt="" style="width:90%; padding-left:22px;" />
                                                <p style="padding-left:30px; font-size:13px;">[ Visa / Master Card ] Payment will be redirected to Veritrans Payment page.</p>
                                                <p>&nbsp;</p>
                                        </div><!-- .pac-child -->
                                    </div><!-- .pa-child -->

                                    <?php if($idmember==6):?>
                                    <div class="pa-child">
                                       <a href="#" class="pa-toggle">Kredivo</a>
                                        <div class="pac-child">
                                                <input type="radio" id="kredivo_uid" name="banktransfer" value="Kredivo" class="rme-radio banktransfer" />
                                                <label for="kredivo_uid" class="rme-label">
                                                    <img src="<?php echo $GLOBALS['SITE_URL'];?>images/kredivo.png" alt="" style="width:160px;" />
                                                </label>
                                        </div><!-- .pac-child -->
                                    </div><!-- .pa-child -->
                                    <?php endif;?>

                                     <div class="pa-child" <?php if($_SESSION['kuririd_pilih']=="COD"): echo ''; else: echo'style="display:none;"'; endif;?>>
                                           <a href="#" class="pa-toggle">COD</a>
                                            <div class="pac-child">
                                                 <div class="pacc"> 
                                                    <input type="radio" id="COD" name="banktransfer" value="COD" class="rme-radio banktransfer" />
                                                        <label for="COD" class="rme-label">
                                                            <img src="<?php echo $GLOBALS['SITE_URL'];?>images/codlogo.png" alt="" style="width:160px;" />
                                                            <p><strong>Fixcomart Store.</strong></p>
                                                            <p>LTC GF2 NO3.</p>
                                                            <p>&nbsp;</p>
                                                     </label>
                                                 </div><!--.pacc-->
                                        </div><!-- .pa-child -->
                                      </div><!-- .pa-child -->
                                    
                                   
                                </div><!-- .payment-accordion -->
                                
                                <div class="snk-check">
                                	<div style="clear:both; height:20px;"></div>
                                    <input type="checkbox" id="snk" name="snk" class="rme-check termchecklist" />
                                    <label for="snk" class="rme-label">Saya setuju dengan <a href="<?php echo $GLOBALS['SITE_URL'];?>syarat-dan-ketentuan" target="_blank">syarat dan ketentuan</a> yang berlaku.</label>
                                </div><!-- .snk-check -->
                                <input type="hidden" name="bank_transferid" id="bank_transferid" value="0" />
                      		 	<input type="hidden" name="termconditionid" id="termconditionid" value="0" />
                             
                            </div><!-- .cpb-child -->
                            <div class="form-button aleft">                       
                                <?php  
										if($stokalert==0): 
												echo'<input type="submit" value="" class="btn btn-red no-margin btn-checkout" name="submit" id="btn_submitorder" style="display:none;"  />';
                                		 		echo'<a href="#" class="btn btn-red no-margin btn-checkout bayar_btndd">BAYAR</a>';
										endif;		
														   
								 ?>						   
                                 <img src="<?php echo $GLOBALS['SITE_URL'];?>images/load_page.gif" class="saveorder_load" style="display:none;" />    
								 <div style="clear:both; height:1px;"></div>
                            </div><!-- .form-button -->
                            
                         	 <?php
							        if($stokalert==1):
									 echo'<div class="error-cart">';
										 echo'<span>Maaf, stok item untuk produk yang ditandai kurang dari jumlah pesananan anda.<br />'; 
                                         echo'Silahkan perbarui jumlah pesanan anda sesuai jumlah stok item. <a href="'.$GLOBALS['SITE_URL'].'shopping-cart" title=""><u>Troli Belanja</u></a></span>'; 
									 echo'</div>';						
									endif;					   
					   
					   		?>
                       
                        </form><!-- .checkout-form -->
                    </div><!-- .cp-bottom -->
                </div><!-- .checkout-page -->
                
                <!-- SHOPPING CART COPY END HERE -->
                
            </div><!-- .template-wrap -->
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section -->

<?php include("foot.php"); ?>
<?php include("footer.php"); ?>


<script>
$(document).ready(function() {
	$("#menu_list4").addClass("active");		
});
</script>

</body>
</html>