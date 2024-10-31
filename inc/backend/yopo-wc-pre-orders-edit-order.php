<?php

if (!defined('ABSPATH')) {
	exit;
}

class WC_Pre_Orders_Edit_Order {
	public function __construct() {
		add_action('woocommerce_before_order_itemmeta', array($this, 'display_pre_order_status_in_order'), 10, 3);
		add_action('woocommerce_admin_order_data_after_order_details', [$this, 'add_pre_order_inline_css']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
	}

	public function display_pre_order_status_in_order($item_id, $item, $order) {
		// Ensure the item is a product
		if (is_a($item, 'WC_Order_Item_Product')) {
			$product = $item->get_product();
			if ($product && $product->get_stock_status() === 'onpreorder') {
				$pre_order_message = '<div class="item-meta-preorder"><strong>' . esc_html__('Pre-order', 'pre-orders-wc') . '</strong>';
				$pre_order_date = get_post_meta($product->get_id(), '_pre_order_available_date', true);
				if ($pre_order_date) {
					/* translators: %s: the available date of the pre-order product */
					$pre_order_message .= '<strong>:</strong> ' . sprintf(esc_html__('Available on %s', 'pre-orders-wc'), esc_html(date_i18n(get_option('date_format'), strtotime($pre_order_date))));
				}
				$pre_order_message .= '</div>';
				echo wp_kses_post($pre_order_message);
			}
		}
	}

	public function add_pre_order_inline_css($order) {
		// Initialize flags for stock statuses
		$only_preorder = true;
		$has_preorder = false;
	
		foreach ($order->get_items() as $item) {
			$product = $item->get_product();
			if ($product) {
				$stock_status = $product->get_stock_status();
	
				if ($stock_status === 'onpreorder') {
					$has_preorder = true;
				} else {
					$only_preorder = false;
				}
			}
		}
	
		// Apply styles based on the stock status conditions
		if ($only_preorder) {
			// If only 'onpreorder' products
			echo '<style>
				h2.woocommerce-order-data__heading::before {
					content: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMjRweCIgaGVpZ2h0PSIyNHB4IiB2aWV3Qm94PSIwIDAgMjQgMjQiIHZlcnNpb249IjEuMSI+CjxnIGlkPSJzdXJmYWNlMSI+CjxwYXRoIHN0eWxlPSIgc3Ryb2tlOm5vbmU7ZmlsbC1ydWxlOm5vbnplcm87ZmlsbDpyZ2IoODMuOTIxNTclLDIxLjE3NjQ3MSUsMjEuOTYwNzg0JSk7ZmlsbC1vcGFjaXR5OjE7IiBkPSJNIDkgMjQgTCAxIDI0IEMgMC40NDkyMTkgMjQgMCAyMy41NTA3ODEgMCAyMyBDIDAgMjIuNDQ5MjE5IDAuNDQ5MjE5IDIyIDEgMjIgTCA5IDIyIEMgOS41NTA3ODEgMjIgMTAgMjIuNDQ5MjE5IDEwIDIzIEMgMTAgMjMuNTUwNzgxIDkuNTUwNzgxIDI0IDkgMjQgWiBNIDkgMjQgIi8+CjxwYXRoIHN0eWxlPSIgc3Ryb2tlOm5vbmU7ZmlsbC1ydWxlOm5vbnplcm87ZmlsbDpyZ2IoODMuOTIxNTclLDIxLjE3NjQ3MSUsMjEuOTYwNzg0JSk7ZmlsbC1vcGFjaXR5OjE7IiBkPSJNIDcgMjAgTCAxIDIwIEMgMC40NDkyMTkgMjAgMCAxOS41NTA3ODEgMCAxOSBDIDAgMTguNDQ5MjE5IDAuNDQ5MjE5IDE4IDEgMTggTCA3IDE4IEMgNy41NTA3ODEgMTggOCAxOC40NDkyMTkgOCAxOSBDIDggMTkuNTUwNzgxIDcuNTUwNzgxIDIwIDcgMjAgWiBNIDcgMjAgIi8+CjxwYXRoIHN0eWxlPSIgc3Ryb2tlOm5vbmU7ZmlsbC1ydWxlOm5vbnplcm87ZmlsbDpyZ2IoODMuOTIxNTclLDIxLjE3NjQ3MSUsMjEuOTYwNzg0JSk7ZmlsbC1vcGFjaXR5OjE7IiBkPSJNIDUgMTYgTCAxIDE2IEMgMC40NDkyMTkgMTYgMCAxNS41NTA3ODEgMCAxNSBDIDAgMTQuNDQ5MjE5IDAuNDQ5MjE5IDE0IDEgMTQgTCA1IDE0IEMgNS41NTA3ODEgMTQgNiAxNC40NDkyMTkgNiAxNSBDIDYgMTUuNTUwNzgxIDUuNTUwNzgxIDE2IDUgMTYgWiBNIDUgMTYgIi8+CjxwYXRoIHN0eWxlPSIgc3Ryb2tlOm5vbmU7ZmlsbC1ydWxlOm5vbnplcm87ZmlsbDpyZ2IoODMuOTIxNTclLDIxLjE3NjQ3MSUsMjEuOTYwNzg0JSk7ZmlsbC1vcGFjaXR5OjE7IiBkPSJNIDEzIDIzLjk1MzEyNSBDIDEyLjQ0OTIxOSAyMy45ODA0NjkgMTEuOTgwNDY5IDIzLjU1MDc4MSAxMS45NTcwMzEgMjMgQyAxMS45Mjk2ODggMjIuNDQ1MzEyIDEyLjM1OTM3NSAyMS45ODA0NjkgMTIuOTEwMTU2IDIxLjk1MzEyNSBDIDE4LjE3NTc4MSAyMS40NzI2NTYgMjIuMTU2MjUgMTYuOTc2NTYyIDIxLjk5NjA5NCAxMS42OTE0MDYgQyAyMS44MzIwMzEgNi40MDYyNSAxNy41ODU5MzggMi4xNjAxNTYgMTIuMzAwNzgxIDIgQyA3LjAxNTYyNSAxLjg0Mzc1IDIuNTE5NTMxIDUuODI0MjE5IDIuMDM5MDYyIDExLjA4OTg0NCBDIDEuOTkyMTg4IDExLjY0MDYyNSAxLjUwMzkwNiAxMi4wNDY4NzUgMC45NTMxMjUgMTEuOTk2MDk0IEMgMC40MDYyNSAxMS45NDUzMTIgMCAxMS40NjA5MzggMC4wNTA3ODEyIDEwLjkxMDE1NiBDIDAuNjI4OTA2IDQuNTE5NTMxIDYuMTMyODEyIC0wLjI4NTE1NiAxMi41NDY4NzUgMC4wMDM5MDYyNSBDIDE4Ljk1NzAzMSAwLjI5Njg3NSAyNC4wMDM5MDYgNS41ODIwMzEgMjQgMTIgQyAyNC4wMzEyNSAxOC4yMTg3NSAxOS4yODUxNTYgMjMuNDE3OTY5IDEzLjA4OTg0NCAyMy45NDkyMTkgQyAxMy4wNTg1OTQgMjMuOTUzMTI1IDEzLjAyNzM0NCAyMy45NTMxMjUgMTMgMjMuOTUzMTI1IFogTSAxMyAyMy45NTMxMjUgIi8+CjxwYXRoIHN0eWxlPSIgc3Ryb2tlOm5vbmU7ZmlsbC1ydWxlOm5vbnplcm87ZmlsbDpyZ2IoODMuOTIxNTclLDIxLjE3NjQ3MSUsMjEuOTYwNzg0JSk7ZmlsbC1vcGFjaXR5OjE7IiBkPSJNIDEyIDYgQyAxMS40NDkyMTkgNiAxMSA2LjQ0OTIxOSAxMSA3IEwgMTEgMTIgQyAxMSAxMi4yNjU2MjUgMTEuMTA1NDY5IDEyLjUxOTUzMSAxMS4yOTI5NjkgMTIuNzA3MDMxIEwgMTQuMjkyOTY5IDE1LjcwNzAzMSBDIDE0LjY4MzU5NCAxNi4wODU5MzggMTUuMzA4NTk0IDE2LjA4MjAzMSAxNS42OTUzMTIgMTUuNjk1MzEyIEMgMTYuMDgyMDMxIDE1LjMwODU5NCAxNi4wODU5MzggMTQuNjgzNTk0IDE1LjcwNzAzMSAxNC4yOTI5NjkgTCAxMyAxMS41ODU5MzggTCAxMyA3IEMgMTMgNi40NDkyMTkgMTIuNTUwNzgxIDYgMTIgNiBaIE0gMTIgNiAiLz4KPC9nPgo8L3N2Zz4K");
					display: inline-block;
					transform: scale(1.2);
					margin-right: 10px;
				}
			</style>';
		} elseif ($has_preorder) {
			// If mixed 'onpreorder' and other stock statuses
			echo '<style>
				h2.woocommerce-order-data__heading::before {
					content: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMjRweCIgaGVpZ2h0PSIyNHB4IiB2aWV3Qm94PSIwIDAgMjQgMjQiIHZlcnNpb249IjEuMSI+CjxnIGlkPSJzdXJmYWNlMSI+CjxwYXRoIHN0eWxlPSIgc3Ryb2tlOm5vbmU7ZmlsbC1ydWxlOm5vbnplcm87ZmlsbDpyZ2IoODMuOTIxNTclLDIxLjE3NjQ3MSUsMjEuOTYwNzg0JSk7ZmlsbC1vcGFjaXR5OjE7IiBkPSJNIDE5LjUgMCBMIDQuNSAwIEMgMi4wMTk1MzEgMCAwIDIuMDE5NTMxIDAgNC41IEwgMCA1IEMgMCA1Ljg4MjgxMiAwLjM5MDYyNSA2LjY3MTg3NSAxIDcuMjE4NzUgTCAxIDE5IEMgMSAyMS43NTc4MTIgMy4yNDIxODggMjQgNiAyNCBMIDkgMjQgQyA5LjU1MDc4MSAyNCAxMCAyMy41NTA3ODEgMTAgMjMgQyAxMCAyMi40NDkyMTkgOS41NTA3ODEgMjIgOSAyMiBMIDYgMjIgQyA0LjM0NzY1NiAyMiAzIDIwLjY1MjM0NCAzIDE5IEwgMyA4IEwgMjEgOCBMIDIxIDkgQyAyMSA5LjU1MDc4MSAyMS40NDUzMTIgMTAgMjIgMTAgQyAyMi41NTQ2ODggMTAgMjMgOS41NTA3ODEgMjMgOSBMIDIzIDcuMjE4NzUgQyAyMy42MDkzNzUgNi42NzE4NzUgMjQgNS44ODI4MTIgMjQgNSBMIDI0IDQuNSBDIDI0IDIuMDE5NTMxIDIxLjk4MDQ2OSAwIDE5LjUgMCBaIE0gMyA2IEMgMi40NDkyMTkgNiAyIDUuNTUwNzgxIDIgNSBMIDIgNC41IEMgMiAzLjEyMTA5NCAzLjEyMTA5NCAyIDQuNSAyIEwgMTkuNSAyIEMgMjAuODc4OTA2IDIgMjIgMy4xMjEwOTQgMjIgNC41IEwgMjIgNSBDIDIyIDUuNTUwNzgxIDIxLjU1MDc4MSA2IDIxIDYgWiBNIDE3IDEwIEMgMTMuMTQwNjI1IDEwIDEwIDEzLjE0MDYyNSAxMCAxNyBDIDEwIDIwLjg1OTM3NSAxMy4xNDA2MjUgMjQgMTcgMjQgQyAyMC44NTkzNzUgMjQgMjQgMjAuODU5Mzc1IDI0IDE3IEMgMjQgMTMuMTQwNjI1IDIwLjg1OTM3NSAxMCAxNyAxMCBaIE0gMTcgMjIgQyAxNC4yNDIxODggMjIgMTIgMTkuNzU3ODEyIDEyIDE3IEMgMTIgMTQuMjQyMTg4IDE0LjI0MjE4OCAxMiAxNyAxMiBDIDE5Ljc1NzgxMiAxMiAyMiAxNC4yNDIxODggMjIgMTcgQyAyMiAxOS43NTc4MTIgMTkuNzU3ODEyIDIyIDE3IDIyIFogTSAxOS4yMDcwMzEgMTcuNzkyOTY5IEMgMTkuNTk3NjU2IDE4LjE4MzU5NCAxOS41OTc2NTYgMTguODE2NDA2IDE5LjIwNzAzMSAxOS4yMDcwMzEgQyAxOS4wMTE3MTkgMTkuNDAyMzQ0IDE4Ljc1NzgxMiAxOS41IDE4LjUgMTkuNSBDIDE4LjI0MjE4OCAxOS41IDE3Ljk4ODI4MSAxOS40MDIzNDQgMTcuNzkyOTY5IDE5LjIwNzAzMSBMIDE2LjI5Mjk2OSAxNy43MDcwMzEgQyAxNi4xMDU0NjkgMTcuNTE5NTMxIDE2IDE3LjI2NTYyNSAxNiAxNyBMIDE2IDE1IEMgMTYgMTQuNDQ5MjE5IDE2LjQ0NTMxMiAxNCAxNyAxNCBDIDE3LjU1NDY4OCAxNCAxOCAxNC40NDkyMTkgMTggMTUgTCAxOCAxNi41ODU5MzggWiBNIDE5LjIwNzAzMSAxNy43OTI5NjkgIi8+CjwvZz4KPC9zdmc+Cg==");
					display: inline-block;
					transform: scale(1.2);
					margin-right: 10px;
				}
			</style>';
		}
	}

	public function enqueue_admin_scripts($hook) {
		// Load script only on the WooCommerce orders list page
		if ('edit.php' === $hook || 'woocommerce_page_wc-orders' === $hook) {
	
			wp_enqueue_script('yopo_wc_orders_script', plugin_dir_url(__FILE__) . '../../js/yopo-wc-pre-orders-orders.js', ['jquery'], '1.0', true);

			// Pass plugin URL to JavaScript
			wp_localize_script('yopo_wc_orders_script', 'myPluginData', [
				'pluginUrl' => plugin_dir_url(__FILE__),
			]);
	

			// Get the user's preference for items per page from Screen Options or default to 20
			$user_id = get_current_user_id();
			$per_page = (int) get_user_meta($user_id, 'edit_shop_order_per_page', true) ?: 20;

			// Get the current page number
			$current_page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
	
			// Get orders for the current page
			$orders = wc_get_orders([
				'limit' => $per_page,
				'paged' => $current_page,
			]);
	
			$order_data = [];
	
			foreach ($orders as $order) {
				$only_preorder = true;
				$has_preorder = false;
	
				foreach ($order->get_items() as $item) {
					$product = $item->get_product();
					if ($product) {
						$stock_status = $product->get_stock_status();
	
						if ($stock_status === 'onpreorder') {
							$has_preorder = true;
						} else {
							$only_preorder = false;
						}
					}
				}
	
				// Add the data to be used in JavaScript
				$order_data[$order->get_id()] = [
					'only_preorder' => $only_preorder,
					'has_preorder' => $has_preorder && !$only_preorder,
				];
			}
	
			wp_localize_script('yopo_wc_orders_script', 'yopowc_order_data', $order_data);
		}
	}	
}

new WC_Pre_Orders_Edit_Order();
