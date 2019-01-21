<?php
/**
 * WooCommerce SmartCash main class
 *
 * @package WooCommerce_SmartCash
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }


/**
 * WooCommerce bootstrap class.
 */
class WC_SmartCash {

	/**
	 * Initialize the plugin public actions.
	 */
	public static function init() {
		// Load plugin text domain.
		add_action( 'init', array( __CLASS__, 'load_plugin_textdomain' ) );

		// Checks with WooCommerce is installed.
		if ( class_exists( 'WC_Payment_Gateway' ) ) {
			self::includes();

			add_filter( 'woocommerce_payment_gateways', array( __CLASS__, 'add_gateway' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( WC_SMARTCASH_PLUGIN_FILE ), array( __CLASS__, 'plugin_action_links' ) );
      add_action( 'template_redirect', array( __CLASS__, 'payment_page' ) );

		} else {
			add_action( 'admin_notices', array( __CLASS__, 'woocommerce_missing_notice' ) );
		}
	}

  /**
	 * Display Payment Page
	 */
  public static function payment_page() {
    $smartcash_payment = intval( get_query_var( 'smartcash_payment' ) );
    if ( $smartcash_payment ) {
        include self::get_templates_path().'payment-page.php';
        die;
    }
  }

	/**
	 * Get templates path.
	 *
	 * @return string
	 */
	public static function get_templates_path() {
		return plugin_dir_path( WC_SMARTCASH_PLUGIN_FILE ) . 'templates/';
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public static function load_plugin_textdomain() {
		load_plugin_textdomain( 'wcscp', false, dirname( plugin_basename( WC_SMARTCASH_PLUGIN_FILE ) ) . '/languages/' );
	}

	/**
	 * Action links.
	 *
	 * @param array $links Action links.
	 *
	 * @return array
	 */
	public static function plugin_action_links( $links ) {
		$plugin_links   = array();
		$plugin_links[] = '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=smartcash_payment' ) ) . '">' . __( 'Settings', 'wcscp' ) . '</a>';

		return array_merge( $plugin_links, $links );
	}

	/**
	 * Includes.
	 */
	private static function includes() {
    include_once plugin_dir_path( WC_SMARTCASH_PLUGIN_FILE ) . 'routes.php';
		include_once dirname( __FILE__ ) . '/class-kamoney.php';
		include_once dirname( __FILE__ ) . '/helpers.php';
		include_once dirname( __FILE__ ) . '/class-wc-smartcash-gateway.php';
	}

	/**
	 * Add the gateway to WooCommerce.
	 *
	 * @param  array $methods WooCommerce payment methods.
	 *
	 * @return array          Payment methods with PagSeguro.
	 */
	public static function add_gateway( $methods ) {
		$methods[] = 'WC_SmartCash_Gateway';

		return $methods;
	}

	/**
	 * WooCommerce missing notice.
	 */
	public static function woocommerce_missing_notice() {
		include dirname( __FILE__ ) . '/admin/views/html-notice-missing-woocommerce.php';
	}
}
