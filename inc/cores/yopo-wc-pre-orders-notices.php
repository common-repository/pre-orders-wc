<?php

if (!defined('ABSPATH')) {
	exit;
}

class WC_Pre_Orders_Notices {

	public function __construct() {
		add_action('admin_notices', [$this, 'check_woocommerce_active']);
		add_action('admin_notices', [$this, 'admin_notice']);
		add_action('wp_ajax_dismiss_wc_pre_orders_notice', [$this, 'dismiss_notice']);
		add_action('wp_ajax_never_show_wc_pre_orders_notice', [$this, 'never_show_notice']);
	}

	public function check_woocommerce_active() {
		if (!is_plugin_active('woocommerce/woocommerce.php')) {
			$this->yopo_missing_wc_notice();
		}
	}

	public function yopo_missing_wc_notice() {
		?>
		<div class="notice notice-warning is-dismissible">
			<p><?php echo esc_html__('WooCommerce Pre-Orders requires WooCommerce to be installed and activated.', 'pre-orders-wc'); ?></p>
		</div>
		<?php
	}

	public function admin_notice() {
		$user_id = get_current_user_id();
		$activation_time = get_user_meta($user_id, 'wc_pre_orders_activation_time', true);
		$current_time = current_time('timestamp');

		if (get_user_meta($user_id, 'wc_pre_orders_never_show_again', true) === 'yes') {
			return;
		}

		if (!$activation_time) {
			update_user_meta($user_id, 'wc_pre_orders_activation_time', $current_time);
			return;
		}

		$time_since_activation = $current_time - $activation_time;
		$days_since_activation = floor($time_since_activation / DAY_IN_SECONDS);

		if ($days_since_activation >= 1 && (($days_since_activation - 1) % 90 === 0)) {
			if (get_user_meta($user_id, 'wc_pre_orders_notice_dismissed', true) !== 'yes') {
				echo '<div class="notice notice-info is-dismissible">
					<p>Thank you for using WooCommerce Pre-Orders! Please support us by <a href="https://wordpress.org/plugins/pre-orders-wc/#reviews" target="_blank">leaving a review</a> <span style="color: #e26f56;">&#9733;&#9733;&#9733;&#9733;&#9733;</span> to keep updating & improving.</p>
					<p><a href="#" onclick="dismissForever()">Never show this again</a></p>
				</div>';
				add_action('admin_footer', [$this, 'wc_pre_orders_admin_footer_scripts']);
			}
		}
	}

	public function wc_pre_orders_admin_footer_scripts() {
		// Enqueue a script that will be used as a handle for wp_add_inline_script
		wp_enqueue_script( 'wc-pre-orders-admin', false, array( 'jquery' ), '', true );
	
		// Use wp_add_inline_script to inject the inline script
		$inline_script = '
			var wc_pre_orders_Admin_Notice = {
				dismissForever: function() {
					jQuery.ajax({
						url: ajaxurl,
						type: "POST",
						data: {
							action: "never_show_wc_pre_orders_notice",
						},
						success: function(response) {
							jQuery(".notice.notice-info").hide();
						}
					});
				}
			};
			jQuery(document).on("click", ".notice.is-dismissible", function() {
				jQuery.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						action: "dismiss_wc_pre_orders_notice",
					}
				});
			});
		';
	
		wp_add_inline_script( 'wc-pre-orders-admin', $inline_script );
	}	

	public function dismiss_notice() {
		$user_id = get_current_user_id();
		update_user_meta($user_id, 'wc_pre_orders_notice_dismissed', 'yes');
	}

	public function never_show_notice() {
		$user_id = get_current_user_id();
		update_user_meta($user_id, 'wc_pre_orders_never_show_again', 'yes');
	}
}

new WC_Pre_Orders_Notices();
