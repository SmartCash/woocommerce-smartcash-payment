<?php
/**
 * Payment Page Details
 *
 * @package WooCommerce_SmartCash/Templates
 *
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }
?>
<script>
  setTimeout(function(){
   window.location.reload(1);
 }, 10000);
</script>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-7 col-11 box p-4 mt-5 rounded">
      <div class="row align-items-center">
        <div class="col-md-4 text-center">
          <div id="qrcode" class="mb-4 mb-md-2 px-5 px-md-3">

          </div>
          <script type="text/javascript">
            new QRCode(document.getElementById("qrcode"), { text: "<?php echo get_post_meta($order->id, "kamoney_address", true); ?>", width: 151, height: 151} );
          </script>
        </div>
        <div class="col-md-8" style="border-left: 1px solid #EEE;">
          <h3 class="text-size-2 mb-0"><?php printf( __( 'Payment details for <b>%s</b>', 'wcscp' ), get_bloginfo("name") ); ?></h3>
          <p class="color-gray"><i class="fa fa-spinner fa-pulse fa-fw"></i> <?php _e("We are waiting for your transaction.", "wcscp"); ?></p>
          <div class="mb-2 mt-3">
            <span class="text-muted text-size-1"><?php _e("AMOUNT", "wcscp"); ?></span><br>
            <div class="text-size-2" style="line-height:90%;"><b><?php echo floatval(get_post_meta($order->id, "kamoney_amount", true)); ?> <?php echo get_post_meta($order->id, "kamoney_currency", true); ?></b><br /><span class="text-size-1"><?php echo get_woocommerce_currency_symbol()." ".$order->get_total(); ?></span></div>
          </div>
          <div class="mb-2">
            <span class="text-muted text-size-1"><?php _e("ADDRESS", "wcscp"); ?></span>
            <div class="text-size-2"><b><?php echo get_post_meta($order->id, "kamoney_address", true); ?></b> <a href="#" id="copy-clipboard" class="tooltip-btn" data-clipboard-text="<?php echo get_post_meta($order->id, "kamoney_address", true); ?>" data-toggle="tooltip" data-title="<?php _e("Copy", "wcscp"); ?>" data-title-toggle="<?php _e("Copied", "wcscp"); ?>"><i class="far fa-copy"></i><span class="sr-only">Copy</span></a></div>
            <script type="text/javascript">
              var btn = document.getElementById('copy-clipboard');
              var clipboard = new ClipboardJS(btn);
              $(btn).click(function(e) {
                e.preventDefault();
              });
              clipboard.on('success', function(e) {
                  $(btn).tooltip("hide");
                  $(btn).attr("data-original-title", $(btn).attr("data-title-toggle"));
                  $(btn).tooltip("show");
                  $(btn).tooltip("hide");
                  $(btn).attr("data-original-title", $(btn).attr("data-title"));
              });
            </script>
          </div>
          <div>
            <span class="text-muted text-size-1"><?php _e("EXPIRES IN", "wcscp"); ?></span>
            <div class="text-size-2"><b id="time-to-expire" data-expire-date="<?php echo wcscp_payment_expire_date($order); ?>"><?php echo wcscp_payment_expire_diff($order); ?></b></div>
            <script type="text/javascript">
              var expire_date = $("#time-to-expire").attr("data-expire-date");
              $('#time-to-expire').countdown(expire_date, function(event) {
                $(this).html(event.strftime('%M:%S'));
              });
            </script>
          </div>

        </div>
      </div>
      <!--
      <div class="timebar">
        <span class="timebar-progress"></span>
      </div>
      -->
    </div>
  </div>
  <p class="text-center mt-5 mb-5 color-gray"><?php _e("Problems with the payment?", "wcscp"); ?><br /><a href="<?php echo $order->get_cancel_order_url() ;?>"><?php _e( 'Cancel order &amp; restore cart', 'wcscp' ); ?></a></p>
</div>
