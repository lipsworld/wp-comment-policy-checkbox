<?php

/**
 * WP Comment Policy Checkbox bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://franciscogodoy.es
 * @since             1.0.0
 * @package           Wpcpc
 *
 * @wordpress-plugin
 * Plugin Name:       WP Comment Policy Checkbox
 * Plugin URI:        wpcpc
 * Description:       Add a required policy checkbox to any comment forms width
 *                    custom link.
 * Version:           1.0.0
 * Author:            fcojgodoy
 * Author URI:        franciscogodoy.es
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpcpc
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );


/**
 * Admin
 *
 */
 if ( is_admin() ) {
    // We are in admin mode
    require_once ( plugin_dir_path ( __FILE__ ) . 'admin/wpcpc-admin.php' );
 }


/**
* Load the plugin text domain for translation.
*
*/
function wpcpc_load_plugin_textdomain() {

	load_plugin_textdomain(
		'wpcpc',
		false,
		basename( dirname( __FILE__ ) ) . '/languages/'
	);

}

add_action( 'plugins_loaded', 'wpcpc_load_plugin_textdomain' );


/**
* Create cistp, fields
*
*/
function wpcpc_custom_fields($fields) {

	// Multilingual strings
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

	if ( function_exists( 'pll_register_string' ) ) {
		$url = get_permalink ( pll_get_post( get_option( 'wpcpc_policy_page_id' ) ) );
		$read_and_accept = pll__( 'I have read and accept the ' );
	} else {
		$url = get_permalink ( get_option( 'wpcpc_policy_page_id' ) );
		$read_and_accept = __( 'I have read and accept the ', 'wpcpc' );
	}

    $fields[ 'policy' ] =
        '<p class="comment-form-policy">'.
            '<label for="policy">
                <input name="policy" value="policy-key" class="comment-form-policy__input" type="checkbox" style="width:auto"' . $aria_req . '>
                ' . $read_and_accept . '
                <a href="' . esc_url( $url ) . '" target="_blanck" class="comment-form-policy__see-more-link">' . __('Privacy Policy', 'wpcpc') . '</a>
                <span class="comment-form-policy__required required">*</span>
            </label>
        </p>';

    return $fields;
}

add_filter('comment_form_default_fields', 'wpcpc_custom_fields');


/**
* Add the filter to check whether the comment meta data has been filled
*
*/
function wpcpc_verify_policy_check( $polictydata ) {
    if ( ! isset( $_POST['policy'] ) )

		if ( function_exists( 'pll_register_string' ) ) {
			$policy_check_error = pll__( 'Error: you must accept the Privacy Policy.' );
		} else {
			$policy_check_error = __( 'Error: you must accept the Privacy Policy.', 'wpcpc' );
		}

    	wp_die( $policy_check_error . '<p><a href="javascript:history.back()">' . __('&laquo; Back') . '</a></p>');

    return $polictydata;
}

add_filter( 'preprocess_comment', 'wpcpc_verify_policy_check' );