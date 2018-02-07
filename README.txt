=== Plugin Name ===
Contributors: kishaneli5
Donate link: https://www.eli5.io
Tags: woocommerce, postcode, validation
Requires at least: 4.0 & WooCommerce 2.0+
Tested up to: 4.9.3 & WooCommerce 3.2.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce Postcode Validator lets you validate Dutch postcodes and auto-fill the address and city for the filled in postcode.

== Description ==

NOTE: This plugin depends on the LocatieServer API from PDOK: https://www.pdok.nl/nl/producten/pdok-locatieserver
This API enables the plugin to find and validate postcodes and find the corresponding address and city name for The Netherlands.

WooCommerce Postcode Validator lets you validate Dutch postcodes and auto-fill the address and city for the filled in postcode. This is achieved by using the BAG / LocatieServer API.

= Main features =
- Validate the filled in postal code for Dutch orders, and auto-fill the street name and city.

== Installation ==

= Automatic installation =
Search for "WooCommerce Postcode Validator" in the "Add new plugin" section in the admin panel, and click "Install now".

= Manual installation =

1. Download the zipfile.
2. Extract `woocommerce-postcode-validator` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= How can I change the loader =

You can modify the loader by overriding the CSS for the input fields, the current loader is being put as a background image for all `.woocommerce-billing-fields input.woocommerce-postcode-validator-loading` classes.

== Changelog ==

= 1.0 =
* First release