<?php
/**
 * Payment Page Complete
 *
 * @package WooCommerce_SmartCash/Templates
 *
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }
?>
<div class="container text-center mt-4">
  <h1><b><?php _e("Thank you for your payment!", "wcscp"); ?></b></h1>
  <p class="text-size-2 color-gray">
    <?php _e("Your payment is now complete.", "wcscp"); ?><br>
    <?php _e("We are redirecting you to your purchase receipt.", "wcscp"); ?></p>
  <a href="<?php echo $order->get_checkout_order_received_url(); ?>" class="btn btn-primary"><?php _e("Check my receipt", "wcscp"); ?></a>
</div>
