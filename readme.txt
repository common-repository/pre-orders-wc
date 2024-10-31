=== Pre-Orders - Extended Stock Status for WooCommerce ===
Contributors: yoohw
Tags: pre order, pre-orders, preorder, backorder, stock status
Requires at least: 6.3
Tested up to: 6.6.2
WC tested up to: 9.3.3
Requires PHP: 7.2
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Just another product stock status for your WooCommerce store.

== Description ==
The plugin allows you to manage pre-orders for your products within the WooCommerce platform. It enables customers to pre-order products before they are available, helping you gauge demand and manage inventory efficiently.

== Features ==
* Adds a new `On preorder` stock status for both Simple and Variable products.
* Allows you to choose the available date for the pre-order products.
* Displays `Pre-order now` button for both Simple products and Variations of the products.
* Shows the available date at product pages, product lists, cart, and checkout pages.
* Shows the icons for the orders within the only or mixed `On preorder` product at order lists and order pages.
* Included the `On preorder` status and available date at the order table in order email notifications.
* Automatically updates stock status from `On preorder` to `In stock` on the available date.
* Supports various customization options for pre-order messages and labels.

== Plugin Integrations ==

[Order Splitter - Split & Duplicate orders for WooCommerce](https://wordpress.org/plugins/wc-order-splitter/): Split orders within product stock status, and automatically split orders with `On preorder` product status by Order Splitter Premium.

== Installation ==
1. **Upload Plugin**: Upload the `wc-pre-orders` folder to the `/wp-content/plugins/` directory.
2. **Activate Plugin**: Activate the plugin through the 'Plugins' menu in WordPress.
3. **Prerequisites**: Ensure that WooCommerce is installed and activated.

== Usage ==
1. **Enable Pre-Orders**: Navigate to the WooCommerce product edit page and set the stock status to 'On preorder'.
2. **Set Availability Date**: Choose the available date for the pre-order product in the product data section.
3. **Manage Orders**: Pre-order products will display a 'Pre-order now' button on product pages and variations, showing the availability date in the product details, cart, and checkout pages.

== Frequently Asked Questions ==

**Q: How do I enable pre-orders for a product?**  
A: To enable pre-orders, navigate to the WooCommerce product edit page and set the stock status to 'On preorder'. You can then select the availability date for when the product will be in stock.

**Q: Can I enable pre-orders for variable products?**
A: Yes, you can enable pre-orders for both Simple and Variable products. Simply set the stock status of each variation to 'On preorder' and select the available date for each variation.

**Q: Will customers see the availability date of a pre-order product?**
A: Yes, the availability date will be displayed on the product page, product list, cart, and checkout pages, ensuring customers are aware of when the product will be available.

**Q: What happens when the availability date is reached?**  
A: When the availability date is reached, the stock status will automatically update from 'On preorder' to 'In stock'. Customers can then purchase the product as a regular in-stock item.

== Screenshots ==
1. Display at product page.
2. Display at product list (shop).
3. Easily seeing the status at product list.
4. Simply set 'Onpreorder' for simple product.
5. Just the same with variable product.
6. Icons for orders with the only and mixed product stock statuses.

== Changelog ==

= 1.0.2 (Oct 29, 2024) =
* New: Added the available date and time in products list.
* New: Added the `Pre-order` info at order table in order email notifications.
* New: Added icons for the orders with the only or mixed product stock statuses.

= 1.0.1 (Oct 23, 2024) =
* New: Added time in the available date fields.
* Improved: Changed the cron functions to automatically update product stock status.
* Improved: Minor css updated.
* Fixed: Variation available date cannot be saved.

= 1.0.0 (Oct 21, 2024) =
* First released.