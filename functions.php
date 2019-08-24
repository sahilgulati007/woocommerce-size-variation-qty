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

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
		require 'inc/nux/class-storefront-nux-starter-content.php';
	}
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */


function woocommerce_ajax_add_to_cart_js() {
    if (function_exists('is_product') && is_product()) {
        //wp_enqueue_script('woocommerce-ajax-add-to-cart', plugin_dir_url(__FILE__) . 'assets/ajax-add-to-cart.js', array('jquery'), '', true);
        wp_enqueue_script('woocommerce-ajax-add-to-cart', get_template_directory_uri() . '/ajax-add-to-cart.js', array('jquery'), '', true);
        wp_localize_script( 'woocommerce-ajax-add-to-cart', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    }
}
add_action('wp_enqueue_scripts', 'woocommerce_ajax_add_to_cart_js', 99);


add_action( 'wp_ajax_get_variation_id_from_attributes', 'get_variation_id_from_attributes' );
add_action( 'wp_ajax_nopriv_get_variation_id_from_attributes', 'get_variation_id_from_attributes' );
 function get_variation_id_from_attributes() {
     $colour = strtolower($_POST['color']);
     $size = strtolower($_POST['size']);
     $product_id = intval($_POST['prod_id']);

     $variation_id = find_matching_product_variation_id ( $product_id,array(
         'attribute_pa_color' => $colour,
         'attribute_pa_size' => $size
 ));

     echo $variation_id;
     wp_die();
 }
function find_matching_product_variation_id($product_id, $attributes)
{
    return (new \WC_Product_Data_Store_CPT())->find_matching_product_variation(
        new \WC_Product($product_id),
        $attributes
    );
}