<?php

if (!defined('ABSPATH')) {
	exit;
}

class WC_Pre_Orders_Backend {

	public function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
		$this->includes();
	}

	public function enqueue_scripts() {
		wp_enqueue_style('pre-orders-css', plugin_dir_url(__FILE__) . '../../css/yopo-wc-pre-orders-backend.css', '1.0', true);
	}

	public function includes() {
		include_once plugin_dir_path(__FILE__) . '../backend/yopo-wc-pre-orders-product-stock-status.php';
		include_once plugin_dir_path(__FILE__) . '../backend/yopo-wc-pre-orders-products.php';
		include_once plugin_dir_path(__FILE__) . '../backend/yopo-wc-pre-orders-edit-order.php';
		include_once plugin_dir_path(__FILE__) . '../backend/actions/yopo-wc-pre-orders-auto-update-to-instock.php';
		include_once plugin_dir_path(__FILE__) . '../backend/emails/yopo-wc-pre-orders-customer-email-new-order.php';
	}
}

new WC_Pre_Orders_Backend();
