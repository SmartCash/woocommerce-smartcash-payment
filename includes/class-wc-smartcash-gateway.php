<?php
/**
 * WooCommerce SmartCash Gateway class
 *
 * @package WooCommerce_SmartCash/Classes/Gateway
 * @version 1.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly.
 }

class WC_SmartCash_Gateway extends WC_Payment_Gateway {

	function __construct() {
		$this->id = "smartcash_payment";
    $this->icon = plugins_url( 'assets/images/smartcash.png', plugin_dir_path( __FILE__ ));
		$this->method_title = __( "SmartCash", 'wcscp' );
		$this->method_description = __( "Start accepting SmartCash cryptocurrency in your WooCommerce store.<br /><b>This plugin requires a merchant account in the service <a href='http://kamoney.com.br' target='_blank'>Kamoney</a>.</b>", 'wcscp' );
		$this->title = __( "SmartCash", 'wcscp' );
    $this->has_fields = true;


		// Load form fields
		$this->init_form_fields();

		// Load settings
		$this->init_settings();

		// Turn these settings into variables we can use
		foreach ( $this->settings as $setting_key => $value ) {
			$this->$setting_key = $value;
		}

    // Kamoney API
    $this->api = new Kamoney($this->kamoney_public_key, $this->kamoney_secret_key);

    // Active logs.
		if ( 'yes' === $this->debug ) {
			if ( function_exists( 'wc_get_logger' ) ) {
				$this->log = wc_get_logger();
			} else {
				$this->log = new WC_Logger();
			}
		}

    // Main actions.
		add_action( 'woocommerce_api_wc_gateway_smartcash_payment', array( $this, 'ipn_handler' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	}

  /**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'		=> __( 'Enable / Disable', 'wcscp' ),
				'label'		=> __( 'Enable this payment gateway', 'wcscp' ),
				'type'		=> 'checkbox',
				'default'	=> 'no',
			),
			'title' => array(
				'title'		=> __( 'Title', 'wcscp' ),
				'type'		=> 'text',
				'desc_tip'	=> __( 'Payment title of checkout process.', 'wcscp' ),
				'default'	=> __( 'Pay with SmartCash', 'wcscp' ),
			),
			'description' => array(
				'title'		=> __( 'Description', 'wcscp' ),
				'type'		=> 'textarea',
				'desc_tip'	=> __( 'Payment title of checkout process.', 'wcscp' ),
				'default'	=> __( 'Pay with SmartCash cryptocurrency', 'wcscp' ),
				'css'		=> 'max-width:450px;'
			),
			'kamoney_public_key' => array(
				'title'		=> __( 'Kamoney Public Key', 'wcscp' ),
				'type'		=> 'text',
				'desc_tip'	=> __( 'This is the Public Key provided by Kamoney.com.br when you signed up for a merchant account.', 'wcscp' ),
			),
			'kamoney_secret_key' => array(
				'title'		=> __( 'Kamoney Secret Key', 'wcscp' ),
				'type'		=> 'password',
				'desc_tip'	=> __( 'This is the Secret Key provided by Kamoney.com.br when you signed up for a merchant account.', 'wcscp' ),
			),
      'debug' => array(
				'title'		=> __( 'Debug mode', 'wcscp' ),
				'label'		=> __( 'Enable logging', 'wcscp' ),
				'type'		=> 'checkbox',
				'default'	=> 'no',
			),
		);
	}

  /**
	 * Admin page.
	 */
	public function admin_options() {
		include dirname( __FILE__ ) . '/admin/views/html-admin-page.php';
	}


  /**
	 * Returns a bool that indicates if currency is amongst the supported ones.
	 *
	 * @return bool
	 */
	public function using_supported_currency() {
		return 'BRL' === get_woocommerce_currency();
	}

  /**
	 * Returns a value indicating the the Gateway is available or not. It's called
	 * automatically by WooCommerce before allowing customers to use the gateway
	 * for payment.
	 *
	 * @return bool
	 */
	public function is_available() {
		// Test if is valid for use.
    $test = $this->api->statusServiceOrder();

		$available = 'yes' === $this->get_option( 'enabled' ) && !array_key_exists("error", $test) && false !== $test && $this->using_supported_currency();
		return $available;
	}

	// Response handled for payment gateway
	public function process_payment( $order_id ) {
		global $woocommerce;
    $order = new WC_Order( $order_id );

    $result = 'fail';
    $url = '';

    $data = array(
      "amount" => $this->get_order_total(),
      "currency" => WC_SMARTCASH_CURRENCY_CODE,
      "order_id" => $order_id,
			"callback" => WC()->api_request_url( 'wc_gateway_smartcash_payment' )
    );
    $sale = $this->api->salesCreate($data);

		if ( 'yes' === $this->debug ) {
			$this->log->add( $this->id, $sale["error"]);
		}

    if (($sale !== false) && (!array_key_exists("error", $sale))) {
	    // Mark as on-hold (we're awaiting the transaction)
	    $order->update_status('on-hold');

	    // Remove cart
	    $woocommerce->cart->empty_cart();

	    // Update order meta
	    foreach ($sale as $key=>$value) {
	      update_post_meta($order->id, "kamoney_".$key, $value);
	    }

	    // Add note informing Kamoney Payment ID
	    $order->add_order_note(__('Kamoney Sale ID: ', 'wcscp' ).$sale["id"]);

	    $result = "success";
	    $url = wcscp_smartcash_payment_url($order);

    } else {

      // Kamoney Sale Creating Error
      $error_message = $sale["erorr"];

    }

    if ($result == "fail") {
      // Update order status
      $order->update_status('failed');
      $order->add_order_note(__('Failed to connect to Kamoney: ', 'wcscp' ).$error_message);

      // Report error in the checkout page
      wc_add_notice( __('Payment error: service not available. Please, try again later.', 'wcscp'), 'error' ); return;
    }

    return array(
        'result' => $result,
        'redirect' => $url
    );

	}

	// Validate fields
	public function validate_fields() {
		return true;
	}

	/**
	 * IPN handler.
	 */
	public function ipn_handler() {
		@ob_clean();
		if (array_key_exists("order_id", $_POST) && array_key_exists("id", $_POST)) {
			if ( 'yes' === $this->debug ) {
				$this->log->add( $this->id, "Callback received: ".print_r($_POST,true));
			}
			$data = $_POST;
			$order = wc_get_order($data["order_id"]);
			if (false!==$order && get_post_meta($order->id, "kamoney_id", true) == $data["id"]) {
				header( 'HTTP/1.1 200 OK' );
				if ( 'yes' === $this->debug ) {
					$this->log->add( $this->id, "Order $order->id exists and match");
				}
				$confirmed_status = array("WAITING_CONFIRMS", "UNCONFIRMED_APPROVED", "CONFIRMED");
				if (in_array($data["status_code"], $confirmed_status) && $order->get_status() == "on-hold") {
					if ( 'yes' === $this->debug ) {
						$this->log->add( $this->id, "Order $order->id has a confirmed status");
					}
					wc_reduce_stock_levels( $order_id );
					$order->update_status( 'processing' );
				}
				switch ($data["status_code"]) {
					case "WAITING_CONFIRMS":
							$order->add_order_note( __( 'Transaction identified. Confirming.', 'wcscp' ) );
						break;
					case "UNCONFIRMED_APPROVED":
							$order->add_order_note( __( 'Approved. Confirming payment.', 'wcscp' ) );
						break;
					case "UNCONFIRMED_PARTIAL":


						break;
					case "UNCONFIRMED_REOPENED":


						break;
					case "CONFIRMED_PARTIAL":


						break;
					case "CONFIRMED":
							$order->add_order_note( __( 'Fully confirmed.', 'wcscp' ) );
							die("*ok*");
						break;
				}
			} else {
				if ( 'yes' === $this->debug ) {
					$this->log->add( $this->id, "Invalid Kamoney Order ID");
				}
				wp_die(esc_html__( 'Invalid Kamoney Order ID', 'wcscp' ));
			}
		} else {
			if ( 'yes' === $this->debug ) {
				$this->log->add( $this->id, "Invalid Kamoney POST");
			}
			wp_die(esc_html__( 'Invalid Kamoney POST', 'wcscp' ));
		}
	}



}
