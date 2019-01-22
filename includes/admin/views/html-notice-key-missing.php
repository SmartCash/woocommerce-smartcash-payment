<?php
/**
 * Admin View: Notice - Secret Key missing
 *
 * @package WooCommerce_SmartCash/Admin/Notices
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="error inline">
	<p>
		<strong><?php _e( 'SmartCash Payments Disabled', 'wcscp' ); ?></strong><br />
		<?php _e( 'You should inform a valid Kamoney Public and Secret Key.', 'wcscp' ); ?><br />
		- <?php echo $test["error"]; ?>
	</p>
</div>
