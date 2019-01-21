<?php

/**
 * WooCommerce SmartCash Helper Functions
 *
 * @package WooCommerce_SmartCash/Helpers
 *
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }

function wcscp_fix_date_timezone($date) {
  $date = new DateTime($date, new DateTimeZone('America/Sao_Paulo'));
  $date->setTimezone(new DateTimeZone(date("e")));
  return $date->format('Y-m-d H:i:s');
}

/**
 * Return payment expire date
 *
 * @param  WC_Order $order    Order object.
 * @return boolean
 */
function wcscp_payment_expire_date($order) {
  $payment_created_date = wcscp_fix_date_timezone(get_post_meta($order->id, "kamoney_date", true));
  $payment_expire_date = strtotime("+15 minutes", strtotime($payment_created_date));
  return date('Y-m-d H:i:s', $payment_expire_date);
}

/**
 * Return payment expire date
 *
 * @param  WC_Order $order    Order object.
 * @return boolean
 */
function wcscp_payment_expire_diff($order) {
  $payment_expire_date =  date("U", strtotime(wcscp_payment_expire_date($order)));
  $now = date("U", time());
  $diff = $payment_expire_date-$now;
  return date("i:s", $diff);
}


/**
 * Check if the payment is expired
 *
 * @param  WC_Order $order    Order object.
 * @return boolean
 */
function wcscp_is_payment_expired($order) {
  $now = date("U", time());
  $payment_expire_date =  date("U", strtotime(wcscp_payment_expire_date($order)));
  if ($now>$payment_expire_date) {
    return true;
  } else {
    return false;
  }
}

/**
 * Return the SmartCash Payment URL
 *
 * @param  WC_Order $order    Order object.
 * @return string
 */
function wcscp_smartcash_payment_url($order) {
  if (isset($order->id)) {
    if (get_option("permalink_structure") == '') {
      return get_bloginfo("home")."?smartcash_payment=".$order->id."&smartcash_payment_order_key=".$order->get_order_key();
    } else {
      return get_bloginfo("home")."/smartcash-payment/".$order->id."/".$order->get_order_key();
    }
  } else {
    return false;
  }
}
