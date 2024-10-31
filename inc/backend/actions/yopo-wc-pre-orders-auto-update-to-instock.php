<?php

if (!defined('ABSPATH')) {
	exit;
}

class WC_Pre_Orders_Auto_Update_To_In_Stock {

	public function __construct() {
		// Hook into product save to schedule the event (works for simple products)
		add_action('save_post_product', array($this, 'schedule_pre_order_stock_update'));

		// Hook into variation save to schedule the event (works for variations)
		add_action('woocommerce_save_product_variation', array($this, 'schedule_pre_order_stock_update_variation'), 10, 2);

		// Hook for the actual event to change stock status
		add_action('wc_pre_orders_update_stock_status', array($this, 'update_pre_order_stock_status'), 10, 1);
	}

	// Schedule an event for when the product's available date arrives (for both simple products and variations)
	public function schedule_pre_order_stock_update($post_id) {
		$this->schedule_event_for_product($post_id);
	}

	// Schedule an event for variations
	public function schedule_pre_order_stock_update_variation($variation_id, $i) {
		$this->schedule_event_for_product($variation_id);
	}

	// Common function to schedule events for both simple products and variations
	private function schedule_event_for_product($product_id) {
		$product = wc_get_product($product_id);

		if (!$product) {
			return;
		}

		// Ensure stock status is 'onpreorder'
		if ($product->get_stock_status() !== 'onpreorder') {
			return;
		}

		// Get the available date with time (YYYY-MM-DD HH:MM)
		$pre_order_available_date = $product->get_meta('_pre_order_available_date');

		// If no date is set, return
		if (empty($pre_order_available_date)) {
			return;
		}

		// Convert the available date to the site's local timezone
		$datetime = new DateTime($pre_order_available_date, new DateTimeZone(wp_timezone_string()));
		$timestamp_site = $datetime->getTimestamp();  // Get timestamp based on site's timezone

		// Get the current time in the site's timezone
		$current_time = new DateTime('now', new DateTimeZone(wp_timezone_string()));
		$current_timestamp = $current_time->getTimestamp();

		// Unschedule any previously scheduled event for this product
		$this->unschedule_event($product_id);

		// Schedule the event for the product when the available date is reached
		if ($timestamp_site > $current_timestamp) {
			wp_schedule_single_event($timestamp_site, 'wc_pre_orders_update_stock_status', array($product_id));
		}
	}

	// Unschedule the event if needed (for example, if the date is changed)
	public function unschedule_event($product_id) {
		$timestamp = wp_next_scheduled('wc_pre_orders_update_stock_status', array($product_id));
		if ($timestamp) {
			wp_unschedule_event($timestamp, 'wc_pre_orders_update_stock_status', array($product_id));
		}
	}

	// Function to update stock status when the scheduled event fires
	public function update_pre_order_stock_status($product_id) {
		$product = wc_get_product($product_id);

		// Check if product exists and is still on pre-order status
		if ($product && $product->get_stock_status() === 'onpreorder') {
			// Update stock status to 'instock'
			$product->set_stock_status('instock');
			$product->save();

			// Delete the _pre_order_available_date meta after stock status changes to "instock"
			delete_post_meta($product_id, '_pre_order_available_date');

			// If it's a variation, update the parent product too
			$parent_id = wp_get_post_parent_id($product_id);
			if ($parent_id) {
				$parent_product = wc_get_product($parent_id);
				if ($parent_product) {
					$parent_product->set_stock_status('instock');
					$parent_product->save();
				}
			}
		}
	}

	public static function deactivate() {
		// Unschedule all pre-order events on plugin deactivation
		$crons = _get_cron_array();
		foreach ($crons as $timestamp => $cron) {
			foreach ($cron as $hook => $events) {
				if ($hook === 'wc_pre_orders_update_stock_status') {
					foreach ($events as $key => $event) {
						wp_unschedule_event($timestamp, 'wc_pre_orders_update_stock_status', $event['args']);
					}
				}
			}
		}
	}
}

new WC_Pre_Orders_Auto_Update_To_In_Stock();

// Register activation and deactivation hooks
register_deactivation_hook(__FILE__, array('WC_Pre_Orders_Auto_Update_To_In_Stock', 'deactivate'));
