<?php
/**
 * Payment Page
 *
 * @package WooCommerce_SmartCash/Templates
 *
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i|Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/payment-page.css', plugin_dir_path( __FILE__ )); ?>">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src="<?php echo plugins_url( 'assets/js/qrcode.min.js', plugin_dir_path( __FILE__ )); ?>"></script>
    <script src="<?php echo plugins_url( 'assets/js/jquery.countdown.min.js', plugin_dir_path( __FILE__ )); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <title><?php _e("Pay with SmartCash", "wcscp"); ?> - <?php bloginfo("name"); ?></title>
  </head>
  <body>
    <div class="bg-smartcash py-2">
      <h1 class="text-center text-size-3 m-0"><img src="<?php echo plugins_url( 'assets/images/smartcash-icon.png', plugin_dir_path( __FILE__ )); ?>"> <?php _e("Pay with <b>SmartCash</b>", "wcscp"); ?></h1>
    </div>
    <?php

      $order_id = intval( get_query_var( 'smartcash_payment' ) );
      $order_key = get_query_var( 'smartcash_payment_order_key' );
      $order = wc_get_order($order_id);
      if (false !== $order && $order->get_status() !== "failed" && $order->get_order_key() == $order_key) {
        if ($order->is_paid()) {
          include_once self::get_templates_path()."payment-page-complete.php";
        } else if (!wcscp_is_payment_expired($order)) {
          include_once self::get_templates_path()."payment-page-details.php";
        } else {
          $order->update_status( 'cancelled' );
          $order->add_order_note( __( 'Payment expired.', 'wcscp' ) );
          include_once self::get_templates_path()."payment-page-expired.php";
        }

      } else {
        include_once self::get_templates_path()."payment-page-failed.php";
      }
    ?>
    <script>
      $(function () {
        $('body').tooltip({
            selector: '.tooltip-btn'
        });
      });
    </script>
  </body>
</html>
