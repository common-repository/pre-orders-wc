<?php
/**
 * Plugin Name: WooCommerce Pre-Orders
 * Plugin URI: https://wordpress.org/plugins/pre-orders-wc/
 * Description: Just another product stock status for your WooCommerce store.
 * Version: 1.0.2
 * Author: YoOhw.com
 * Author URI: https://yoohw.com
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Text Domain: pre-orders-wc
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Requires Plugins: woocommerce
 */

if (!defined('ABSPATH')) {
	exit;
}

class WooCommerce_Pre_Orders {
	
	public function __construct() {
		add_action('plugins_loaded', array($this, 'load_textdomain'));
		$this->includes();
	}

	public function load_textdomain() {
		load_plugin_textdomain('pre-orders-wc', false, basename(dirname(__FILE__)) . '/languages/');
	}

	public function includes() {
		include_once plugin_dir_path(__FILE__) . 'inc/cores/yopo-wc-pre-orders-notices.php';
		include_once plugin_dir_path(__FILE__) . 'inc/cores/yopo-wc-pre-orders-backend.php';
		include_once plugin_dir_path(__FILE__) . 'inc/cores/yopo-wc-pre-orders-frontend.php';
	}
}

// Initialize the plugin
new WooCommerce_Pre_Orders();
