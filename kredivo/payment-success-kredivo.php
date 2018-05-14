<?php include("header.php"); 

    $tr_status  = $_GET['tr_status'];

    $orderid    = $_GET['order_id'];

    if($tr_status == 'settlement'):

        $que        = $db->query("SELECT *,DATE_FORMAT(`date`,'%W, %d %M %Y') as tgl FROM `order_header` WHERE `id`='$orderid'");

        $data       = $que->fetch_assoc();

        $totalorder = (($data['orderamount']+$data['shippingcost']+$data['kode_unik']+$data['handling_fee'])-$data['discountamount']);

        $message    = 'Terima Kasih Telah Berbelanja di Fixcomart !';

    else:

        //Transaction status check

        if($tr_status == 'pending') {
            
            $title = 'di Pending';

        } elseif ($tr_status == 'deny') {
                
            $title = 'di Tolak';

        } elseif ($tr_status == 'cancel') {
            
            $title = 'di Batalkan';

        } elseif ($tr_status == 'expire') {
            
            $title = 'Telah Expire';

        }

        $message    = 'Mohon Maaf, Proses Transaksi Anda '.$title;

        endif;
    

?>

<?php include("head.php"); ?>



<section id="breadcrumbs">

    <div class="container">

        <div class="row">

            <ul class="breadcrumbs">

                <li class="first"><a href="<?php echo $GLOBALS['SITE_URL'];?>index" class="bc-home">Home</a></li>

                <li>Shopping Cart</li>

                <li>Checkout</li>

                <li class="f-pb">Order Complete</li>

            </ul><!-- .breadcrumbs -->

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- #breadcrumbs -->



<section id="template-page" class="section one-column">

    <div class="container">

        <div class="row">

            <div class="template-wrap">

                <div class="max-700 centered">

                    <h1 class="f-pb"><?php echo $message; ?></h1>

                </div><!-- .max-700 -->

            <?php if($tr_status == 'settlement'): ?>
                
                <table cellspacing="0" cellpadding="0" class="done-table">

                    <tr>

                        <td><strong>Tanggal</strong></td>

                        <td class="td">:</td>

                        <td><?php echo $data['tgl'];?></td>

                    </tr>

                    <tr>

                        <td><strong>Total Order</strong></td>

                        <td class="td">:</td>

                        <td>Rp <?php echo number_format($totalorder);?></td>

                    </tr>

                    <tr>

                        <td><strong>Nomor Order</strong></td>

                        <td class="td">:</td>

                        <td>#<?php echo sprintf('%06d',$data['id']);?></td>

                    </tr>

                    <tr>

                        <td><strong>Kurir</strong></td>

                        <td class="td">:</td>

                        <td><?php echo $data['kurir'];?></td>

                    </tr>                     

                    <tr>

                        <td><strong>Nama Penerima</strong></td>

                        <td class="td">:</td>

                        <td><?php echo $data['nama_penerima'];?></td>

                    </tr>

                    <tr>

                        <td><strong>Alamat Pengiriman</strong></td>

                        <td class="td">:</td>

                        <td>

                            <?php

                                    echo $data['address_penerima'].'<br />';

                                    echo $data['kota_penerima'].', '. $data['kabupaten_penerima'].'<br />';

                                    echo $data['provinsi_penerima'].' - '.$data['country_penerima'].' '.$data['kodepos'];               

                            ?>                          

                        </td>

                    </tr>

                    <tr>

                        <td><strong>Telepon</strong></td>

                        <td class="td">:</td>

                        <td><?php echo $data['phone_penerima'];?></td>

                    </tr>



                    <tr>

                        <td><strong>Pesan Pengiriman</strong></td>

                        <td class="td">:</td>

                        <td><?php if($data['note']==""): echo'-'; else: echo $data['note']; endif;?></td>

                    </tr>

                </table>

            <?php endif; ?>

                <div class="done-button centered">

                    <a href="<?php echo $GLOBALS['SITE_URL'];?>product" class="btn btn-red f-mr">LANJUT BELANJA</a>

                </div><!-- .done-button -->

            </div><!-- .template-wrap -->

        </div><!-- .row -->

    </div><!-- .container -->

</section><!-- .section -->



<?php include("foot.php"); ?>

<?php include("footer.php"); ?>





</body>

</html>