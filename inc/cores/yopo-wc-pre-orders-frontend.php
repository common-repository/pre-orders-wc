<?php

if (!defined('ABSPATH')) {
	exit;
}

class WC_Pre_Orders_Frontend {

	public function __construct() {
		add_action('wp_enqueue_scripts', [$this, 'frontend_enqueue_scripts']);
		$this->includes();
	}

	public function frontend_enqueue_scripts() {
		if (is_cart() || is_checkout()) {
			wp_enqueue_style('pre-orders-frontend-css', plugin_dir_url(__FILE__) . '../../css/yopo-wc-pre-orders-frontend.css', array(), '1.0');
		}
	}

	public function includes() {
		include_once plugin_dir_path(__FILE__) . '../frontend/yopo-wc-pre-orders-single-product.php';
		include_once plugin_dir_path(__FILE__) . '../frontend/yopo-wc-pre-orders-product-list.php';
		include_once plugin_dir_path(__FILE__) . '../frontend/yopo-wc-pre-orders-cart.php';
	}
}

new WC_Pre_Orders_Frontend();
