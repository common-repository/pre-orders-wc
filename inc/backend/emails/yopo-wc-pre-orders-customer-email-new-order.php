<?php

if (!defined('ABSPATH')) {
	exit;
}

class WC_Pre_Orders_Email_Customer_New_Order {

	public function __construct() {
		// Hook into WooCommerce email item formatting to modify the product title in new order emails
		add_filter('woocommerce_order_item_name', array($this, 'display_preorder_text_and_date_in_email'), 10, 2);
	}

	// Add 'Pre-order item' and available date under the product title for 'onpreorder' products
	public function display_preorder_text_and_date_in_email($item_name, $item) {
		// Check if the order item is a product
		if ($item instanceof WC_Order_Item_Product) {
			$product = $item->get_product();

			// Check if the product exists and has the 'onpreorder' stock status
			if ($product && $product->get_stock_status() === 'onpreorder') {
				// Append 'Pre-order item' below the product title
				$item_name .= '<br><small style="color: #a3a3a3; font-weight: 600;">' . esc_html__('Pre-order item', 'pre-orders-wc') . '</small>';

				// Retrieve the '_pre_order_available_date' meta and format it
				$pre_order_available_date = get_post_meta($product->get_id(), '_pre_order_available_date', true);

				if (!empty($pre_order_available_date)) {
					// Convert the date to the site timezone for display
					$date = new DateTime($pre_order_available_date, new DateTimeZone('UTC'));
					$date->setTimezone(new DateTimeZone(wp_timezone_string()));  // Site timezone setting

					// Append the available date under 'Pre-order item' using the site's date and time format
					$item_name .= '<br><small style="color: #a3a3a3;">'. esc_html__('Available on: ', 'pre-orders-wc') . esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $date->getTimestamp())) . '</small>';
				}
			}
		}

		return $item_name;
	}
}

new WC_Pre_Orders_Email_Customer_New_Order();
