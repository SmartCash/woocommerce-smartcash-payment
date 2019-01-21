<?php
/**
 * Routes
 *
 * @package WooCommerce_SmartCash/Routes
 *
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }


add_filter( 'rewrite_rules_array','wcscp_insert_rewrite_rules' );
add_filter( 'query_vars','wcscp_insert_query_vars' );
add_action( 'wp_loaded','wcscp_flush_rules' );

// flush_rules() if our rules are not yet included
function wcscp_flush_rules(){
	$rules = get_option( 'rewrite_rules' );

	if ( ! isset( $rules['smartcash-payment/([^/]+)/([^/]+)/?$'] ) ) {
		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();
	}
}

// Adding a new rule
function wcscp_insert_rewrite_rules( $rules )
{
	$newrules = array();
	$newrules['smartcash-payment/([^/]+)/([^/]+)/?$'] = 'index.php?smartcash_payment=$matches[1]&smartcash_payment_order_key=$matches[2]';
	return $newrules + $rules;
}

// Adding the id var so that WP recognizes it
function wcscp_insert_query_vars( $vars )
{
    array_push($vars, 'smartcash_payment', 'smartcash_payment_order_key');
    return $vars;
}
