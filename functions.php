<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';
	require 'inc/nux/class-storefront-nux-starter-content.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */

// Connect the necessary files
require_once get_stylesheet_directory() . '/inc/cpt-cities.php';
require_once get_stylesheet_directory() . '/inc/taxonomy-countries.php';
require_once get_stylesheet_directory() . '/inc/meta-boxes.php';
require_once get_stylesheet_directory() . '/inc/weather-widget.php';
require_once get_stylesheet_directory() . '/inc/ajax-search.php';

// Register the page template
function register_custom_templates($templates) {
    $templates['templates/page-cities-table.php'] = 'Cities Table';
    return $templates;
}
add_filter('theme_page_templates', 'register_custom_templates');

add_action('wp_enqueue_scripts', 'enqueue_city_search_scripts');
function enqueue_city_search_scripts() {
    if (is_page_template('templates/page-cities-table.php')) {
        wp_enqueue_script('city-search', get_stylesheet_directory_uri() . '/assets/js/city-search.js', ['jquery'], null, true);
        wp_localize_script('city-search', 'citySearch', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('search_cities_nonce')
        ]);
    }
}

// Disabling the Gutenberg editor for all record types
add_filter('use_block_editor_for_post', '__return_false', 10);