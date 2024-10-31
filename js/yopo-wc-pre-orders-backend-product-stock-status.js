jQuery(document).ready(function($) {
	function togglePreOrderDateField() {
		// Loop through stock status fields for single products
		$('[id^="_stock_status"]').each(function() {
			var stockStatus = $(this).val();
			var preOrderDateField = $(this).closest('.form-field').siblings('.form-field').find('[id^="_pre_order_available_date"]').closest('.form-field');

			// Show or hide the date field based on stock status
			if (stockStatus === 'onpreorder') {
				preOrderDateField.show();
			} else {
				preOrderDateField.hide();
			}
		});

		// Loop through variations' stock status fields for variable products
		$('.woocommerce_variation').each(function() {
			var stockStatusSelect = $(this).find('[id^="variable_stock_status"]');
			if (stockStatusSelect.length > 0) {
				var stockStatus = stockStatusSelect.val();
				var variationId = $(this).find('input.variable_post_id').val();
				var preOrderDateField = $(this).find(`[id="_pre_order_available_date_${variationId}"]`).closest('.form-row');

				// Show or hide the date field based on stock status
				if (stockStatus === 'onpreorder') {
					preOrderDateField.show();
				} else {
					preOrderDateField.hide();
				}
			}
		});
	}

	// Run the toggle function on initial load for single products
	togglePreOrderDateField();

	// Check on stock status change for single products
	$(document).on('change', '[id^="_stock_status"]', function() {
		togglePreOrderDateField();
	});

	// Check on stock status change for variable product variations
	$(document).on('change', '[id^="variable_stock_status"]', function() {
		togglePreOrderDateField();
	});

	// Ensure variations are fully loaded before running the toggle function for variable products
	$(document).on('woocommerce_variations_loaded', function() {
		togglePreOrderDateField();
	});
});
