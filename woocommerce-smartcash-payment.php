<?php
/*
Plugin Name: WooCommerce SmartCash Payment
Plugin URI: http://smartcash.cc
Description: WooCommerce plugin that enables payments using the cryptocurrency SmartCash. This plugin requires a Kamoney (http://kamoney.com.br) account.
Version: 1.0
Text Domain: wcscp
Domain Path: /languages
*/

// Using as reference: https://github.com/claudiosanches/woocommerce-pagseguro/

defined( 'ABSPATH' ) || exit;

// Plugin constants.
define( 'WC_SMARTCASH_PLUGIN_FILE', __FILE__ );
define( 'WC_SMARTCASH_CURRENCY_CODE', "SMART" );

if ( ! class_exists( 'WC_SmartCash' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-wc-smartcash.php';
	add_action( 'plugins_loaded', array( 'WC_SmartCash', 'init' ) );
}
