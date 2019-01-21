<?php
/**
 * Admin View: Notice - Currency not supported.
 *
 * @package WooCommerce_SmartCash/Admin/Notices
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="error inline">
	<p><strong><?php _e( 'Smartcash Payments Disabled', 'wcscp' ); ?></strong>: <?php printf( __( 'Currency <code>%s</code> is not supported. Works only with Brazilian Real.', 'wcscp' ), get_woocommerce_currency() ); ?>
	</p>
</div>
