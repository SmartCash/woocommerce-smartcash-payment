<?php
/**
 * Payment Page Failed
 *
 * @package WooCommerce_SmartCash/Templates
 *
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }
?>
<div class="container text-center mt-4">
  <h1><b><?php _e("Ops, there is an error here", "wcscp"); ?></b></h1>
  <p class="text-size-2 mb-5 color-gray"><?php _e("The order that you are looking to pay doesn't exist.", "wcscp"); ?></p>
  <p class="text-size-2 mb-5 color-gray"><?php _e("If you think that it is an error, please contact support.", "wcscp"); ?></p>
  <a href="<?php bloginfo("home"); ?>" class="btn btn-primary"><?php _e("Back to store", "wcscp"); ?></a>
</div>
