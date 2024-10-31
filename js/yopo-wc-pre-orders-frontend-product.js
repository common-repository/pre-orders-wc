jQuery(document).ready(function($) {
    // Listen for variation changes
    $('.variations_form').on('found_variation', function(event, variation) {
        // Check the stock status of the selected variation
        if (variation.stock_status === 'onpreorder') {
            $('.single_add_to_cart_button').text('Pre-order now');
        } else {
            $('.single_add_to_cart_button').text('Add to cart');
        }
    });

    // Handle cases where no variation is selected or variation is reset
    $('.variations_form').on('reset_data', function() {
        $('.single_add_to_cart_button').text('Add to cart');
    });
});
