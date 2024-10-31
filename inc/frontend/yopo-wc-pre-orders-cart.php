<?php

if (!defined('ABSPATH')) {
	exit;
}

class WC_Pre_Orders_Cart_Checkout {

	public function __construct() {
		// Hook into the 'woocommerce_cart_item_price' filter to display the pre-order availability date under the product price in the cart
		add_filter('woocommerce_cart_item_name', array($this, 'display_pre_order_date_in_cart'), 10, 3);
	}

	/**
	 * Display the pre-order availability date under the product price in the cart.
	 *
	 * @param string $price The current product price.
	 * @param array $cart_item The cart item array.
	 * @param string $cart_item_key The cart item key.
	 * @return string Modified product price with pre-order date.
	 */
	public function display_pre_order_date_in_cart($price, $cart_item, $cart_item_key) {
		$product = $cart_item['data'];
		if ($product->get_stock_status() === 'onpreorder') {
			$pre_order_date = get_post_meta($product->get_id(), '_pre_order_available_date', true);
			if ($pre_order_date) {
				/* translators: %s: the available date of the pre-order product */
				$pre_order_message = sprintf('<p class="pre-order-availability-date-small">' . __('Available on <strong>%s</strong>.', 'pre-orders-wc') . '</p>', date_i18n(get_option('date_format'), strtotime($pre_order_date)));
				$price .= $pre_order_message;
			}
		}
		return $price;
	}
}

new WC_Pre_Orders_Cart_Checkout();
