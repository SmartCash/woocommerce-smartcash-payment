<?php
/**
 * Payment Page Expired
 *
 * @package WooCommerce_SmartCash/Templates
 *
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }
?>
<div class="container text-center mt-4">
  <h1><b><?php _e("Sorry, the payment expired", "wcscp"); ?></b></h1>
  <p class="text-size-2 mb-5 color-gray"><?php _e("If you have already made the payment, your order will be confirmed soon.", "wcscp"); ?></p>
  <p class="text-size-2 mb-5 color-gray"><?php _e("If not, please, go back to store and repeat your order.", "wcscp"); ?></p>
  <a href="<?php echo bloginfo("home"); ?>" class="btn btn-primary"><?php _e( 'Back to store', 'wcscp' ); ?></a>
</div>
